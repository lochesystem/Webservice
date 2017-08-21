<?php
class Categoria_model extends CI_Model{
		public function adicionar($categoria)
		{
			$this->db->insert("tb_categorias",$categoria);

			if($this->db->error()["code"] == 0){
				return "SUCESSO";
			}else{
				return "ERRO";
			}
		}

		public function retornar_categoria_id($categoria_id){
			$this->load->database();
			$query = $this->db->query("select * from tb_categorias where categoria_id = $categoria_id");
			$row = $query->row_array();

			if (isset($row)) 
			{
				$categoria = array(
					"categoria_id" => $row['categoria_id'],
					"categoria_descricao" => $row['categoria_descricao'],
					"categoria_path_img" => $row['categoria_path_img']
				);

				return $categoria;
			}else{
				return null;
			}
		}

		public function retornar_categorias(){
			$this->load->database();
			$query = $this->db->query('select * from tb_categorias');
			return $query->result_array();
		}
	}
