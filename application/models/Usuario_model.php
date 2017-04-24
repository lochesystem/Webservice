<?php
	class Usuario_model extends CI_Model
	{
		private $usuario_id;
		private $tipo_usuario_id;
		private $status_id;
		private $usuario_login;
		private $usuario_senha;
		private $usuario_data_cadastro;
		private $email_id;
		private $usuario_data_cancelamento;

		public function getUsuario_id()
		{
		    return $this->usuario_id;
		}

		public function setUsuario_id($id)
		{
		    $this->usuario_id = $id;
		    return $this;
		}

		public function getTipo_usuario_id()
		{
		    return $this->tipo_usuario_id;
		}
		public function setTipo_usuario_id($tipo_usuario_id)
		{
		    $this->tipo_usuario_id = $tipo_usuario_id;
		    return $this;
		}

		public function getStatus_id()
		{
		    return $this->status_id;
		}
		public function setStatus_id($status_id)
		{
		    $this->status_id = $status_id;
		    return $this;
		}

		public function getUsuario_senha()
		{
		    return $this->usuario_senha;
		}
		public function setUsuario_senha($usuario_senha)
		{
		    $this->usuario_senha = $usuario_senha;
		    return $this;
		}

		public function getUsuario_login()
		{
		    return $this->usuario_login;
		}
		public function setUsuario_login($usuario_login)
		{
		    $this->usuario_login = $usuario_login;
		    return $this;
		}

		public function getUsuario_data_cadastro()
		{
		    return $this->usuario_data_cadastro;
		}
		public function setUsuario_data_cadastro($usuario_data_cadastro)
		{
		    $this->usuario_data_cadastro = $usuario_data_cadastro;
		    return $this;
		}

		public function getEmail_id()
		{
		    return $this->email_id;
		}
		 
		public function setEmail_id($email_id)
		{
		    $this->email_id = $email_id;
		    return $this;
		}

		public function getUsuario_data_cancelamento()
		{
		    return $this->usuario_data_cancelamento;
		}
		public function setUsuario_data_cancelamento($usuario_data_cancelamento)
		{
		    $this->usuario_data_cancelamento = $usuario_data_cancelamento;
		    return $this;
		}

		public function retornar_todos(){
			return $this->db->get("TB_USUARIOS")->result_array();
		}

		public function retornar_por_id($id)
		{
			if(is_null($id))
			    return false;

			$this->db->where('usuario_id', $id);
			$query = $this->db->get('TB_USUARIOS');

			$row = $query->row_array();
			if (isset($row)) 
			{
				$this->usuario_id = $row['usuario_id'];
				$this->tipo_usuario_id = $row['tipo_usuario_id'];
				$this->status_id = $row['status_id'];
				$this->usuario_senha = $row['usuario_senha'];
				$this->usuario_login = $row['usuario_login'];
				$this->usuario_data_cadastro = $row['usuario_data_cadastro'];
				$this->email_id = $row['email_id'];
				$this->usuario_data_cancelamento = $row['usuario_data_cancelamento'];
			}else
				return null;
		}

		public function retornar_por_login($login)
		{
			//var_dump($login);

			if(is_null($login) )
			    return NULL;

			$this->db->where("usuario_login", $login);
			$query = $this->db->get('TB_USUARIOS');
			$row = $query->row_array();
			if(isset($row)) 
			{
				$usuario = array(
					"usuario_id" => $row['usuario_id'],
					"tipo_usuario_id" => $row['tipo_usuario_id'],
					"status_id" => $row['status_id'],
					"usuario_senha" => $row['usuario_senha'],
					"usuario_login" => $row['usuario_login'],
					"usuario_data_cadastro" => $row['usuario_data_cadastro'],
					"usuario_data_cancelamento" => $row['usuario_data_cancelamento']
				);

				return $usuario;
			}else
				return NULL;
		}
		
		public function retornar_id_prox_usuario($tipo_usuario_id){
			$this->load->database();
			$query = $this->db->query('select fn_proximo_id_usuario('.$tipo_usuario_id.')');

			foreach ($query->result_array() as $row)
			{
				return $row['fn_proximo_id_usuario(1)'];
			}
		}
		
		public function adicionar_usuario($usuario)
		{
			$this->db->insert("TB_USUARIOS",$usuario);

			if($this->db->error()["code"] == 0)
				return "SUCESSO";
			else
				return "ERRO";
		}

		public function retornar_max_id(){
			$this->load->database();
			$query = $this->db->query('select max(usuario_id) from TB_USUARIOS');
			foreach ($query->result_array() as $row)
			{
				return $row["max(usuario_id)"];
			}
		}

		public function retornar_proximo_usuario_id(){
			return 1;
		}

		public function retornar_senha_provissoria(){
			return 123456;
		}
	}
	