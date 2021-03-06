<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Estabelecimento extends CI_Controller{
	public function adicionar(){
        $data = json_decode(file_get_contents('php://input'));

    	if((isset($data->estabelecimento_cnpj) && !empty($data->estabelecimento_cnpj)) &&
            (isset($data->estabelecimento_razao_social) && !empty($data->estabelecimento_razao_social)) &&
    		(isset($data->estabelecimento_nome_fantasia) && !empty($data->estabelecimento_nome_fantasia)) &&
    		(isset($data->estabelecimento_inscricao_estadual) && !empty($data->estabelecimento_inscricao_estadual)) &&
    		(isset($data->estabelecimento_inscricao_municipal) && !empty($data->estabelecimento_inscricao_municipal)) &&
            (isset($data->tipo_estabelecimento_id) && !empty($data->tipo_estabelecimento_id)) &&
            (isset($data->endereco_rua) && !empty($data->endereco_rua)) &&
            (isset($data->endereco_numero) && !empty($data->endereco_numero)) &&
            (isset($data->endereco_complemento) && !empty($data->endereco_complemento)) &&
            (isset($data->endereco_bairro) && !empty($data->endereco_bairro)) &&
            (isset($data->endereco_cep) && !empty($data->endereco_cep)) &&
            (isset($data->estado_id) && !empty($data->estado_id)) &&
            (isset($data->cidade_id) && !empty($data->cidade_id)) &&
            (isset($data->email_descricao) && !empty($data->email_descricao)) &&
            (isset($data->email_setor) && !empty($data->email_setor)) &&
            (isset($data->tipo_telefone_id) && !empty($data->tipo_telefone_id)) &&
            (isset($data->telefone_ddd) && !empty($data->telefone_ddd)) &&
            (isset($data->telefone_numero) && !empty($data->telefone_numero)) &&
            (isset($data->telefone_setor) && !empty($data->telefone_setor)) 
    	  )
    	{
           $Endereco = array(
                'endereco_rua' => $data->endereco_rua,
                'endereco_numero' => $data->endereco_numero,
                'endereco_complemento' => $data->endereco_complemento,
                'endereco_bairro' => $data->endereco_bairro,
                'endereco_cep' => $data->endereco_cep,
                'estado_id' => $data->estado_id,
                'cidade_id' => $data->cidade_id,
            );
            $this->load->model("endereco_model");
            $endereco_id = $this->endereco_model->adicionar($Endereco);

            if(!empty($endereco_id)){
                $estabelecimento = array(
                    "estabelecimento_cnpj" => $data->estabelecimento_cnpj,
                    "endereco_id" => $endereco_id,
                    "estabelecimento_razao_social" => $data->estabelecimento_razao_social,
                    "estabelecimento_nome_fantasia" => $data->estabelecimento_nome_fantasia,
                    "estabelecimento_inscricao_estadual" => $data->estabelecimento_inscricao_estadual,
                    "estabelecimento_inscricao_municipal" => $data->estabelecimento_inscricao_municipal,  
                    "tipo_estabelecimento_id" => $data->tipo_estabelecimento_id          
                );
                $this->load->model("estabelecimento_model");
                $estabelecimento_id = $this->estabelecimento_model->adicionar($estabelecimento);

                $Email = array(
                    "email_descricao" => $data->email_descricao,    
                );
                $this->load->model("email_model");
                $email_id = $this->email_model->adicionar($Email);

                $estabelecimento_email = array(
                    "email_id" => $email_id,
                    "estabelecimento_id" => $estabelecimento_id,
                    "estabelecimento_cnpj" => $data->estabelecimento_cnpj,
                    "estabelecimento_email_setor" => $data->email_setor,
                );
                $this->load->model("estabelecimento_email_model");
                $resp = $this->estabelecimento_email_model->adicionar($estabelecimento_email);

                $telefone = array(
                    "tipo_telefone_id" => $data->tipo_telefone_id,
                    "telefone_ddd" => $data->telefone_ddd,
                    "telefone_numero" => $data->telefone_numero    
                );
                $this->load->model("telefone_model");
                $telefone_id = $this->telefone_model->adicionar_telefone($telefone);

                $estabelecimento_telefone = array(  
                    "estabelecimento_id" => $estabelecimento_id,
                    "estabelecimento_cnpj" => $data->estabelecimento_cnpj,
                    "telefone_id" => $telefone_id,
                    "estabelecimento_telefone_setor" => $data->telefone_setor,
                );
                $this->load->model("Estabelecimento_telefone_model");
                $resposta = $this->Estabelecimento_telefone_model->adicionar($estabelecimento_telefone);

                if($resposta == "SUCESSO"){
                     $resp = array("status" => "true",
                                  "descricao" => "Estabelecimento Cadastrado com Sucesso!",
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
                                  "descricao" => "Erro ao inserir consumidor_endereco",
                                  "objeto" => NULL);
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

    public function getEstabelecimentos(){
        $this->load->database();
        $this->load->model("estabelecimento_model");
        $estabelecimentos = $this->estabelecimento_model->retornar_estabelecimentos();

        if(sizeof($estabelecimentos) == 0){
            $obj = array("status" => "false",
                         "descricao" => "Nenhum estabelecimentos encontrado!",
                         "objeto" => NULL
                        );
        }else{
            $obj = array("status" => "true",
                         "descricao" => "Lista de estabelecimentos",
                         "objeto" => $estabelecimentos
                        );
        }
        $dados = array("response"=>$obj);
        echo $this->myjson->my_json_encode($dados);
    } 

    public function getEstabelecimentosPorCidadeEstado($estado_id, $cidade_id){
        $this->load->database();
        $this->load->model("estabelecimento_model");
        $estabelecimentos = $this->estabelecimento_model->retornar_estabelecimentos_por_cidade_estado($estado_id, $cidade_id);
        if(sizeof($estabelecimentos) == 0){
            $obj = array("status" => "false",
                        "descricao" => "Nenhum estabelecimentos encontrado!",
                        "objeto" => NULL
                     );
        }else{
            $obj = array("status" => "true",
                      "descricao" => "Lista de estabelecimentos",
                      "objeto" => $estabelecimentos
                     );
        }
        $dados = array("response"=>$obj);
        echo $this->myjson->my_json_encode($dados);
    }
}