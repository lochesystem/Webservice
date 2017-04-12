<?php
class Rest extends CI_Controller{

	public function index(){

		$this->load->database();
		$this->load->model("mercados_model"); // error
		$mercados = $this->mercados_model->buscaTodos();

		$dados = array("mercados" => $mercados);

		//$this->load->view("rest/index.php", $dados);
		//$this->load->view("rest/index.php", $dados);

		echo $this->myjson->my_json_encode($dados);
    }
}
