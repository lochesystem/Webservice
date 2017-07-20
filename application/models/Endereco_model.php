<?php
class Endereco_model extends CI_Model{
		public function adicionar($endereco)
		{
			$this->db->insert("tb_enderecos",$endereco);

			if($this->db->error()["code"] == 0){
				return $this->db->insert_id();
			}else{
				return "ERRO";
			}
		}

		public function retornar_todos(){
			$this->load->database();
			$query = $this->db->query('select * from tb_endereco ');
			return $query->result_array();
		}
	}
