<?php
	class Consumidor_telefone_model extends CI_Model
	{
		public function adicionar_consumidor_telefone($consumidor_telelfone)
		{
			$this->db->insert('RL_CONSUMIDOR_TELEFONES',$consumidor_telelfone);

			if($this->db->error()["code"] == 0)
				return "SUCESSO";
			else
				return "ERRO";
		}
	}
?>