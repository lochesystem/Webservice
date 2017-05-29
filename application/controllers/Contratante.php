<?php
class Contratante extends CI_Controller{

    public function adicionar()
    {
        $data = json_decode(file_get_contents('php://input'));

    	if((isset($data->contratante_nome) && !empty($data->contratante_nome)) &&
    		(isset($data->contratante_sobrenome) && !empty($data->contratante_sobrenome)) &&
    		(isset($data->contratante_cpf) && !empty($data->contratante_cpf)) &&
    		(isset($data->contratante_cargo) && !empty($data->contratante_cargo)) &&
            (isset($data->email_descricao) && !empty($data->email_descricao)) &&
            (isset($data->tipo_telefone_id) && !empty($data->tipo_telefone_id)) &&
            (isset($data->telefone_ddd) && !empty($data->telefone_ddd)) &&
            (isset($data->telefone_numero) && !empty($data->telefone_numero))
    	  )
    	{
            $Email = array(
                "email_descricao" => $data->email_descricao
            );
            $this->load->model("email_model");
            $email_id = $this->email_model->adicionar_email($Email);

            if(!empty($email_id)){
                $telefone = array("tipo_telefone_id" => $data->tipo_telefone_id,
                                  "telefone_ddd" => $data->telefone_ddd,
                                  "telefone_numero" => $data->telefone_numero);
                $this->load->model("telefone_model");
                $telefone_id = $this->telefone_model->adicionar_telefone($telefone);

                if(!empty($telefone_id)){
                    $contratante = array(
                        "contratante_nome" => $data->contratante_nome,
                        "contratante_sobrenome" => $data->contratante_sobrenome,
                        "contratante_cpf" => $data->contratante_cpf,
                        "contratante_cargo" => $data->contratante_cargo,
                        "email_id" => $email_id,
                        "telefone_id" => $telefone_id,
                    );
                    $this->load->model("contratante_model");
                    $resp = $this->contratante_model->adicionar($contratante);

                    if($resp == "SUCESSO"){
                        $resp = array("status" => "true",
                                      "descricao" => "Contratante cadastrado com sucesso!",
                                      "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }else{
                        $resp = array("status" => "false",
                                      "descricao" => "Erro ao inserir dados do contratante",
                                      "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }
                }
            }else{
                $resp = array("status" => "false",
                              "descricao" => "Erro ao inserir email",
                              "objeto" => NULL);
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
        }else{
            $resp = array("status" => "false",
                          "descricao" => "POST inválido",
                          "objeto" => NULL);
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }
}