<?php
class Email_model extends CI_Model{
		public function adicionar($email)
		{
			$this->db->insert("tb_emails",$email);

			if($this->db->error()["code"] == 0){
				return $this->db->insert_id();
			}else{
				return "ERRO";
			}
		}

		public function retornar_todos(){
			$this->load->database();
			$query = $this->db->query('select * from tb_emails ');
			return $query->result_array();
		}
	}
