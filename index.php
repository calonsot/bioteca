<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        
		<link rel="stylesheet" type="text/css" href="css/janium.css">
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/janium.js"></script>
</head>
<body>

<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

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
	
	$fichas = $client->iteraResultados();
	
	foreach ($fichas as $ficha)
	{
		echo "<div class='principal'>";
		echo "<table class='info'>";
		
		if (!empty($ficha['fecha']) || !empty($ficha['portada_url']))
		{
			echo "<tr><td class='fecha'>";
			echo $ficha['fecha'];
			echo "</td>";
			
			echo "<td class='portada' rowspan='4'>";
			if (!empty($ficha['portada_url']))
				echo "<img src='".$ficha['portada_url']."' alt='portada' height='150px;' />";
			if(!empty($ficha['portada_url_asociada']))
				echo "<p><a href='".$ficha['portada_url_asociada']."' target='blank'>Ver en línea</a></p>";
			else  // Por si es un libro
				echo "<p><a href='".$ficha['url']."' target='blank'>Ver disponibilidad</a></p>";
			echo "</td>";
			
			
			echo "</tr>";
		}
		
		if (!empty($ficha['clasificaciones']))
		{
			echo "<tr><td align='left'>";
			echo $ficha['clasificaciones'];
			echo "</td></tr>";
		}
		
		if (!empty($ficha['titulos']))
		{
			echo "<tr><td align='left'>";
			echo "<strong>".$ficha['titulos']."</strong>";
			echo "</td></tr>";
		}

		if (!empty($ficha['autores']))
		{
			echo "<tr><td>";
			echo "<span class='identar'>".$ficha['autores']."</span>";
			echo "</td></tr>";
		}
		
		echo "<tr><td>";
		echo "<a href='' id='ficha_".$ficha['ficha']."'>Ver ficha completa</a>";
		echo "</td></tr>";
		//echo $ficha['ficha'];

		
		echo "</table>";
		echo "</div>";
	}
	
	
	/*
	echo "<pre>";
	print_r($fichas);
	echo "</pre>";
	*/
	//echo $client->muestraFicha();
} else
	echo json_encode(array('status' => false, 'datos' => array()));


?>

</body>
</html>