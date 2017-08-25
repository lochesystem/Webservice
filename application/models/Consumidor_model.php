<?php
	class Consumidor_model extends CI_Model
	{
		public function retornar_max_id(){
			$this->load->database();
			$query = $this->db->query('select max(usuario_id) from tb_usuarios');
			foreach ($query->result_array() as $row)
			{
				return $row["max(usuario_id)"];
			}
		}

		public function adicionar_consumidor($consumidor)
		{
			$this->db->insert('tb_consumidores',$consumidor);

			if($this->db->error()["code"] == 0)
				return "SUCESSO";
			else
				return "ERRO";
		}

		public function retornar_consumidor($usuario_id, $tipo_usuario_id)
		{
			$this->load->database();
			$query = $this->db->query("select u.usuario_id, 
									   		  u.tipo_usuario_id, 
									   		  u.usuario_login, 
									   		  u.usuario_senha, 
									          c.consumidor_nome, 
									          c.consumidor_sobrenome, 
									          u.usuario_data_cadastro, 
        							          s.status_descricao
 							           from tb_usuarios as u
 									   inner join tb_consumidores as c on u.usuario_id = c.usuario_id
 									   inner join td_status as s on u.status_id = s.status_id
 									   where u.usuario_id = '.$usuario_id.'
 									   and u.tipo_usuario_id = '.$tipo_usuario_id.'");
			foreach($query->result_array() as $row)
			{
				return $row;
			}
		}

		public function retornar_consumidores(){
			$this->load->database();
			$query = $this->db->query('select u.usuario_id, 
						   	  				  u.tipo_usuario_id, 
									   	      u.usuario_login, 
									   	  	  u.usuario_senha, 
									      	  c.consumidor_nome, 
									      	  c.consumidor_sobrenome, 
									          u.usuario_data_cadastro, 
        							          s.status_descricao
						           	   from tb_usuarios as u
								       inner join tb_consumidores as c on u.usuario_id = c.usuario_id
								       inner join td_status as s on u.status_id = s.status_id');

			$consumidores = array();

			foreach($query->result() as $row)
			{
				$consumidores = array('usuario_id' => $row->usuario_id,
									  'tipo_usuario_id' => $row->tipo_usuario_id,
	                                  'usuario_login' => $row->usuario_login,
	                                  'usuario_senha' => $row->usuario_senha,
	                                  'consumidor_nome' => $row->consumidor_nome,
	                                  'consumidor_sobrenome' => $row->consumidor_sobrenome,
	                                  'usuario_data_cadastro' => $row->usuario_data_cadastro,
	                                  'status_descricao' => $row->status_descricao,
	                                 );
	        	$consumidores[] = $consumidores;
			}
			return $consumidores;	
		}

	}
?>
