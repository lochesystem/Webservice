<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

class Consumidor extends CI_Controller{
    
    function __construct(){
       parent::__construct();       
       $this->load->library('session');
    }

    public function adicionar(){
        $data = json_decode(file_get_contents('php://input'));

        if((isset($data->tipo_usuario_id) && !empty($data->tipo_usuario_id)) &&
            (isset($data->consumidor_nome) && !empty($data->consumidor_nome)) &&
            (isset($data->consumidor_sobrenome) && !empty($data->consumidor_sobrenome)) &&
            (isset($data->email_descricao) && !empty($data->email_descricao)) &&
            (isset($data->tipo_telefone_id) && !empty($data->tipo_telefone_id)) &&
            (isset($data->telefone_ddd) && !empty($data->telefone_ddd)) &&
            (isset($data->telefone_numero) && !empty($data->telefone_numero)) &&
            (isset($data->usuario_senha) && !empty($data->usuario_senha)) &&
            (isset($data->token) && !empty($data->token))
          )
        {
            if($data->token == "Sw280717"){

                $Email = array(
                    "email_descricao" => $data->email_descricao
                );
                $this->load->model("email_model");
                $email_id = $this->email_model->adicionar($Email);

                if(!empty($email_id))
                {
                    $this->load->model("usuario_model");

                    $prox_usuario_id = $this->usuario_model->retornar_id_prox_usuario($data->tipo_usuario_id);
                
                    $data = json_decode(file_get_contents('php://input'));

                    $usuario = array("usuario_id" => $prox_usuario_id,
                                     "tipo_usuario_id" => $data->tipo_usuario_id,
                                     "status_id" => 2, // Status Ativo
                                     "usuario_senha" => $data->usuario_senha,
                                     "usuario_login" => $data->email_descricao,
                                     "usuario_data_cadastro" => date('Y-m-d H:i'),
                                     "email_id" => $email_id);

                    $retorno = $this->usuario_model->adicionar_usuario($usuario);
                    $usuario_id = $this->usuario_model->retornar_max_id($data->tipo_usuario_id);

                    if(!empty($usuario_id))
                    {
                        $consumidor = array("usuario_id" => $usuario_id,
                                             "tipo_usuario_id" => $data->tipo_usuario_id,
                                             "consumidor_nome" => $data->consumidor_nome,
                                             "consumidor_sobrenome" => $data->consumidor_sobrenome);

                        $this->load->model("consumidor_model");
                        $resp = $this->consumidor_model->adicionar_consumidor($consumidor);

                        $telefone = array("tipo_telefone_id" => $data->tipo_telefone_id,
                                          "telefone_ddd" => $data->telefone_ddd,
                                          "telefone_numero" => $data->telefone_numero);

                        $this->load->model("telefone_model");
                        $telefone_id = $this->telefone_model->adicionar_telefone($telefone);

                        $consumidor_telefone = array("usuario_id" => $usuario_id,
                                                     "tipo_usuario_id" => $data->tipo_usuario_id,
                                                     "telefone_id" => $telefone_id);

                        $this->load->model("consumidor_telefone_model");
                        $resposta = $this->consumidor_telefone_model->adicionar_consumidor_telefone($consumidor_telefone);
                        
                        if($resposta == "SUCESSO")
                        {
                            $enviou = $this->EnviarEmail($data->email_descricao,$data->nome_destinatario,$usuario_id,$data->tipo_usuario_id);
                            
                            $resp = array("status" => "true",
                                          "descricao" => "Consumidor cadastrado com sucesso!",
                                          "objeto" => NULL
                            );
                            $dados = array("response"=>$resp);
                            echo $this->myjson->my_json_encode($dados);
                        }
                        else
                        {
                            $resp = array("status" => "false",
                                          "descricao" => "Erro ao inserir consumidor_telefone",
                                          "objeto" => NULL
                            );
                            $dados = array("response"=>$resp);
                            echo $this->myjson->my_json_encode($dados);
                        }
                    }
                    else
                    {
                        $resp = array("status" => "false",
                                      "descricao" => "Erro ao retornar usuario_id",
                                      "objeto" => NULL
                        );
                        $dados = array("response"=>$resp);
                        echo $this->myjson->my_json_encode($dados);
                    }
                }
                else
                {
                    $resp = array("status" => "false",
                                  "descricao" => "Erro ao retornar email_id",
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
                    "descricao" => "Acesso webservice negado!",
                    "objeto" => NULL
                );
                $dados = array("response"=>$resp);
                echo $this->myjson->my_json_encode($dados);
            }
        }
        else
        {
            $resp = array("status" => "false",
                          "descricao" => "Requisição invalida!",
                          "objeto" => NULL
            );
            $dados = array("response"=>$resp);
            echo $this->myjson->my_json_encode($dados);
        }
    }

    public function getConsumidor($usuario_id, $tipo_usuario_id){
        $this->load->database();
        $this->load->model("consumidor_model");
        $consumidor = $this->consumidor_model->retornar_dados_consumidor($usuario_id, $tipo_usuario_id);
        
        $dados = array("consumidor"=>$consumidor);

        echo $this->myjson->my_json_encode($dados);
    }

    public function testeEnvioEmail(){
        $enviou = $this->EnviarEmail('murilo.lfs@gmail.com','Murilo Lourenço',1,1);
        $resp = array("status" => "true",
                      "descricao" => "teste",
                      "objeto" => $enviou);
        $dados = array("response"=>$resp);
        echo $this->myjson->my_json_encode($dados);
    }

    /* Responsável por enviar o email */
    public function EnviarEmail($email_destinatario, $nome_destinatario, $usuario_id, $tipo_usuario_id)
    {
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
        $this->email->subject('Seja bem-vindo ao Smarket App.');
 
        // Preencher conteudo do template
        $header = "Olá " . $nome_destinatario;
        $p1 = "Agradeçemos pelo seu cadastro!";
        $p2 = "Através do Smarket App você terá acesso a produtos de qualidade e com o menor preço, aproveite !!!";
        $p3 = "Ative seu acesso clicando no link abaixo:";
        $p4 = "http://www.mlprojetos.com/webservice/index.php/acesso/aprovarcadastro/' . $usuario_id . '/' . $tipo_usuario_id . '/Sw280717";
        $footer = "Equipe Smarket App";
        $conteudo = array('header' => $header, 'p1' => $p1, 'p2' => $p2, 'p3' => $p3, 'p4' => $p4, 'footer' => $footer);

        $dados = array("conteudo" => $conteudo);
        $this->email->message($this->load->view("email-template", $dados, true));

        if($this->email->send()){
            return "Email enviado com sucesso!";
        }else{
            return "Erro no disparo de email!";
        }  
    }
}