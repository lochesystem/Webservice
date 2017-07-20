<?php
	class Usuario_model extends CI_Model
	{
		public function retornar_todos(){
			return $this->db->get("tb_usuarios")->result_array();
		}

		public function retornar_por_id($id)
		{
			if(is_null($id))
			    return false;

			$this->db->where('usuario_id', $id);
			$query = $this->db->get('tb_usuarios');

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
			if(is_null($login) )
			    return NULL;

			$this->db->where("usuario_login", $login);
			$query = $this->db->get('tb_usuarios');
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
				return $row['fn_proximo_id_usuario('.$tipo_usuario_id.')'];
			}
		}
		
		public function adicionar_usuario($usuario)
		{
			$this->load->database();
			$this->db->insert("tb_usuarios",$usuario);

			if($this->db->error()["code"] == 0)
				return "SUCESSO";
			else
				return "ERRO";
		}

		public function retornar_max_id($tipo_usuario_id){
			$this->load->database();
			$query = $this->db->query('select max(usuario_id) from tb_usuarios where tipo_usuario_id = '.$tipo_usuario_id.'');
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
	