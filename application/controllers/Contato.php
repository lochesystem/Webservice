
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Contato extends CI_Controller{
    public function getTiposTelefone(){
        $this->load->database();
        $this->load->model("telefone_model");
        $tiposTelefone = $this->telefone_model->retornar_tipos_telefone();

        $dados = array("tiposTelefone"=>$tiposTelefone);
        echo $this->myjson->my_json_encode($dados);
    }
}