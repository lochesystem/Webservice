<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Funcionario extends CI_Controller{

 public function adicionar(){
        $data = json_decode(file_get_contents('php://input'));

    	if((isset($data->tipo_usuario_id) && !empty($data->tipo_usuario_id)) &&
            (isset($data->estabelecimento_id) && !empty($data->estabelecimento_id)) &&
            (isset($data->funcionario_nome) && !empty($data->funcionario_nome)) &&
    		(isset($data->funcionario_sobrenome) && !empty($data->funcionario_sobrenome)) &&
            (isset($data->funcionario_cpf) && !empty($data->funcionario_cpf)) &&
            (isset($data->funcionario_cargo) && !empty($data->funcionario_cargo)) &&
    		(isset($data->email_descricao) && !empty($data->email_descricao))
    	  ){
            $Email = array(
                "email_descricao" => $data->email_descricao
            );
            $this->load->model("email_model");
            $email_id = $this->email_model->adicionar($Email);

            if(!empty($email_id)){
                $this->load->model("usuario_model");
                $prox_usuario_id = $this->usuario_model->retornar_id_prox_usuario($data->tipo_usuario_id);

                date_default_timezone_set('America/Sao_Paulo');
                $usuario = array(
                    "usuario_id" => $prox_usuario_id,
                    "tipo_usuario_id" => $data->tipo_usuario_id,
                    "status_id" => 2, // Status Ativo
                    "usuario_senha" => "teste",
                    "usuario_login" => $data->email_descricao,
                    "usuario_data_cadastro" => date('Y-m-d H:i'),
                    "email_id" => $email_id);
                $retorno = $this->usuario_model->adicionar_usuario($usuario);
                $usuario_id = $this->usuario_model->retornar_max_id($data->tipo_usuario_id);

                if(!empty($usuario_id)){
                     $funcionario = array(
                        "usuario_id" => $usuario_id,
                        "tipo_usuario_id" => $data->tipo_usuario_id,
                        "estabelecimento_id" => $data->estabelecimento_id,
                        "funcionario_nome" => $data->funcionario_nome,
                        "funcionario_sobrenome" => $data->funcionario_sobrenome,
                        "funcionario_cpf" => $data->funcionario_cpf,
                        "funcionario_cargo" => $data->funcionario_cargo,   
                    );
                    $this->load->model("funcionario_model");
                    $resp = $this->funcionario_model->adicionar($funcionario);

                    if($resp == "SUCESSO"){
                        //$this->EnviarEmailCadastroConsumidor($data);
                        $resp = array("status" => "true",
                                      "descricao" => "Funcionario cadastrado com sucesso!",
                                      "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }else{
                        $resp = array("status" => "false",
                                      "descricao" => "Erro ao inserir funcionario",
                                      "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }
                }else{
                    $resp = array("status" => "false",
                                  "descricao" => "Erro ao retornar usuario_id",
                                  "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }
            }else{
                $resp = array("status" => "false",
                              "descricao" => "Erro ao retornar email_id",
                              "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
    	}else{
            $resp = array("status" => "false",
                          "descricao" => "Parametros inválidos",
                          "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
    	}
    }
}
