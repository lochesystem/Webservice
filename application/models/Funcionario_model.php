<?php
	class Funcionario_model extends CI_Model
	{
		public function adicionar_funcionario($funcionario)
		{
			$this->db->insert('tb_funcionarios',$funcionario);

			if($this->db->error()["code"] == 0)
				return "SUCESSO";
			else
				return "ERRO";
		}

		public function retornar_dados_funcionario($usuario_id, $tipo_usuario_id)
		{
			$this->load->database();
			$query = $this->db->query('select u.usuario_id, 
									   		  u.tipo_usuario_id, 
									   		  u.usuario_login, 
									   		  u.usuario_senha, 
									          f.funcionario_nome, 
									          f.funcionario_sobrenome,
									          f.funcionario_cargo,
									          f.funcionario_cpf, 
									          u.usuario_data_cadastro, 
        							          s.status_descricao
 							           from tb_usuarios as u
 									   inner join tb_funcionarios as f on u.usuario_id = f.usuario_id
 									   inner join td_status as s on u.status_id = s.status_id');
			foreach ($query->result_array() as $row)
			{
				var_dump($row);
				return $row;
			}
		}

		public function retornar_max_id(){
			$this->load->database();
			$query = $this->db->query('select max(usuario_id) from tb_usuarios');
			foreach ($query->result_array() as $row)
			{
				return $row["max(usuario_id)"];
			}
		}

	}
?>
