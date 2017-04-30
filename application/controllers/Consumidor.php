<?php
class Consumidor extends CI_Controller{
    public function adicionar()
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

}
