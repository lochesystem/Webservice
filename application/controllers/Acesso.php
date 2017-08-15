<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Acesso extends CI_Controller{

    public function Autenticar(){
        $acesso = json_decode(file_get_contents('php://input'));

        if(
            isset($acesso->usuario_login) && !empty($acesso->usuario_login) &&
            isset($acesso->usuario_senha) && !empty($acesso->usuario_senha) &&
            isset($acesso->token) && !empty($acesso->token)
        )
        {
            if($acesso->token == "Sw280717"){
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
                            "descricao" => "Senha inválida!",
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

    public function aprovarCadastro($usuario_id, $tipo_usuario_id, $token){
        if(
            isset($usuario_id) && !empty($usuario_id) &&
            isset($tipo_usuario_id) && !empty($tipo_usuario_id) &&
            isset($token) && !empty($token)
        )
        {
            if($token == "Sw280717"){
                $this->load->model("usuario_model");
                $resp = $this->usuario_model->alterarStatus($usuario_id, $tipo_usuario_id, 2);
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

    /*
    public function RecuperarSenha(){
        $data = json_decode(file_get_contents('php://input'));

        if(isset($data->usuario_login) && !empty($data->usuario_login) &&
           isset($data->token) && !empty($data->token))
        {
            if($data->token == "Sw280717"){
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
    }
    */

    public function RedefinirSenha(){
        $data = json_decode(file_get_contents('php://input'));

        if(isset($data->usuario_id) && !empty($data->usuario_id) &&
           isset($data->tipo_usuario_id) && !empty($data->tipo_usuario_id) &&
           isset($data->nova_senha) && !empty($data->nova_senha) &&
           isset($data->token) && !empty($data->token))
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

    public function testeEnvioEmail(){
        $this->load->helper("email");
        $enviou = $this->email->EnviarEmail('murilo.lfs@gmail.com','Murilo Lourenço',1,1);

        $resp = array("status" => "true",
                      "descricao" => "teste",
                      "objeto" => $enviou);

        $dados = array("response"=>$resp);
        echo $this->myjson->my_json_encode($dados);
    }
}
