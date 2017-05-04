<?php
	class Estado_model extends CI_Model
	{
		public function retornar_todos(){
			return $this->db->get("td_estados")->result_array();
		}
	}
	