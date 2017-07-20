<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Acesso extends CI_Controller{
    
    public function lista_usuarios(){
        $this->load->database();
        $this->load->model("usuario_model");
        $usuarios = $this->usuario_model->retornar_todos();

        $dados = array("usuarios"=>$usuarios);
        echo $this->myjson->my_json_encode($dados);
    }

    public function autenticar(){
        $acesso = json_decode(file_get_contents('php://input'));

        if((isset($acesso->usuario_login) && !empty($acesso->usuario_login) &&
            (isset($acesso->usuario_senha) && !empty($acesso->usuario_senha))))
        {
            $this->load->model("usuario_model");
            $usuario = $this->usuario_model->retornar_por_login($acesso->usuario_login);

            if($usuario == NULL){
                $resp = array(
                    "status" => "false",
                    "descricao" => "Usuario não encontrato!",
                    "objeto" => $usuario
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }else{
                if($usuario["usuario_senha"] == $acesso->usuario_senha){                    
                    $resp = array(
                        "status" => "true",
                        "descricao" => "Usuário autenticado com sucesso!",
                        "objeto" => $usuario
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }else{
                    $resp = array(
                        "status" => "false",
                        "descricao" => "Senha inválida!",
                        "objeto" => NULL
                    );
                    $dados = array("response"=>$resp);
                    echo $this->myjson->my_json_encode($dados);
                }
            }
        }else{
            $resp = array(
                "status" => "false",
                "descricao" => "POST inválido!",
                "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }

    public function ativarAcesso(){
        $this->load->view('ativacao_cadastro_consumidor');
    }
}