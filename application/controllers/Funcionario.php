<?php
class Funcionario extends CI_Controller{

    public function adicionar()
    {
    	if((isset($_POST["funcionario_nome"]) && !empty($_POST["funcionario_nome"])) &&
            (isset($_POST["funcionario_sobrenome"]) && !empty($_POST["funcionario_sobrenome"])) &&
    		(isset($_POST["funcionario_cpf"]) && !empty($_POST["funcionario_cpf"])) &&
    		(isset($_POST["funcionario_cargo"]) && !empty($_POST["funcionario_cargo"])) &&
    		(isset($_POST["estabelecimento_id"]) && !empty($_POST["estabelecimento_id"])) &&
            (isset($_POST["email_descricao"]) && !empty($_POST["email_descricao"])) &&
    	  )
    	{
            $objeto_recebido = array('funcionario_nome' => $this->input->post("funcionario_nome"), 
                                     'funcionario_sobrenome' => $this->input->post("funcionario_sobrenome"),
                                     'funcionario_cpf' => $this->input->post("funcionario_cpf"),
                                     'funcionario_cargo' => $this->input->post("funcionario_cargo"),
                                     'email_descricao' => $this->input->post("email_descricao")
                                     );

            $Email = array(
                "email_descricao" => $objeto_recebido["email_descricao"]
            );
            $this->load->model("email_model");
            $email_id = $this->email_model->adicionar_email($Email);

            if(!empty($email_id))
            {
                $this->load->model("usuario_model");
                $prox_usuario_id = $this->usuario_model->retornar_id_prox_usuario($objeto_recebido["tipo_usuario_id"]);
                $usuario = array(
                    "usuario_id" => $prox_usuario_id,
                    "tipo_usuario_id" => $objeto_recebido["tipo_usuario_id"],
                    "status_id" => 4,
                    "usuario_senha" => $objeto_recebido["telefone_ddd"].$objeto_recebido["telefone_numero"],
                    "usuario_login" => $objeto_recebido["email_descricao"],
                    "usuario_data_cadastro" => date ("Y-m-d"),
                    "email_id" => $email_id       
                );
                $this->load->model("usuario_model");
                $retorno = $this->usuario_model->adicionar_usuario($usuario);
                $usuario_id = $this->usuario_model->retornar_max_id();

                if(!empty($usuario_id)){
                     $funcionario = array(
                        "usuario_id" => $usuario_id,
                        "tipo_usuario_id" => $objeto_recebido["tipo_usuario_id"],
                        "estabelecimento_id" => $objeto_recebido["tipo_usuario_id"],
                        "funcionario_nome" => $objeto_recebido["consumidor_nome"],
                        "funcionario_cnpj" => $objeto_recebido["consumidor_nome"],
                        "funconario_cargo" => $objeto_recebido["consumidor_sobrenome"]     
                    );
                    $this->load->model("funcionario_model");
                    $resposta = $this->funcionario_model->adicionar_funcionario($funcionario);

                    if($resposta == "SUCESSO"){
                        $resp = array("status" => "true",
                                      "descricao" => "Consumidor cadastrado com sucesso!",
                                      "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }else{
                        $resp = array("status" => "false",
                                      "descricao" => "Erro ao inserir consumidor_",
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
    }

}
