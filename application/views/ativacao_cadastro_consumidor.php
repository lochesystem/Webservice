<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Smarket - Ativação de Cadastro</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #FFCC80;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1{
		color: #000;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	h2{
		color: #000;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	h3{
		color: #000;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		background-color: #fff;
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 30px #D0D0D0;
	}
	</style>
</head>
<body>
<h1>Smarket</h1>
<div id="container">
	<h2>Ativação de Acesso</h2>

	<div id="body">
		<h3>Seu acesso foi ativado com sucesso !</h3>

		<h3>Agradeçemos pelo seu cadastro, fique a vontade para utilizar nosso app.</h3>
	</div>

	<p class="footer"><strong> @By Smarket API </strong><?php echo  (ENVIRONMENT === 'development') ?  ' - CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

</body>
</html>