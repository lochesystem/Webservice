<?php

	class Telefone_model extends CI_Model
	{

		private $telefone_id;
		private $tipo_telefone_id;
		private $telefone_ddd;
		private $telefone_numero;

		public function getTelefone_id()
		{
		    return $this->telefone_id;
		}
		public function setTelefone_id($telefone_id)
		{
		    $this->telefone_id = $telefone_id;
		    return $this;
		}

		public function getTipo_telefone_id()
		{
		    return $this->tipo_telefone_id;
		}
		public function setTipo_telefone_id($tipo_telefone_id)
		{
		    $this->tipo_telefone_id = $tipo_telefone_id;
		    return $this;
		}

		public function getTelefone_ddd()
		{
		    return $this->telefone_ddd;
		}
		public function setTelefone_ddd($telefone_ddd)
		{
		    $this->telefone_ddd = $telefone_ddd;
		    return $this;
		}

		public function getTelefone_numero()
		{
		    return $this->telefone_numero;
		}
		public function setTelefone_numero($telefone_numero)
		{
		    $this->telefone_numero = $telefone_numero;
		    return $this;
		}

		public function adicionar_telefone($telefone)
		{
			$this->db->insert('tb_telefones',$telefone);

			if($this->db->error()["code"] == 0){
				return $this->db->insert_id();
			}else{
				return "ERRO";
			}
		}
	}