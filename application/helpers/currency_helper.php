<?php

/* ------------------------------------ HELPER FORMATAÇÃO DE VALORES EM REAIS ------------------------------------*/

function numeroEmReais($numero){
	return "R$ " . number_format($numero, 2, ",", ".");
}