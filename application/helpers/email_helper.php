<?php

/* ------------------------------------ HELPER ENVIO DE EMAIL ------------------------------------*/

function __construct(){
   parent::__construct();       
   $ci->load->library('session');
}

function enviarEmailConfirmacaoCadastro($email_destinatario, $nome_destinatario, $usuario_id, $tipo_usuario_id){
    // Carrega a library email
    $ci = &get_instance();
    $ci->load->library('email');
     
    //Inicia o processo de configuração para o envio do email
    $config['protocol'] = 'mail'; // define o protocolo utilizado
    $config['wordwrap'] = TRUE; // define se haverá quebra de palavra no texto
    $config['validate'] = TRUE; // define se haverá validação dos endereços de email
    $config['mailtype'] = 'html'; // tipo template

    // Inicializa a library Email, passando os parâmetros de configuração
    $ci->email->initialize($config);
    
    // Define remetente e destinatário
    $ci->email->from('contato@mlprojetos.com', 'Smarket App'); // Remetente
    $ci->email->to($email_destinatario,[$nome_destinatario]); // Destinatário

    // Define o assunto do email
    $ci->email->subject('Seja bem-vindo ao Smarket App.');

    // Preencher conteudo do template
    $header = "Olá " . $nome_destinatario;
    $p1 = "Agradeçemos pelo seu cadastro!";
    $p2 = "Através do Smarket App você terá acesso a produtos de qualidade e com o menor preço, aproveite !!!";
    $p3 = "Ative seu acesso clicando no link abaixo:";
    $p4 = "http://www.mlprojetos.com/webservice/index.php/acesso/aprovarcadastro/$usuario_id/$tipo_usuario_id/Sw280717";
    $footer = "Equipe Smarket App";
    $conteudo = array('header' => $header, 'p1' => $p1, 'p2' => $p2, 'p3' => $p3, 'p4' => $p4, 'footer' => $footer);
    var_dump($conteudo);
    $dados = array("conteudo" => $conteudo);
    $ci->email->message($ci->load->view("email-template", $dados, true));

    if($ci->email->send()){
        return "sucesso";
    }else{
        return "erro";
    }  
}

function enviarEmailRedefinirSenha($email_destinatario, $nome_destinatario, $usuario_token){
    // Carrega a library email
    $ci = &get_instance();
    $ci->load->library('email');
     
    //Inicia o processo de configuração para o envio do email
    $config['protocol'] = 'mail'; // define o protocolo utilizado
    $config['wordwrap'] = TRUE; // define se haverá quebra de palavra no texto
    $config['validate'] = TRUE; // define se haverá validação dos endereços de email
    $config['mailtype'] = 'html'; // tipo template

    // Inicializa a library Email, passando os parâmetros de configuração
    $ci->email->initialize($config);
    
    // Define remetente e destinatário
    $ci->email->from('contato@mlprojetos.com', 'Smarket App'); // Remetente
    $ci->email->to($email_destinatario,[$nome_destinatario]); // Destinatário

    // Define o assunto do email
    $ci->email->subject('Redefinição de Senha.');

    // Preencher conteudo do template
    $header = "Olá " . $nome_destinatario;
    $p1 = "Redefina sua senha clicando no link abaixo:";
    $p2 = "http://www.mlprojetos.com/webservice/index.php/acesso/redefinirSenha/$usuario_token";
    $footer = "Equipe Smarket App";
    $conteudo = array('header' => $header, 'p1' => $p1, 'p2' => $p2, 'footer' => $footer);

    $dados = array("conteudo" => $conteudo);
    $cis->email->message($cis->load->view("email-template", $dados, true));

    if($ci->email->send()){
        return "sucesso";
    }else{
        return "erro";
    }  
}

