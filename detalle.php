<?php

require 'JaniumService.php';

if (isset($_POST['ficha']) && !empty($_POST['ficha']))
{
	$client = new JaniumService();
	$client->callWs('RegistroBib/Detalle', 'ficha', $_POST['ficha']);
	$datos = $client->muestraFicha();
	
	if (is_array($datos))
	{
		echo "<pre>";
		print_r($datos);
		echo "</pre>";
		
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
		echo "<div id='detalle_".$ficha['ficha']."'>";
		echo "</td></tr>";
		//echo $ficha['ficha'];
		
		
		echo "</table>";
		echo "</div>";
		
	} else  // No existen datos
		echo $datos;	
			
} else
	echo "No existen datos para esta consulta";