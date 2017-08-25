<?php
class Consumidor_model extends CI_Model
{
	public function retornar_max_id_produto(){
		$this->load->database();
		$query = $this->db->query('select max(produto_id) from tb_produtos');
		foreach ($query->result_array() as $row)
		{
			return $row["max(usuario_id)"];
		}
	}

	public function salvarProduto($produto)
	{
		$this->db->insert("tb_produtos",$produto);

		if($this->db->error()["code"] == 0){
			return "SUCESSO";
		}else{
			return "ERRO";
		}
	}

	public function retornar_max_id_lote(){
		$this->load->database();
		$query = $this->db->query('select max(lote_id) from tb_lotes');
		foreach ($query->result_array() as $row)
		{
			return $row["max(lote_id)"];
		}
	}

	public function salvarLote($lote)
	{
		$this->db->insert("tb_lotes",$lote);

		if($this->db->error()["code"] == 0){
			return "SUCESSO";
		}else{
			return "ERRO";
		}
	}
}
?>