<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Produto extends CI_Controller{
	public function adicionarProduto(){
        $data = json_decode(file_get_contents('php://input'));
        var_dump($data);

    	if((isset($data->estabelecimento_id) && !empty($data->estabelecimento_id)) &&
            (isset($data->produto_descricao) && !empty($data->produto_descricao)) &&
    		(isset($data->produto_path_foto) && !empty($data->produto_path_foto)) &&
    		(isset($data->marca_id) && !empty($data->marca_id)) &&
    		(isset($data->categoria_id) && !empty($data->categoria_id)) &&
            (isset($data->quantidade) && !empty($data->quantidade)) &&
            (isset($data->unidade_medida_id) && !empty($data->unidade_medida_id)) &&
            (isset($data->sub_categoria_id) && !empty($data->sub_categoria_id)) 
    	  )
    	{
            $this->load->model("produto_model");
            $produto_id = $this->produto_model->retornar_max_id_produto();
            if($produto_id == null){
                $produto_id = 1;
            }else{
                $produto_id = $produto_id + 1;
            }

            $produto = array(
                'produto_id' => $produto_id,
                'estabelecimento_id' => $data->estabelecimento_id,
                'produto_descricao' => $data->produto_descricao,
                'produto_path_foto' => $data->produto_path_foto,
                'marca_id' => $data->marca_id,
                'categoria_id' => $data->categoria_id,
                'quantidade' => $data->quantidade,
                'unidade_medida_id' => $data->unidade_medida_id,
                'sub_categoria_id' => $data->sub_categoria_id,
            );
            $this->load->model("produto_model");
            $resposta = $this->produto_model->salvarProduto($produto);

            if($resposta == "SUCESSO"){
                $resp = array("status" => "true",
                              "descricao" => "Produto cadastrado com sucesso!",
                              "objeto" => NULL
                );

                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados); 
            }else{
                $resp = array("status" => "false",
                              "descricao" => "Erro ao inserir produto.",
                              "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
        }else{
            $resp = array("status" => "false",
                          "descricao" => "Erro no recebimento de parametros",
                          "objeto" => NULL);
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }

    public function getProdutosPorCategoria($categoria_id){
        $this->load->database();
        $this->load->model("produto_model");
        $produtos = $this->produto_model->retornar_por_categoria($categoria_id);
        if(sizeof($produtos) == 0){
            $obj = array("status" => "false",
                        "descricao" => "Nenhum produto encontrado!",
                        "objeto" => NULL
                     );
        }else{
            $obj = array("status" => "true",
                      "descricao" => "Lista de produtos",
                      "objeto" => $produtos
                     );
        }
        $dados = array("response"=>$obj);
        echo $this->myjson->my_json_encode($dados);
    }

    public function getProdutosPorSubCategoria($sub_categoria_id){
        $this->load->database();
        $this->load->model("produto_model");
        $produtos = $this->produto_model->retornar_estabelecimentos_por_cidade_estado($estado_id, $cidade_id);
        if(sizeof($produtos) == 0){
            $obj = array("status" => "false",
                        "descricao" => "Nenhum produto encontrado!",
                        "objeto" => NULL
                     );
        }else{
            $obj = array("status" => "true",
                      "descricao" => "Lista de produtos",
                      "objeto" => $produtos
                     );
        }
        $dados = array("response"=>$obj);
        echo $this->myjson->my_json_encode($dados);
    }

    public function getUnidadesMedidas(){
        $this->load->database();
        $this->load->model("medida_model");
        $unidades_medidas = $this->medida_model->retornar_unidades_medidas();

        $resp = array("status" => "true",
                       "descricao" => "Sucesso!",
                       "objeto" => $unidades_medidas
                      );
        $dados = array("response"=>$resp);
        echo $this->myjson->my_json_encode($dados);
    }

    public function adicionarLote(){
        $data = json_decode(file_get_contents('php://input'));

        if((isset($data->produto_id) && !empty($data->produto_id)) &&
            (isset($data->estabelecimento_id) && !empty($data->estabelecimento_id)) &&
            (isset($data->lote_data_fabricacao) && !empty($data->lote_data_fabricacao)) &&
            (isset($data->lote_data_vencimento) && !empty($data->lote_data_vencimento)) &&
            (isset($data->lote_preco) && !empty($data->lote_preco)) &&
            (isset($data->lote_obs) && !empty($data->lote_obs)) &&
            (isset($data->lote_quantidade) && !empty($data->lote_quantidade)) &&
            (isset($data->unidade_medida_id) && !empty($data->unidade_medida_id))
          )
        {
            $this->load->model("produto_model");
            $lote_id = $this->produto_model->retornar_max_id_lote();
            if($lote_id == null){
                $lote_id = 1;
            }else{
                $lote_id = $lote_id + 1;
            }

            date_default_timezone_set('America/Sao_Paulo');
            $lote = array(
                'lote_id' => $lote_id,
                'produto_id' => $data->produto_id,
                'estabelecimento_id' => $data->estabelecimento_id,
                'lote_data_fabricacao' => $data->lote_data_fabricacao,
                'lote_data_vencimento' => $data->lote_data_vencimento,
                'lote_data_cadastro' => date('Y-m-d H:i'),
                'lote_preco' => $data->lote_preco,
                'lote_obs' => $data->lote_obs,
                'lote_quantidade' => $data->lote_quantidade,
                'unidade_medida_id' => $data->unidade_medida_id,
            );
            $this->load->model("produto_model");
            $resposta = $this->produto_model->salvarLote($lote);

            if($resposta == "SUCESSO"){
                $resp = array("status" => "true",
                              "descricao" => "Lote cadastrado com sucesso!",
                              "objeto" => NULL
                );

                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados); 
            }else{
                $resp = array("status" => "false",
                              "descricao" => "Erro ao inserir lote.",
                              "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
        }else{
            $resp = array("status" => "false",
                          "descricao" => "Erro no recebimento de parametros",
                          "objeto" => NULL);
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }
}