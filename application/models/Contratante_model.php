
<?php

	class Contratante_model extends CI_Model
	{
		public function adicionar($contratante){
			$this->db->insert('tb_contratantes',$contratante);

			if($this->db->error()["code"] == 0){
				return "SUCESSO";
			}else{
				return "ERRO";
			}
		}

		public function retornar_todos(){
			$this->load->database();
			$query = $this->db->query('
				select 
					tb_contratantes.contratante_id, 
					tb_contratantes.contratante_nome,
				    tb_contratantes.contratante_sobrenome,
				    tb_contratantes.contratante_CPF,
				    tb_contratantes.contratante_cargo,
				    tb_emails.email_descricao,
				    tb_telefones.tipo_telefone_id,
				    tb_telefones.telefone_ddd,
				    tb_telefones.telefone_numero
				from tb_contratantes 
				inner join tb_emails on tb_emails.email_id = tb_contratantes.email_id
				inner join tb_telefones on tb_telefones.telefone_id = tb_contratantes.telefone_id
			');

			$contratantes = array();
			foreach ($query->result() as $row)
			{
				$contratante = array('contratante_id' => $row->contratante_id,
									 'contratante_nome' => $row->contratante_nome,
									 'contratante_sobrenome' => $row->contratante_sobrenome, 
	                                 'contratante_CPF' => $row->contratante_CPF,
	                                 'contratante_cargo' => $row->contratante_cargo,
	                                 'email_descricao' => $row->email_descricao,
	                                 'tipo_telefone_id' => $row->tipo_telefone_id,
	                                 'telefone_ddd' => $row->telefone_ddd,
	                                 'telefone_numero' => $row->telefone_numero
                                 	);
	        	$contratantes[] = $contratante;
			}
			return $contratantes;	
		}
	}
