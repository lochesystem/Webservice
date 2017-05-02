<?php
class Acesso extends CI_Controller{
    
    public function lista_usuarios(){
        $this->load->database();
        $this->load->model("usuario_model");
        $usuarios = $this->usuario_model->retornar_todos();

        $dados = array("usuarios"=>$usuarios);
        echo $this->myjson->my_json_encode($dados);
    }

    public function autenticar_aux()
    {
        var_dump($_POST["usuario_login"]);
        var_dump($_POST["usuario_senha"]);

        if((isset($_POST["usuario_login"]) && !empty($_POST["usuario_login"])) &&
            (isset($_POST["usuario_senha"]) && !empty($_POST["usuario_senha"])))
        {
            $acesso = array('usuario_login' => $this->input->post("usuario_login"), 
                            'usuario_senha' => $this->input->post("usuario_senha"));

            $this->load->model("usuario_model");
            $usuario = $this->usuario_model->retornar_por_login($this->input->post("usuario_login"));

            if($usuario == NULL){
                $resp = array(
                    "status" => "false",
                    "descricao" => "Usuario não encontrato!",
                    "objeto" => $usuario
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }else{
                if($usuario["usuario_senha"] == $acesso["usuario_senha"]){
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

    public function autenticar()
    {
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
}