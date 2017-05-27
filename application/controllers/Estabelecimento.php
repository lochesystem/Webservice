<?php
class Estabelecimento extends CI_Controller{

	public function adicionar()
    {
        $data = json_decode(file_get_contents('php://input'));

    	if((isset($data->estabelecimento_cnpj) && !empty($data->estabelecimento_cnpj)) &&
            (isset($data->estabelecimento_razao_social) && !empty($data->estabelecimento_razao_social)) &&
    		(isset($data->estabelecimento_nome_fantasia) && !empty($data->estabelecimento_nome_fantasia)) &&
    		(isset($data->estabelecimento_inscrição_estatual) && !empty($data->estabelecimento_inscrição_estatual)) &&
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
            (isset($data->tipo_telefone_id) && !empty($data->tipo_telefone_id)) &&
            (isset($data->telefone_ddd) && !empty($data->telefone_ddd)) &&
            (isset($data->telefone_numero) && !empty($data->telefone_numero))
    	  )
    	{
            $this->load->database();
            $this->db->trans_begin();

            // Inserção endereço
            $query = $this->db->query("
                insert into tb_enderecos(endereco_rua, 
                                         endereco_numero, 
                                         endereco_complemento
                                         endereco_bairro, 
                                         endereco_cep, 
                                         estado_id, 
                                         cidade_id)
                values('.$data->endereco_rua.',
                        '.$data->endereco_numero.',
                        '.$data->endereco_complemento.',
                        '.$data->endereco_bairro.',
                        '.$data->endereco_cep.', 
                        '.'$data->estado_id.', 
                        '.$data->cidade_id.')");
            $row = $query->row();
            if (isset($row))
            {
                var_dump($row);
                echo $row->endereco_id;
            }else{
                var_dump('nop');
                echo 'NOP';
            }

            if ($this->db->trans_status() === FALSE)
            {
                var_dump("erro");
                $this->db->trans_rollback();
            }
            else
            {
                var_dump("OK");
                $this->db->trans_commit();
            }
        }else{
            echo "Erro no recebimento de parametros";
        }
    }

    public function getEstabelecimentos(){
        $this->load->database();
        $this->load->model("estabelecimento_model");
        $estabelecimentos = $this->estabelecimento_model->retornar_estabelecimentos();
        $dados = array("estabelecimentos"=>$estabelecimentos);
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