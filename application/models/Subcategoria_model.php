<?php
class Subcategoria_model extends CI_Model{
		public function adicionar($subcategoria)
		{
			$this->db->insert("tb_sub_categorias",$subcategoria);

			if($this->db->error()["code"] == 0){
				return "SUCESSO";
			}else{
				return "ERRO";
			}
		}

		public function retornar_subcategoria_id($subcategoria_id){
			$this->load->database();
			$query = $this->db->query("select * from tb_sub_categorias where sub_categoria_id = $subcategoria_id");
			$row = $query->row_array();

			if (isset($row)) 
			{
				$subcategoria = array(
					"sub_categoria_id" => $row['sub_categoria_id'],
					"sub_categoria_descricao" => $row['sub_categoria_descricao']
				);

				return $subcategoria;
			}else{
				return null;
			}
		}

		public function retornar_subcategorias(){
			$this->load->database();
			$query = $this->db->query('select * from tb_sub_categorias');
			return $query->result_array();
		}

		public function retornar_subcategoria_categoria($categoria_id){
			$this->load->database();
			$query = $this->db->query("select * from tb_sub_categorias where categoria_id = $categoria_id");
			return $query->result_array();
		}
	}
