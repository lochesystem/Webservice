<?php

class Estabelecimento_model extends CI_Model
{
	public function retornar_estabelecimentos(){
		$this->load->database();
		$query = $this->db->query('
			select 
				tb_estabelecimentos.estabelecimento_id, 
			    tb_estabelecimentos.estabelecimento_nome_fantasia, 
			    tb_estabelecimentos.tipo_estabelecimento_id,
			    tb_enderecos.endereco_rua, 
				tb_enderecos.endereco_numero, 
			    tb_enderecos.endereco_bairro, 
			    tb_enderecos.endereco_cep,
			    td_cidades.cidade_descricao,
			    td_estados.estado_sigla,
			    tb_telefones.telefone_ddd,
			    tb_telefones.telefone_numero,
			    tb_emails.email_descricao
			from tb_estabelecimentos 
			inner join tb_enderecos on tb_enderecos.endereco_id = tb_estabelecimentos.endereco_id
			inner join td_cidades on td_cidades.cidade_id = tb_enderecos.cidade_id
			inner join td_estados on td_estados.estado_id = tb_enderecos.estado_id
			inner join rl_estabelecimento_telefones on rl_estabelecimento_telefones.estabelecimento_id = tb_estabelecimentos.estabelecimento_id
			inner join tb_telefones on tb_telefones.telefone_id = rl_estabelecimento_telefones.telefone_id
			inner join rl_estabelecimento_emails on rl_estabelecimento_emails.estabelecimento_id = tb_estabelecimentos.estabelecimento_id
			inner join tb_emails on tb_emails.email_id = rl_estabelecimento_emails.email_id
			where td_cidades.estado_id = tb_enderecos.estado_id
		');

		$estabelecimentos = array();

		foreach ($query->result() as $row)
		{
			$estabelecimento = array('estabelecimento_id' => $row->estabelecimento_id,
									 'estabelecimento_nome_fantasia' => $row->estabelecimento_nome_fantasia,
									 'tipo_estabelecimento_id' => $row->tipo_estabelecimento_id, 
                                 	 'endereco_rua' => $row->endereco_rua,
                                 	 'endereco_numero' => $row->endereco_numero,
                                 	 'endereco_bairro' => $row->endereco_bairro,
                                 	 'endereco_cep' => $row->endereco_cep,
                                 	 'cidade_descricao' => $row->cidade_descricao,
                                 	 'estado_sigla' => $row->estado_sigla,
                                 	 'telefone_ddd' => $row->telefone_ddd,
                                 	 'telefone_numero' => $row->telefone_numero,
                                 	 'email_descricao' => $row->email_descricao
                                 );
        	$estabelecimentos[] = $estabelecimento;
		}
		return $estabelecimentos;	
	}

	public function retornar_estabelecimentos_por_cidade_estado($estado_id, $cidade_id){
		$this->load->database();
		$query = $this->db->query('
			select 
				tb_estabelecimentos.estabelecimento_id, 
			    tb_estabelecimentos.estabelecimento_nome_fantasia, 
			    tb_estabelecimentos.tipo_estabelecimento_id,
			    tb_enderecos.endereco_rua, 
				tb_enderecos.endereco_numero, 
			    tb_enderecos.endereco_bairro, 
			    tb_enderecos.endereco_cep,
			    td_cidades.cidade_descricao,
			    td_estados.estado_sigla,
			    tb_telefones.telefone_ddd,
			    tb_telefones.telefone_numero,
			    tb_emails.email_descricao
			from tb_estabelecimentos 
			inner join tb_enderecos on tb_enderecos.endereco_id = tb_estabelecimentos.endereco_id
			inner join td_cidades on td_cidades.cidade_id = tb_enderecos.cidade_id
			inner join td_estados on td_estados.estado_id = tb_enderecos.estado_id
			inner join rl_estabelecimento_telefones on rl_estabelecimento_telefones.estabelecimento_id = tb_estabelecimentos.estabelecimento_id
			inner join tb_telefones on tb_telefones.telefone_id = rl_estabelecimento_telefones.telefone_id
			inner join rl_estabelecimento_emails on rl_estabelecimento_emails.estabelecimento_id = tb_estabelecimentos.estabelecimento_id
			inner join tb_emails on tb_emails.email_id = rl_estabelecimento_emails.email_id
			where td_cidades.estado_id = tb_enderecos.estado_id
			and td_cidades.estado_id = '.$estado_id.'
			and td_cidades.cidade_id = '.$cidade_id.'
		');

		$estabelecimentos = array();

		foreach ($query->result() as $row)
		{
			$estabelecimento = array('estabelecimento_id' => $row->estabelecimento_id,
									 'estabelecimento_nome_fantasia' => $row->estabelecimento_nome_fantasia,
									 'tipo_estabelecimento_id' => $row->tipo_estabelecimento_id, 
                                 	 'endereco_rua' => $row->endereco_rua,
                                 	 'endereco_numero' => $row->endereco_numero,
                                 	 'endereco_bairro' => $row->endereco_bairro,
                                 	 'endereco_cep' => $row->endereco_cep,
                                 	 'cidade_descricao' => $row->cidade_descricao,
                                 	 'estado_sigla' => $row->estado_sigla,
                                 	 'telefone_ddd' => $row->telefone_ddd,
                                 	 'telefone_numero' => $row->telefone_numero,
                                 	 'email_descricao' => $row->email_descricao
                                 );
        	$estabelecimentos[] = $estabelecimento;
		}
		return $estabelecimentos;	
	}
}

