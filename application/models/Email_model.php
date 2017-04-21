<?php

	class Email_model extends CI_Model
	{
		private $email_id;
		private $email_descricao;

		public function getEmail_id()
		{
		    return $this->email_id;
		}

		public function setEmail_id($email_id)
		{
		    $this->email_id = $email_id;
		    return $this;
		}

		public function getEmail_descricao()
		{
		    return $this->email_id;
		}

		public function setEmail_descricao($email_descricao)
		{
		    $this->email_descricao = $email_descricao;
		    return $this;
		}

		public function adicionar_email($email)
		{
			$this->db->insert("TB_EMAILS",$email);

			if($this->db->error()["code"] == 0){
				$teste = array(
					"id" => $this->db->insert_id()
				);
				var_dump($teste);
				return $teste;
			}else{
				return "ERRO";
			}
		}

		public function retornar_todos(){
			return $this->db->get("TB_EMAILS")->result_array();
		}
	}
