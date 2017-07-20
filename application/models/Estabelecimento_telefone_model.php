<?php

class Estabelecimento_telefone_model extends CI_Model
{
	public function adicionar($estabelecimento_telefone)
	{
		$this->db->insert("rl_estabelecimento_telefones",$estabelecimento_telefone);

		if($this->db->error()["code"] == 0){
			return "SUCESSO";
		}else{
			return "ERRO";
		}
	}
}