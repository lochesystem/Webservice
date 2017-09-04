<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Subcategoria extends CI_Controller{
    
    function __construct(){
       parent::__construct();       
       $this->load->library('session');
    }

    public function adicionar(){
        $data = json_decode(file_get_contents('php://input'));
        var_dump($data);
        if((isset($data->sub_categoria_descricao) && !empty($data->sub_categoria_descricao)) &&
            (isset($data->categoria_id) && !empty($data->categoria_id)) &&
            (isset($data->token) && !empty($data->token))
          )
        {
            if($data->token == "Sw280717"){

                $subcategoria = array("sub_categoria_descricao" => $data->sub_categoria_descricao,
                                      "categoria_id" => $data->categoria_id);
                $this->load->model("subcategoria_model");
                $resposta = $this->subcategoria_model->adicionar($subcategoria);

                if($resposta == "SUCESSO")
                {
                   $resp = array(
                        "status" => "true",
                        "descricao" => "Subcategoria cadastrada com sucesso!",
                        "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }else{
                    $resp = array(
                        "status" => "false",
                        "descricao" => "Falha ao cadastrar subcategoria!",
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

    public function getSubcategoriaPorId($subcategoria_id){
        $this->load->database();
        $this->load->model("subcategoria_model");
        $subcategoria = $this->subcategoria_model->retornar_subcategoria_id($subcategoria_id);
        if($subcategoria != NULL){
            $resp = array(
                            "status" => "true",
                            "descricao" => "Sucesso!",
                            "objeto" => $subcategoria
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados); 
        }else{
             $resp = array(
                            "status" => "true",
                            "descricao" => "Subcategoria não encontrada!",
                            "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
        }        
    }

    public function getSubcategorias(){
        $this->load->database();
        $this->load->model("subcategoria_model");
        $subcategorias = $this->subcategoria_model->retornar_subcategorias();

         $resp = array(
                        "status" => "true",
                        "descricao" => "Sucesso!",
                        "objeto" => $subcategorias
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
    }

    public function getSubcategoriaPorCategoria($categoria_id){
        $this->load->database();
        $this->load->model("subcategoria_model");
        $subcategoria = $this->subcategoria_model->retornar_subcategoria_categoria($categoria_id);
        if($subcategoria != NULL){

            $resp = array("status" => "true",
                        "descrição" => "Sucesso!",
                        "objeto" => $subcategoria
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }else{
            $resp = array("status" => "true",
                            "descrição" => "Não existem Subcategorias dessa Categoria!",
                            "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
        }
    }

}