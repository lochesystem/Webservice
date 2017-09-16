<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Acesso extends CI_Controller{
    function __construct(){
       parent::__construct();       
       $this->load->library('session');
    }

    public function Autenticar(){
        $acesso = json_decode(file_get_contents('php://input'));
        
        if(isset($acesso->usuario_login) && !empty($acesso->usuario_login) &&
           isset($acesso->usuario_senha) && !empty($acesso->usuario_senha)
        )
        {
            $this->load->model("usuario_model");
            $usuario = $this->usuario_model->retornar_por_login($acesso->usuario_login);

            if($usuario == NULL)
            {
                $resp = array(
                    "status" => "false",
                    "descricao" => "Usuario não encontrato!",
                    "objeto" => $usuario
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
            else
            {
                if($usuario["usuario_senha"] != $acesso->usuario_senha)
                {
                    $resp = array(
                        "status" => "false",
                        "descricao" => "Senha inválida!!!!!!!",
                        "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);    
                }
                else
                {
                    switch ($usuario["status_id"]) {
                        case 1:
                            $resp = array("status" => "false",
                                          "descricao" => "Aguardando aprovação de cadastro!",
                                          "objeto" => $usuario);
                            $dados = array("response"=>$resp);
                            echo $this->myjson->my_json_encode($dados);
                        case 2:
                            $resp = array("status" => "true",
                                          "descricao" => "Usuário autenticado com sucesso!",
                                          "objeto" => $usuario);
                            $dados = array("response"=>$resp);
                            echo $this->myjson->my_json_encode($dados);
                        case 3:
                            $resp = array("status" => "false",
                                          "descricao" => "Usuário inativo!",
                                          "objeto" => $usuario);
                            $dados = array("response"=>$resp);
                            echo $this->myjson->my_json_encode($dados);
                        case 4:
                            $resp = array("status" => "false",
                                          "descricao" => "Usuário bloqueado!",
                                          "objeto" => $usuario);
                            $dados = array("response"=>$resp);
                            echo $this->myjson->my_json_encode($dados);
                    }
                }
            }
        }
        else
        {
            $resp = array(
                    "status" => "false",
                    "descricao" => "Requisição invalida!",
                    "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }        
    }

    public function aprovarCadastro($usuario_token){
        if(isset($usuario_token) && !empty($usuario_token))
        {
            $this->load->helper("token");
            if(validaToken($usuario_token)){
                $this->load->model("usuario_model");
                $resp = $this->usuario_model->alterarStatus($usuario_token, 2);
                if($resp == 1){
                    echo "Cadastro aprovado com sucesso !!!";
                }else{
                    echo "Cadastro já aprovado!";
                }
            }
            else
            {
                $resp = array(
                    "status" => "false",
                    "descricao" => "Acesso webservice negado!",
                    "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
        }
        else
        {
            $resp = array(
                    "status" => "false",
                    "descricao" => "Requisição invalida!",
                    "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }

    public function RedefinirSenha(){
        $data = json_decode(file_get_contents('php://input'));

        if(isset($data->token) && !empty($data->token) &&
           isset($data->nova_senha) && !empty($data->nova_senha))
        {
            if($data->token == "Sw280717"){
                
                $this->load->model("usuario_model");
                $usuario = $this->usuario_model->retornar_por_id_tipo($data->usuario_id, $data->tipo_usuario_id);

                if($usuario != NULL)
                {
                    $x = $this->usuario_model->redefinir_senha($data->usuario_id, $data->tipo_usuario_id, $data->nova_senha);

                    if($x == 1){
                        $resp = array(
                            "status" => "true",
                            "descricao" => "Senha redefinida com sucesso!",
                            "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }else{
                        $resp = array(
                            "status" => "false",
                            "descricao" => "Erro ao redefinir senha!",
                            "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }
                }
                else
                {
                    $resp = array(
                        "status" => "false",
                        "descricao" => "Usuario não encontrato!",
                        "objeto" => $usuario
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }
            }
            else
            {
                $resp = array(
                    "status" => "false",
                    "descricao" => "Acesso webservice negado!",
                    "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
        }else{
            $resp = array(
                    "status" => "false",
                    "descricao" => "Requisição invalida!",
                    "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }

    public function RecuperarSenha(){
        $data = json_decode(file_get_contents('php://input'));

        if(isset($data->email) && !empty($data->email) &&
           isset($data->token) && !empty($data->token))
        {
            if($data->token == "Sw280717"){
                
                $this->load->model("usuario_model");
                $usuario = $this->usuario_model->retornar_por_login($data->email);

                if($usuario != NULL)
                {
                    $this->EnviarEmailRecupSenha($usuario->usuario_login, $usuario->usuario_login, $usuario->usuario_senha);

                    $resp = array(
                        "status" => "true",
                        "descricao" => "Email de recuperação de senha enviado com sucesso!",
                        "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                    
                }
                else
                {
                    $resp = array(
                        "status" => "false",
                        "descricao" => "Usuario não encontrato!",
                        "objeto" => $usuario
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }
            }
            else
            {
                $resp = array(
                    "status" => "false",
                    "descricao" => "Acesso webservice negado!",
                    "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
        }else{
            $resp = array(
                    "status" => "false",
                    "descricao" => "Requisição invalida!",
                    "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }

    public function EnviarEmailRecupSenha($email_destinatario, $usuario_login, $usuario_senha){
        // Carrega a library email
        $this->load->library('email');
         
        //Inicia o processo de configuração para o envio do email
        $config['protocol'] = 'mail'; // define o protocolo utilizado
        $config['wordwrap'] = TRUE; // define se haverá quebra de palavra no texto
        $config['validate'] = TRUE; // define se haverá validação dos endereços de email
        $config['mailtype'] = 'html'; // tipo template

        // Inicializa a library Email, passando os parâmetros de configuração
        $this->email->initialize($config);
        
        // Define remetente e destinatário
        $this->email->from('contato@mlprojetos.com', 'Smarket App'); // Remetente
        $this->email->to($email_destinatario,[$nome_destinatario]); // Destinatário
 
        // Define o assunto do email
        $this->email->subject('Recuperação de Senha.');
 
        // Preencher conteudo do template
        $header = "Suas credenciais de acesso são:";
        $p1 = "Login: $usuario_login";
        $p2 = "Senha: $usuario_senha";
        $footer = "Equipe Smarket App";
        $conteudo = array('header' => $header, 'p1' => $p1, 'p2' => $p2, 'footer' => $footer);

        $dados = array("conteudo" => $conteudo);
        $this->email->message($this->load->view("email-recuperar-senha", $dados, true));

        if($this->email->send()){
            return "Email enviado com sucesso!";
        }else{
            return "Erro no disparo de email!";
        }  
    }
}
