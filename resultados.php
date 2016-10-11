<?php
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
	
	if (isset($_GET['inicio']) && $_GET['inicio'] == '1' && !empty($fichas))
		echo "<div align='right' class='tit'>".$fichas[0]['total_de_registros']." resultado(s) para la búsqueda \"".$_GET['v']."\""."</div>";

	foreach ($fichas as $ficha)
	{
		echo "<div class='drop-shadow raised principal'>"; // Inicia div con resultado
		echo "<table class='info' id='tabla_".$ficha['ficha']."'>"; // Inicia tabla 

		if (!empty($ficha['clasificaciones']) || !empty($ficha['portada_url']) || !empty($ficha['portada_url_asociada']))
		{
			echo "<tr><td align='left' width=60%'>";
			echo $ficha['clasificaciones'];
			echo "</td>";
			
			echo "<td width='40%' class='portada' rowspan='4'>";
			if (!empty($ficha['portada_url']))
			{
				if (strpos($ficha['portada_url'],'https://www.youtube.com') !== false)
				{
					$portada_url_array = explode(',', $ficha['portada_url']);
					$portada_url_youtube = explode('|', $portada_url_array[1]);
					$portada_url_youtube_limpio = str_replace("&hl=es_ES&fs=1%20", "", $portada_url_youtube[1]);
					$portada_url_youtube_limpio = str_replace("&hl=es_ES&fs=1", "", $portada_url_youtube_limpio);
					$portada_url_youtube_limpio = str_replace("/v/", "/embed/", $portada_url_youtube_limpio);
					
					?>
					<iframe height="150" src="<?php echo $portada_url_youtube_limpio; ?>" allowfullscreen frameborder="0"></iframe>
					<?php 
				} else {
					$portada_url_limpio = str_replace("%20%20%20", "", $ficha['portada_url']);
					$portada_url_limpio = str_replace("%20%20", "", $ficha['portada_url']);
					$portada_url_limpio = str_replace("%20", "", $ficha['portada_url']);
					echo "<img src='".$portada_url_limpio."' alt='portada' height='150px;' />";
				}
			}	
	
			if(!empty($ficha['portada_url_asociada']))
				echo "<span id='submenu'><img src='images/1_ic_ver.png' width='23' height='17'/><a href='".$ficha['portada_url_asociada']."' target='blank' class='nb'>Ver en línea</a></span>";
			
			//echo "AAA";
			     //else  // Por si es un libro
				//echo "<p><a href='".$ficha['url']."' target='blank'>Ver disponibilidad</a></p>";
			
			echo "</td>";
			echo "</tr>";
		}
		
		if (!empty($ficha['fecha']))
		{
			echo "<tr><td class='fecha'>";
			echo $ficha['fecha'];
			echo "</td></tr>";
		}

		if (!empty($ficha['titulos']))
		{
			echo "<tr><td align='left'>";
			echo "<h2>".$ficha['titulos']."</h2>";
			echo "</td></tr>";
		}

		if (!empty($ficha['autores']))
		{
			echo "<tr><td>";
			
			if (!empty($ficha['titulos']))  // Para identar
				echo "<span class='identar'>".$ficha['autores']."</span>";
			else 
				echo $ficha['autores'];
			echo "</td></tr>";
		}

		echo "<tr><td>";
		echo "<span id='submenu'><a href='' id='ficha_".$ficha['ficha']."' class='nb'>Ver ficha completa</a></span>";
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
	 
	echo $client->muestraFicha();*/
}