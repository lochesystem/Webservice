<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Marca extends CI_Controller{
    
    function __construct(){
       parent::__construct();       
       $this->load->library('session');
    }

    public function adicionar(){
        $data = json_decode(file_get_contents('php://input'));
        var_dump($data);
        if((isset($data->marca_descricao) && !empty($data->marca_descricao)) &&
            (isset($data->token) && !empty($data->token))
          )     
        {
            if($data->token == "Sw280717"){

                $marca = array("marca_descricao" => $data->marca_descricao);
                $this->load->model("marca_model");
                $resposta = $this->marca_model->adicionar($marca);

                if($resposta == "SUCESSO")
                {
                   $resp = array(
                        "status" => "true",
                        "descricao" => "Marca cadastrada com sucesso!",
                        "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }else{
                    $resp = array(
                        "status" => "false",
                        "descricao" => "Falha ao cadastrar a marca!",
                        "objeto" => NULL
                        );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }
            }else{
                $resp = array(
                    "status" => "false",
                    "descricao" => "Acesso webservice negado!",
                    "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
        }else{
            $resp = array("status" => "false",
                          "descricao" => "Requisição invalida!",
                          "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }

    public function getMarcaPorId($marca_id){
        $this->load->database();
        $this->load->model("marca_model");
        $marca = $this->marca_model->retornar_marca_id($marca_id);

        if($marca != NULL){
            $resp = array("status" => "true",
                          "descricao" => "Sucesso!",
                          "objeto" => $marca
                         );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados); 
        }else{
            $resp = array("status" => "true",
                           "descricao" => "Marca não encontrada!",
                           "objeto" => $marca
                          );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }        
    }

    public function getMarcas(){
        $this->load->database();
        $this->load->model("marca_model");
        $marcas = $this->marca_model->retornar_marcas();

        $resp = array("status" => "true",
                       "descricao" => "Sucesso!",
                       "objeto" => $marcas
                      );
        $dados = array("response"=>$resp);
        echo $this->myjson->my_json_encode($dados);
    }

public function alterarMarca(){
        $data = json_decode(file_get_contents('php://input'));
        if((isset($data->marca_descricao)) && !empty($data->marca_descricao) &&
           (isset($data->marca_id)) && !empty($data->marca_id) &&
            (isset($data->token) && !empty($data->token)))
        
        {
            if($data->token == "Sw280717"){
                $marca_id = $data->marca_id;
                $marca_descricao = $data->marca_descricao;

                $this->load->model("marca_model");
                $resposta = $this->marca_model->alterar_marca($marca_id,$marca_descricao);

                if($resposta == "SUCESSO")
                {
                   $resp = array(
                        "status" => "true",
                        "descricao" => "Marca alterada com sucesso!",
                        "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }else{
                    $resp = array(
                        "status" => "false",
                        "descricao" => "Falha ao alterar a marca!",
                        "objeto" => NULL
                        );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }
            }else{
                $resp = array(
                    "status" => "false",
                    "descricao" => "Acesso webservice negado!",
                    "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
            }else{
            $resp = array("status" => "false",
                          "descricao" => "Requisição inválida!",
                          "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
            }
    }
}