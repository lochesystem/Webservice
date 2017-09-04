<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Categoria extends CI_Controller{
    
    function __construct(){
       parent::__construct();       
       $this->load->library('session');
    }

    public function adicionar(){
        $data = json_decode(file_get_contents('php://input'));
        var_dump($data);
        if((isset($data->categoria_descricao) && !empty($data->categoria_descricao)) &&
            (isset($data->token) && !empty($data->token))
          )
        {
            if($data->token == "Sw280717"){

                $categoria = array("categoria_descricao" => $data->categoria_descricao);
                $this->load->model("categoria_model");
                $resposta = $this->categoria_model->adicionar($categoria);

                if($resposta == "SUCESSO")
                {
                   $resp = array(
                        "status" => "true",
                        "descricao" => "Categoria cadastrada com sucesso!",
                        "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }else{
                    $resp = array(
                        "status" => "false",
                        "descricao" => "Falha ao cadastrar a categoria!",
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

    public function getCategoriaPorId($categoria_id){
        $this->load->database();
        $this->load->model("categoria_model");
        $categoria = $this->categoria_model->retornar_categoria_id($categoria_id);

        if($categoria != NULL){
            $resp = array("status" => "true",
                          "descricao" => "Sucesso!",
                          "objeto" => $categoria
                         );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados); 
        }else{
            $resp = array("status" => "true",
                          "descricao" => "Categoria não encontrada!",
                          "objeto" => $categoria
                         );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }        
    }

    public function getCategorias(){
        $this->load->database();
        $this->load->model("categoria_model");
        $categorias = $this->categoria_model->retornar_categorias();

        $resp = array("status" => "true",
                        "descricao" => "Sucesso!",
                        "objeto" => $categorias
                      );
        $dados = array("response"=>$resp);
        echo $this->myjson->my_json_encode($dados);
    }

    public function alterarCategoria(){
        $data = json_decode(file_get_contents('php://input'));
        if((isset($data->categoria_descricao)) && !empty($data->categoria_descricao) &&
           (isset($data->categoria_id)) && !empty($data->categoria_id) &&
            (isset($data->token) && !empty($data->token)))
        
        {
            if($data->token == "Sw280717"){
                $categoria_id = $data->categoria_id;
                $categoria_descricao = $data->categoria_descricao;

                $this->load->model("categoria_model");
                $resposta = $this->categoria_model->alterar_categoria($categoria_id,$categoria_descricao);

                if($resposta == "SUCESSO")
                {
                   $resp = array(
                        "status" => "true",
                        "descricao" => "Categoria alterada com sucesso!",
                        "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }else{
                    $resp = array(
                        "status" => "false",
                        "descricao" => "Falha ao alterar a categoria!",
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