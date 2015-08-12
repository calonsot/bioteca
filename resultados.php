<?php
require 'JaniumService.php';

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