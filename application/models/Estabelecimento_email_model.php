<?php

class Estabelecimento_email_model extends CI_Model
{
	public function adicionar($estabelecimento_email)
	{
		$this->db->insert("rl_estabelecimento_emails",$estabelecimento_email);

		if($this->db->error()["code"] == 0){
			return $this->db->insert_id();
		}else{
			return "ERRO";
		}
	}
}