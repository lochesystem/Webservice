<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Funcionario extends CI_Controller{
    public function adicionar(){
        $data = json_decode(file_get_contents('php://input'));

    	if((isset($data->tipo_usuario_id) && !empty($data->tipo_usuario_id)) &&
            (isset($data->funcionario_nome) && !empty($data->funcionario_nome)) &&
    		(isset($data->funcionario_sobrenome) && !empty($data->funcionario_sobrenome)) &&
    		(isset($data->funcionario_cpf) && !empty($data->funcionario_cpf)) &&
    		(isset($data->cargo_id) && !empty($data->cargo_id)) &&
            (isset($data->email_descricao) && !empty($data->email_descricao)) &&
            (isset($data->estabelecimento_id) && !empty($data->estabelecimento_id)) &&
            (isset($data->usuario_senha) && !empty($data->usuario_senha))
    	  )
    	{
            $Email = array("email_descricao" => $data->email_descricao);

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
                    "usuario_senha" => $data->usuario_senha,
                    "usuario_login" => $data->email_descricao,
                    "usuario_data_cadastro" => date('Y-m-d H:i'),
                    "email_id" => $email_id);
                $this->load->model("usuario_model");
                $retorno = $this->usuario_model->adicionar_usuario($usuario);
                
                $this->load->model("usuario_model");
                $usuario_id = $this->usuario_model->retornar_max_id($data->tipo_usuario_id);

                if(!empty($usuario_id)){
                     $funcionario = array(
                        "usuario_id" => $usuario_id,
                        "tipo_usuario_id" => $data->tipo_usuario_id,
                        "estabelecimento_id" => $data->estabelecimento_id,
                        "funcionario_nome" => $data->funcionario_nome,
                        "funcionario_sobrenome" => $data->funcionario_sobrenome,
                        "funcionario_cpf" => $data->funcionario_cpf,  
                        "cargo_id" => $data->cargo_id
                    );
                    $this->load->model("funcionario_model");
                    $resp = $this->funcionario_model->adicionar_funcionario($funcionario);
                    
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
                                      "descricao" => "Erro ao registrar dados do funcionario.",
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

    public function getFuncionario($usuario_id, $tipo_usuario_id){
        $this->load->database();
        $this->load->model("funcionario_model");
        $funcionario = $this->funcionario_model->retornar_dados_funcionario($usuario_id, $tipo_usuario_id);
        
        $dados = array("consumidor"=>$consumidor);
        var_dump($dados);

        echo $this->myjson->my_json_encode($dados);
    }

    public function EnviarEmailCadastroConsumidor($dadosConsumidor)
    {
        $assunto = 'MLprojetos - Cadastro de Consumidor';
        $conteudo = 'Olá ' .$dadosConsumidor->consumidor_nome. ' ' .$dadosConsumidor->consumidor_sobrenome. ', Seja Bem-vindo(a) !</br></br> Seu cadastro foi realizado com sucesso. </br></br> Dados de Acesso:</br> Login: ' .$dadosConsumidor->email_descricao. ' </br> Senha: ' .$dadosConsumidor->usuario_senha;

        if($this->EnviaEmail($dadosConsumidor->email_descricao, $assunto, $conteudo))
            return true;
        else
            return false;  
    }

    public function EnviaEmail($destinatario, $assunto, $conteudo) {
        $this->load->library('email');
        $this->email->from('contato@mlprojetos.com', 'MLprojetos');
        $this->email->to($destinatario);
        $this->email->subject($assunto);
        $this->email->message($conteudo);
        if($this->email->send())
            return true;
        else
            return false;                  
    }
}