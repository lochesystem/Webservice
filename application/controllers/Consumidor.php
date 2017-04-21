<?php
class Consumidor extends CI_Controller{
    
    public function lista_usuarios(){
        $this->load->database();
        $this->load->model("usuario_model");
        $usuarios = $this->usuario_model->retornar_todos();

        $dados = array("usuarios"=>$usuarios);
        echo $this->myjson->my_json_encode($dados);
    }

    public function lista_emails(){
        $this->load->database();
        $this->load->model("email_model");
        $emails = $this->email_model->retornar_todos();

        $dados = array("emails"=>$emails);
        echo $this->myjson->my_json_encode($dados);
    }

    public function adicionar_consumidor()
    {
    	if((isset($_POST["tipo_usuario_id"]) && !empty($_POST["tipo_usuario_id"])) &&
            (isset($_POST["usuario_nome"]) && !empty($_POST["usuario_nome"])) &&
    		(isset($_POST["usuario_sobrenome"]) && !empty($_POST["usuario_sobrenome"])) &&
    		(isset($_POST["usuario_email"]) && !empty($_POST["usuario_email"])) &&
    		(isset($_POST["usuario_telefone"]) && !empty($_POST["usuario_telefone"])) 
    	  )
    	{
            $Email = array(
                "email_descricao" => $this->input->post("usuario_email")
            );

            $this->load->model("email_model");
            $retorno1 = $this->email_model->adicionar_email($Email);
            var_dump($retorno1);
            if(!empty($retorno1)){

                $usuario = array(
                    "tipo_usuario_id" => $this->input->post("tipo_usuario_id"),
                    "usuario_id" => 1,
                    "status_id" => 4,
                    "usuario_login" => $this->input->post("usuario_email"),
                    "usuario_senha" => "teste123",
                    "email_id" => $retorno1       
                );

                $this->load->model("usuario_model");
                $retorno2 = $this->usuario_model->adicionar_usuario($usuario);

                if($retorno2 == "SUCESSO"){
                    $dados = array("data"=>$retorno2);
                    echo $this->myjson->my_json_encode($dados);
                }else{
                    $dados = array("data"=>$retorno2);
                    echo $this->myjson->my_json_encode($dados);
                }
            }
    	} else
    	{
    		$erroPost = "Parametros inválidos";
            $dados = array("data"=>$erroPost);
            echo $this->myjson->my_json_encode($dados);
    	}
    }
}
