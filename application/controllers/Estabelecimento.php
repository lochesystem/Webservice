<?php
class Estabelecimento extends CI_Controller{

	public function adicionar()
    {
    	if((isset($_POST["estabelecimento_cnpj"]) && !empty($_POST["estabelecimento_cnpj"])) &&
            (isset($_POST["estabelecimento_razao_social"]) && !empty($_POST["estabelecimento_razao_social"])) &&
    		(isset($_POST["estabelecimento_nome_fantasia"]) && !empty($_POST["estabelecimento_nome_fantasia"])) &&
    		(isset($_POST["estabelecimento_inscrição_estatual"]) && !empty($_POST["estabelecimento_inscrição_estatual"])) &&
    		(isset($_POST["estabelecimento_inscricao_municipal"]) && !empty($_POST["estabelecimento_inscricao_municipal"])) &&
            (isset($_POST["tipo_estabelecimento_id"]) && !empty($_POST["tipo_estabelecimento_id"])) &&
            (isset($_POST["endereco_rua"]) && !empty($_POST["endereco_rua"])) &&
            (isset($_POST["endereco_numero"]) && !empty($_POST["endereco_numero"])) &&
            (isset($_POST["endereco_complemento"]) && !empty($_POST["endereco_complemento"])) &&
            (isset($_POST["endereco_bairro"]) && !empty($_POST["endereco_bairro"])) &&
            (isset($_POST["endereco_cep"]) && !empty($_POST["endereco_cep"])) &&
            (isset($_POST["estado_id"]) && !empty($_POST["estado_id"])) &&
            (isset($_POST["cidade_id"]) && !empty($_POST["cidade_id"])) &&
            (isset($_POST["email_descricao"]) && !empty($_POST["email_descricao"])) &&
            (isset($_POST["tipo_telefone_id"]) && !empty($_POST["tipo_telefone_id"])) &&
            (isset($_POST["telefone_ddd"]) && !empty($_POST["telefone_ddd"])) &&
            (isset($_POST["telefone_numero"]) && !empty($_POST["telefone_numero"]))
    	  )
    	{
            $objeto_recebido = array('estabelecimento_cnpj' => $this->input->post("estabelecimento_cnpj"), 
                                     'estabelecimento_razao_social' => $this->input->post("estabelecimento_razao_social"),
                                     'estabelecimento_nome_fantasia' => $this->input->post("estabelecimento_nome_fantasia"),
                                     'estabelecimento_inscrição_estatual' => $this->input->post("estabelecimento_inscrição_estatual"),
                                     'estabelecimento_inscricao_municipal' => $this->input->post("estabelecimento_inscricao_municipal"),
                                     'tipo_estabelecimento_id' => $this->input->post("tipo_estabelecimento_id"),
                                     'endereco_rua' => $this->input->post("endereco_rua"),
                                     'endereco_numero' => $this->input->post("endereco_numero"),
                                     'endereco_complemento' => $this->input->post("endereco_complemento"),
                                     'endereco_bairro' => $this->input->post("endereco_bairro"),
                                     'endereco_cep' => $this->input->post("endereco_cep"),
                                     'estado_id' => $this->input->post("estado_id"),
                                     'cidade_id' => $this->input->post("cidade_id"),
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

                $prox_usuario_id = $this->usuario_model->retornar_id_prox_usuario(2);
                //var_dump($prox_usuario_id);

                $usuario = array(
                    "usuario_id" => $prox_usuario_id,
                    "tipo_usuario_id" => 2,
                    "status_id" => 4,
                    "usuario_senha" => $objeto_recebido["telefone_ddd"].$objeto_recebido["telefone_numero"],
                    "usuario_login" => $objeto_recebido["email_descricao"],
                    "usuario_data_cadastro" => date(),
                    "email_id" => $email_id       
                );

                $this->usuario_model->adicionar_usuario($usuario);

                $estabelecimeto = array(
                    "estabelecimento_cnpj" => $objeto_recebido["estabelecimento_cnpj"],
                    "estabelecimento_razao_social" => $objeto_recebido["estabelecimento_razao_social"],
                    "estabelecimento_nome_fantasia" => $objeto_recebido["estabelecimento_nome_fantasia"],
                    "estabelecimento_inscrição_estatual" => $objeto_recebido["estabelecimento_inscrição_estatual"] 
                    "estabelecimento_inscricao_municipal" => $objeto_recebido["estabelecimento_inscricao_municipal"]    
                    "tipo_estabelecimento_id" => $objeto_recebido["tipo_estabelecimento_id"]           
                );
                $this->load->model("estabelecimento_model");
                $resp = $this->estabelecimento_model->adicionar($estabelecimento);

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
}