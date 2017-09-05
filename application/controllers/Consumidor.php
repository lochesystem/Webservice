<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Consumidor extends CI_Controller{
    function __construct(){
       parent::__construct();       
       $this->load->library('session');
    }

    public function adicionar(){
        $data = json_decode(file_get_contents('php://input'));

        if((isset($data->tipo_usuario_id) && !empty($data->tipo_usuario_id)) &&
            (isset($data->consumidor_nome) && !empty($data->consumidor_nome)) &&
            (isset($data->consumidor_sobrenome) && !empty($data->consumidor_sobrenome)) &&
            (isset($data->email_descricao) && !empty($data->email_descricao)) &&
            (isset($data->tipo_telefone_id) && !empty($data->tipo_telefone_id)) &&
            (isset($data->telefone_ddd) && !empty($data->telefone_ddd)) &&
            (isset($data->telefone_numero) && !empty($data->telefone_numero)) &&
            (isset($data->usuario_senha) && !empty($data->usuario_senha)))
        {
            //teste helper currency
            //$this->load->helper("currency");
            //$teste = numeroEmReais(200);
            //var_dump($teste);

            //Adicionando Email
            $Email = array(
                "email_descricao" => $data->email_descricao
            );
            $this->load->model("email_model");
            $email_id = $this->email_model->adicionar($Email);

            if(!empty($email_id)){
                $this->load->model("usuario_model");

                $prox_usuario_id = $this->usuario_model->retornar_id_prox_usuario($data->tipo_usuario_id);

                //Helper token
                $this->load->helper("token");
                $token = gerarToken($prox_usuario_id);
                var_dump($token);

                //Adicionando usuario
                date_default_timezone_set('America/Sao_Paulo');
                $usuario = array("usuario_id" => $prox_usuario_id,
                                 "tipo_usuario_id" => $data->tipo_usuario_id,
                                 "status_id" => 1, // Status Ativo
                                 "usuario_senha" => $data->usuario_senha,
                                 "usuario_login" => $data->email_descricao,
                                 "usuario_token" => $token,
                                 "usuario_data_cadastro" => date('Y-m-d H:i'),
                                 "email_id" => $email_id);
                $retorno = $this->usuario_model->adicionar_usuario($usuario);
                $usuario_id = $this->usuario_model->retornar_max_id($data->tipo_usuario_id);

                if(!empty($usuario_id))
                {
                    //Adicionando consumidor
                    $consumidor = array("usuario_id" => $usuario_id,
                                        "tipo_usuario_id" => $data->tipo_usuario_id,
                                        "consumidor_nome" => $data->consumidor_nome,
                                        "consumidor_sobrenome" => $data->consumidor_sobrenome);
                    $this->load->model("consumidor_model");
                    $resp = $this->consumidor_model->adicionar_consumidor($consumidor);

                    //Adicionando telefone
                    $telefone = array("tipo_telefone_id" => $data->tipo_telefone_id,
                                      "telefone_ddd" => $data->telefone_ddd,
                                      "telefone_numero" => $data->telefone_numero);
                    $this->load->model("telefone_model");
                    $telefone_id = $this->telefone_model->adicionar_telefone($telefone);

                    //Adicionando consumidor_telefone
                    $consumidor_telefone = array("usuario_id" => $usuario_id,
                                                 "tipo_usuario_id" => $data->tipo_usuario_id,
                                                 "telefone_id" => $telefone_id);
                    $this->load->model("consumidor_telefone_model");
                    $resposta = $this->consumidor_telefone_model->adicionar_consumidor_telefone($consumidor_telefone);
                    
                    if($resposta == "SUCESSO")
                    {
                        //Helper email
                        $this->load->helper("email");
                        //$envio = enviarEmailConfirmacaoCadastro($data->email_descricao,$data->consumidor_nome,$usuario_id,$data->tipo_usuario_id);

                        $resp = array("status" => "true",
                                      "descricao" => "Consumidor cadastrado com sucesso!",
                                      "objeto" => $token
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }
                    else
                    {
                        $resp = array("status" => "false",
                                      "descricao" => "Erro ao inserir consumidor_telefone",
                                      "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }
                }
                else
                {
                    $resp = array("status" => "false",
                                  "descricao" => "Erro ao retornar usuario_id",
                                  "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }
            }
            else
            {
                $resp = array("status" => "false",
                              "descricao" => "Erro ao retornar email_id",
                              "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
        }
        else
        {
            $resp = array("status" => "false",
                          "descricao" => "Requisição invalida!",
                          "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }

    public function getConsumidor($usuario_id, $tipo_usuario_id, $usuario_token){
        if((isset($usuario_id) && !empty($usuario_id)) &&
            (isset($tipo_usuario_id) && !empty($tipo_usuario_id)) &&
            (isset($usuario_token) && !empty($usuario_token)))
        {
            $this->load->helper("token");
            if(validaToken($usuario_token)){
                $this->load->database();
                $this->load->model("consumidor_model");
                $consumidor = $this->consumidor_model->retornar_consumidor($usuario_id, $tipo_usuario_id);
            
                if(sizeof($consumidor) == 0){
                    $obj = array("status" => "false",
                                 "descricao" => "Nenhum consumidor encontrado.",
                                 "objeto" => NULL
                                );
                }else{
                    $obj = array("status" => "true",
                                 "descricao" => "Dados do consumidor.",
                                 "objeto" => $consumidores
                                );
                }

                $dados = array("response"=>$obj);
                echo $this->myjson->my_json_encode($dados);
            }else{
                $obj = array("status" => "false",
                                 "descricao" => "Acesso invalido.",
                                 "objeto" => NULL
                            );

                $dados = array("response"=>$obj);
                echo $this->myjson->my_json_encode($dados);
            }
        }else{
            $obj = array("status" => "false",
                             "descricao" => "Requisição invalida.",
                             "objeto" => NULL
                        );

            $dados = array("response"=>$obj);
            echo $this->myjson->my_json_encode($dados);
        }
    }

    public function getConsumidores($usuario_token){
        if((isset($usuario_token) && !empty($usuario_token)))
        {
            $this->load->helper("token");
            if(validaToken($usuario_token)){
                $this->load->database();
                $this->load->model("consumidor_model");
                $consumidores = $this->consumidor_model->retornar_consumidores();

                if(sizeof($consumidores) == 0){
                    $obj = array("status" => "false",
                                 "descricao" => "Nenhum consumidor encontrado!",
                                 "objeto" => NULL);
                }else{
                    $obj = array("status" => "true",
                                 "descricao" => "Lista de consumidores",
                                 "objeto" => $consumidores);
                }

                $dados = array("response"=>$obj);
                echo $this->myjson->my_json_encode($dados);
            }else{
                $obj = array("status" => "false",
                                 "descricao" => "Acesso invalida.",
                                 "objeto" => NULL
                            );

                $dados = array("response"=>$obj);
                echo $this->myjson->my_json_encode($dados);
            }
        }else{
            $obj = array("status" => "false",
                             "descricao" => "Acesso invalida.",
                             "objeto" => NULL
                        );

            $dados = array("response"=>$obj);
            echo $this->myjson->my_json_encode($dados);
        }
    }
}