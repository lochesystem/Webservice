
<?php

	class Contratante_model extends CI_Model
	{
		public function adicionar($contratante)
		{
			$this->db->insert('tb_contratantes',$contratante);

			if($this->db->error()["code"] == 0){
				return "SUCESSO";
			}else{
				return "ERRO";
			}
		}
	}
