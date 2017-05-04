
<?php
class Localidade extends CI_Controller{
    
    public function getEstados(){
        $this->load->database();
        $this->load->model("estado_model");
        $estados = $this->estado_model->retornar_todos();

        $dados = array("estados"=>$estados);
        echo $this->myjson->my_json_encode($dados);
    }

    public function getCidades($estado_id){
        $this->load->database();
        $this->load->model("cidade_model");
        $cidades = $this->cidade_model->retornar_por_estado_id($estado_id);
        $cont = 0;
    	foreach($cidades as $cit)
		{
			$cidades[$cont] = array("estado_id" => $cit->getEstado_id(),
				                    "cidade_id" => $cit->getCidade_id(),
				                    "cidade_descricao" => $cit->getCidade_descricao());			
			$cont += 1;
		}

		$dados = array("cidades"=>$cidades);
        echo $this->myjson->my_json_encode($dados);
    }
}