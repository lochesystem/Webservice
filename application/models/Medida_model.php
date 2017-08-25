<?php
class Medida_model extends CI_Model{

	public function retornar_unidades_medidas(){
		$this->load->database();
		$query = $this->db->query('select * from td_unidades_medida');
		return $query->result_array();
	}

}