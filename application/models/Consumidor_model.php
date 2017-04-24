<?php
	class Consumidor_model extends CI_Model
	{
		public function adicionar_consumidor($consumidor)
		{
			$this->db->insert('TB_CONSUMIDORES',$consumidor);

			if($this->db->error()["code"] == 0)
				return "SUCESSO";
			else
				return "ERRO";
		}
	}
?>
