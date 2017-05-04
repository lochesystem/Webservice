<?php
	class Consumidor_model extends CI_Model
	{
		public function adicionar_consumidor($consumidor)
		{
			$this->db->insert('tb_consumidores',$consumidor);

			if($this->db->error()["code"] == 0)
				return "SUCESSO";
			else
				return "ERRO";
		}
	}
?>
