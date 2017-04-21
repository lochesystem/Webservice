<?php
class Rest extends CI_Controller{
    
	public function index(){
		$this->user();
    }

    public function lista_usuarios(){
        $this->load->database();
        $this->load->model("usuario_model");
        $usuarios = $this->usuario_model->returnAll();
        $dados = array("usuarios"=>$usuarios);

        echo $this->myjson->my_json_encode($dados);
    }

    public function returnUserById(){
        $this->load->database();
        $this->load->model("Usuario_model");
        $usuarios = $this->Usuario_model->resturnAll();
        $dados = array("usuarios"=>$usuarios);

        echo $this->myjson->my_json_encode($dados);
    }

    public function adicionar_consumidor()
    {
    	if((isset($_POST['usuario_nome']) && !empty($_POST['usuario_nome'])) &&
    		(isset($_POST['usuario_sobrenome']) && !empty($_POST['usuario_sobrenome'])) &&
    		(isset($_POST['usuario_email']) && !empty($_POST['usuario_email'])) &&
    		(isset($_POST['usuario_telefone']) && !empty($_POST['usuario_telefone'])) 
    	  )
    	{
        	$usu = new Usuario_model();

        	$usu->setNickpsn($this->input->post('nickpsn'));
        	$usu->setNome($this->input->post('nome'));
        	$usu->setEmail($this->input->post('email'));
        	$usu->setIdtipousuario($this->input->post('idtipousuario'));
        	$usu->setSenha($this->input->post('senha'));

        	$retorno = $u->adicionarUsuario();
    	} else
    	{
    		echo "Echo parametros inválidos";
    	}
    }


}
