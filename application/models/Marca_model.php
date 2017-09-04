<?php
class Marca_model extends CI_Model{
	public function adicionar($marca)
	{
		$this->db->insert("tb_marcas",$marca);

		if($this->db->error()["code"] == 0){
			return "SUCESSO";
		}else{
			return "ERRO";
		}
	}

	public function retornar_marca_id($marca_id){
		$this->load->database();
		$query = $this->db->query("select * from tb_marcas where marca_id = $marca_id");
		$row = $query->row_array();

		if (isset($row)) 
		{
			$marca = array(
				"marca_id" => $row['marca_id'],
				"marca_descricao" => $row['marca_descricao'],
				"marca_path_img" => $row['marca_path_img']
			);

			return $marca;
		}else{
			return null;
		}
	}

	public function retornar_marcas(){
		$this->load->database();
		$query = $this->db->query('select * from tb_marcas');
		return $query->result_array();
	}

	public function alterar_marca($marca_id,$marca_descricao)
		{
			$this->load->database();
			$query = $this->db->query("update tb_marcas SET marca_descricao = '$marca_descricao' WHERE marca_id = $marca_id");

			if($this->db->error()["code"] == 0){
				return "SUCESSO";
			}else{
				return "ERRO";
			}
		}
}
