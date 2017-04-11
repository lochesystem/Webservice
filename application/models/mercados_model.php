<?php

class Mercados_model extends CI_Model{
	public function buscaTodos(){
		return $this->db->get("TB_MER_MERCADOS")->result_array();
	}
}