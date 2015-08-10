<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'JaniumService.php';

if (isset($_GET['metodo']) && !empty($_GET['metodo']) && isset($_GET['a']) && !empty($_GET['a']) && isset($_GET['v']) && !empty($_GET['v']))
{
	if (isset($_GET['debug']) && $_GET['debug'] == '1')
		$client = new JaniumService(true);
	else
		$client = new JaniumService();
	
	if (isset($_GET['numero_de_pagina']) && !empty($_GET['numero_de_pagina']))
		$client->callWs($_GET['metodo'], $_GET['a'], $_GET['v'], $_GET['numero_de_pagina']);
	else
		$client->callWs($_GET['metodo'], $_GET['a'], $_GET['v']);
	
	//echo $client->iteraResultados();
	echo $client->muestraFicha();
} else
	echo json_encode(array('status' => false, 'datos' => array()));
