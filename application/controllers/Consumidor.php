<?php
class Consumidor extends CI_Controller{
    
    public function lista_usuarios(){
        $this->load->database();
        $this->load->model("usuario_model");
        $usuarios = $this->usuario_model->retornar_todos();

        $dados = array("usuarios"=>$usuarios);
        echo $this->myjson->my_json_encode($dados);
    }

    public function lista_emails(){
        $this->load->database();
        $this->load->model("email_model");
        $emails = $this->email_model->retornar_todos();

        $dados = array("emails"=>$emails);
        echo $this->myjson->my_json_encode($dados);
    }

    public function adicionar_consumidor()
    {
    	if((isset($_POST["tipo_usuario_id"]) && !empty($_POST["tipo_usuario_id"])) &&
            (isset($_POST["consumidor_nome"]) && !empty($_POST["consumidor_nome"])) &&
    		(isset($_POST["consumidor_sobrenome"]) && !empty($_POST["consumidor_sobrenome"])) &&
    		(isset($_POST["email_descricao"]) && !empty($_POST["email_descricao"])) &&
    		(isset($_POST["tipo_telefone_id"]) && !empty($_POST["tipo_telefone_id"])) &&
            (isset($_POST["telefone_ddd"]) && !empty($_POST["telefone_ddd"])) &&
            (isset($_POST["telefone_numero"]) && !empty($_POST["telefone_numero"]))
    	  )
    	{
            $objeto_recebido = array('tipo_usuario_id' => $this->input->post("tipo_usuario_id"), 
                                     'consumidor_nome' => $this->input->post("consumidor_nome"),
                                     'consumidor_sobrenome' => $this->input->post("consumidor_sobrenome"),
                                     'email_descricao' => $this->input->post("email_descricao"),
                                     'tipo_telefone_id' => $this->input->post("tipo_telefone_id"),
                                     'telefone_ddd' => $this->input->post("telefone_ddd"),
                                     'telefone_numero' => $this->input->post("telefone_numero"),
                                     );

            $Email = array(
                "email_descricao" => $objeto_recebido["email_descricao"]
            );

            $this->load->model("email_model");
            $email_id = $this->email_model->adicionar_email($Email);

            if(!empty($email_id)){
                $this->load->model("usuario_model");

                $prox_usuario_id = $this->usuario_model->retornar_id_prox_usuario($objeto_recebido["tipo_usuario_id"]);
                //var_dump($prox_usuario_id);

                $usuario = array(
                    "usuario_id" => $prox_usuario_id,
                    "tipo_usuario_id" => $objeto_recebido["tipo_usuario_id"],
                    "status_id" => 4,
                    "usuario_senha" => $objeto_recebido["telefone_ddd"].$objeto_recebido["telefone_numero"],
                    "usuario_login" => $objeto_recebido["email_descricao"],
                    "usuario_data_cadastro" => date ("Y-m-d"),
                    "email_id" => $email_id       
                );

                $lala = $this->usuario_model->adicionar_usuario($usuario);
                $usuario_id = $this->usuario_model->retornar_max_id();
                //var_dump($usuario_id);

                if(!empty($usuario_id)){
                     $consumidor = array(
                        "usuario_id" => $usuario_id,
                        "tipo_usuario_id" => $objeto_recebido["tipo_usuario_id"],
                        "consumidor_nome" => $objeto_recebido["consumidor_nome"],
                        "consumidor_sobrenome" => $objeto_recebido["consumidor_sobrenome"]     
                    );
                    $this->load->model("consumidor_model");
                    $resp = $this->consumidor_model->adicionar_consumidor($consumidor);

                    $telefone = array(
                        "tipo_telefone_id" => $objeto_recebido["tipo_telefone_id"],
                        "telefone_ddd" => $objeto_recebido["telefone_ddd"],
                        "telefone_numero" => $objeto_recebido["telefone_numero"]    
                    );
                    $this->load->model("telefone_model");
                    $telefone_id = $this->telefone_model->adicionar_telefone($telefone);

                    $consumidor_telefone = array(
                        "usuario_id" => $usuario_id,
                        "tipo_usuario_id" => $objeto_recebido["tipo_usuario_id"],
                        "telefone_id" => $telefone_id    
                    );
                    $this->load->model("consumidor_telefone_model");
                    $resposta = $this->consumidor_telefone_model->adicionar_consumidor_telefone($consumidor_telefone);

                    if($resposta == "SUCESSO"){
                        $resp = array("status" => "ok",
                                      "usuario_senha" => $usuario["usuario_senha"]
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }else{
                        $resp = array("status" => "nop",
                                      "descricao" => "Erro ao inserir consumidor_telefone"
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }
                }else{
                    $resp = array("status" => "nop",
                                  "descricao" => "Erro ao retornar usuario_id"
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }

            }else{
                $resp = array("status" => "nop",
                              "descricao" => "Erro ao retornar email_id");
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
    	}else{
            $resp = array("status" => "nop",
                          "descricao" => "Parametros inválidos"
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
    	}
    }

    public function autentificacao()
    {
        if((isset($_POST["usuario_login"]) && !empty($_POST["usuario_login"])) &&
            (isset($_POST["usuario_senha"]) && !empty($_POST["usuario_senha"])))
        {
            $acesso = array('usuario_login' => $this->input->post("usuario_login"), 
                            'usuario_senha' => $this->input->post("usuario_senha"));

            $this->load->model("usuario_model");
            $usuario = $this->usuario_model->retornar_por_login($this->input->post("usuario_login"));

            if($usuario == NULL){
                $resp = array(
                    "status" => "nop",
                    "descricao" => "Usuario não encontrato"
                );
                $dados = array("retorno"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }else{
                var_dump($usuario["usuario_senha"]);
                var_dump($acesso["usuario_senha"]);

                if($usuario["usuario_senha"] == $acesso["usuario_senha"]){
                    $resp2 = array(
                        "status" => "ok",
                        "tipo_usuario_id" => $usuario["tipo_usuario_id"]
                    );
                    $dados = array("retorno"=>$resp2);
                    echo $this->myjson->my_json_encode($dados);
                }else{
                    $resp1 = array(
                        "status" => "nop",
                        "descricao" => "Senha invalida"
                    );
                    $dados = array("retorno"=>$resp1);
                    echo $this->myjson->my_json_encode($dados);
                }
            }
        }else{
            $resp3 = array(
                "status" => "nop",
                "descricao" => "post invalido"
            );
            $dados = array("retorno"=>$resp3);
            echo $this->myjson->my_json_encode($dados);
        }
    }
}
