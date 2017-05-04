<?php
	class Cidade_model extends CI_Model
	{
		private $estado_id;
		private $cidade_id;
		private $cidade_descricao;

		public function getEstado_id()
		{
		    return $this->estado_id;
		}
		 
		public function setEstado_id($id)
		{
		    $this->estado_id = $id;
		    return $this;
		}

		public function getCidade_id()
		{
		    return $this->cidade_id;
		}
		 
		public function setCidade_id($id)
		{
		    $this->cidade_id = $id;
		    return $this;
		}

		public function getCidade_descricao()
		{
		    return $this->cidade_descricao;
		}
		 
		public function setCidade_descricao($descricao)
		{
		    $this->cidade_descricao = $descricao;
		    return $this;
		}


		public function retornar_por_estado_id($estado_id)
		{			
			if(is_null($estado_id))
			    return false;

			$this->db->order_by("cidade_descricao");
			$this->db->where('estado_id', $estado_id);
			$query = $this->db->get('td_cidades');

			$cidades = array();

			foreach ($query->result() as $row)
			{
	        	$cid = new Cidade_model();
	        	$cid->estado_id = $row->estado_id;
	        	$cid->cidade_id = $row->cidade_id;
	        	$cid->cidade_descricao = $row->cidade_descricao;
	        	
	        	$cidades[] = $cid;
			}

			return $cidades;	
		}
	}