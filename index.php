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
?>

<form action="index.php" method="post">
	Busqueda general: <input type="text" name="v"><br>
	<input type="submit" value="Buscar">
	<input type="hidden" name="metodo" value="RegistroBib/BuscarPorPalabraClaveGeneral">
	<input type="hidden" name="a" value="terminos">
</form>

<?php

if (isset($_POST['metodo']) && !empty($_POST['metodo']) && isset($_POST['a']) && !empty($_POST['a']) && isset($_POST['v']) && !empty($_POST['v']))
{
	if (isset($_POST['debug']) && $_POST['debug'] == '1')
		$client = new JaniumService(true);
	else
		$client = new JaniumService();
	
	if (isset($_POST['numero_de_pagina']) && !empty($_POST['numero_de_pagina']))
		$client->callWs($_POST['metodo'], $_POST['a'], $_POST['v'], $_POST['numero_de_pagina']);
	else
		$client->callWs($_POST['metodo'], $_POST['a'], $_POST['v']);
	
	$fichas = $client->iteraResultados();
	
	foreach ($fichas as $ficha)
	{
		echo "<div class='principal'>";
		echo "<table class='info' id='tabla_".$ficha['ficha']."'>";
		
		if (!empty($ficha['fecha']) || !empty($ficha['portada_url']) || !empty($ficha['portada_url_asociada']))
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
		
		echo "<div id='detalle_".$ficha['ficha']."'></div>";
		echo "</div>";
	}
	
	
	/*
	echo "<pre>";
	print_r($fichas);
	echo "</pre>";
	*/
	//echo $client->muestraFicha();
}
?>

</body>
</html>