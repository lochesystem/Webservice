<?php
	class Funcionario_model extends CI_Model
	{
		public function adicionar($funcionario)
		{
			$this->load->database();
			$this->db->insert('tb_funcionarios',$funcionario);

			if($this->db->error()["code"] == 0){

				return "SUCESSO";
			}
			else{
				
				return "ERRO";
			}
		}
	}