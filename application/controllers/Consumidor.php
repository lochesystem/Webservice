<?php
class Consumidor extends CI_Controller{

    public function adicionar()
    {
        $data = json_decode(file_get_contents('php://input'));

    	if((isset($data->tipo_usuario_id) && !empty($data->tipo_usuario_id)) &&
            (isset($data->consumidor_nome) && !empty($data->consumidor_nome)) &&
    		(isset($data->consumidor_sobrenome) && !empty($data->consumidor_sobrenome)) &&
    		(isset($data->email_descricao) && !empty($data->email_descricao)) &&
    		(isset($data->tipo_telefone_id) && !empty($data->tipo_telefone_id)) &&
            (isset($data->telefone_ddd) && !empty($data->telefone_ddd)) &&
            (isset($data->telefone_numero) && !empty($data->telefone_numero)) &&
            (isset($data->usuario_senha) && !empty($data->usuario_senha))
    	  )
    	{
            $Email = array(
                "email_descricao" => $data->email_descricao
            );

            $this->load->model("email_model");
            $email_id = $this->email_model->adicionar_email($Email);

            if(!empty($email_id)){
                $this->load->model("usuario_model");
                $prox_usuario_id = $this->usuario_model->retornar_id_prox_usuario($data->tipo_usuario_id);

                date_default_timezone_set('America/Sao_Paulo');
                
                $usuario = array(
                    "usuario_id" => $prox_usuario_id,
                    "tipo_usuario_id" => $data->tipo_usuario_id,
                    "status_id" => 4,
                    "usuario_senha" => $data->usuario_senha,
                    "usuario_login" => $data->email_descricao,
                    "usuario_data_cadastro" => date('Y-m-d H:i'),
                    "email_id" => $email_id
                );

                $this->load->model("usuario_model");
                $retorno = $this->usuario_model->adicionar_usuario($usuario);
                $usuario_id = $this->usuario_model->retornar_max_id();

                if(!empty($usuario_id)){
                     $consumidor = array(
                        "usuario_id" => $usuario_id,
                        "tipo_usuario_id" => $data->tipo_usuario_id,
                        "consumidor_nome" => $data->consumidor_nome,
                        "consumidor_sobrenome" => $data->consumidor_sobrenome    
                    );
                    $this->load->model("consumidor_model");
                    $resp = $this->consumidor_model->adicionar_consumidor($consumidor);

                    $telefone = array(
                        "tipo_telefone_id" => $data->tipo_telefone_id,
                        "telefone_ddd" => $data->telefone_ddd,
                        "telefone_numero" => $data->telefone_numero    
                    );
                    $this->load->model("telefone_model");
                    $telefone_id = $this->telefone_model->adicionar_telefone($telefone);

                    $consumidor_telefone = array(
                        "usuario_id" => $usuario_id,
                        "tipo_usuario_id" => $data->tipo_usuario_id,
                        "telefone_id" => $telefone_id    
                    );
                    $this->load->model("consumidor_telefone_model");
                    $resposta = $this->consumidor_telefone_model->adicionar_consumidor_telefone($consumidor_telefone);

                    if($resposta == "SUCESSO"){
                        $this->EnviarEmailCadastroConsumidor($data);

                        $resp = array("status" => "true",
                                      "descricao" => "Consumidor cadastrado com sucesso!",
                                      "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }else{
                        $resp = array("status" => "false",
                                      "descricao" => "Erro ao inserir consumidor_telefone",
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

    public function EnviarEmailCadastroConsumidor($dadosConsumidor)
    {
        $assunto = 'MLprojetos - Cadastro de Consumidor';
        $conteudo = 'Olá ' .$dadosConsumidor->consumidor_nome. ' ' .$dadosConsumidor->consumidor_sobrenome. ', Seja Bem-vindo(a) !</br></br> Seu cadastro foi realizado com sucesso. </br></br> Dados de Acesso:</br> Login: ' .$dadosConsumidor->email_descricao. ' </br> Senha: ' .$dadosConsumidor->usuario_senha. ;

        if($this->enviaEmail($dadosConsumidor->email_descricao, $assunto, $conteudo))
            echo "SUCESSO";
        else
            echo "FALHA";  
    }

    public function enviaEmail($destinatario, $assunto, $conteudo) {
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
