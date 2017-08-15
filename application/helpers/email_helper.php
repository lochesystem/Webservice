<?php

function EnviarEmail($email_destinatario, $nome_destinatario, $usuario_id, $tipo_usuario_id)
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