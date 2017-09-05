<?php

function tokenValido($token){
	$query = $this->db->query("select * from tb_usuarios where usuario_token = $token");
	$row = $query->row_array(); 

	if(isset($row)) 
	{
		return true;
	}else{
		return false;
	}
}

function gerarToken($id){
	return md5($id);
}