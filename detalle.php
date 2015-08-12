<?php

require 'JaniumService.php';

if (isset($_POST['ficha']) && !empty($_POST['ficha']))
{
	$client = new JaniumService();
	$client->callWs('RegistroBib/Detalle', 'ficha', $_POST['ficha']);
	$ficha = $client->muestraFicha();
	
	if (is_array($ficha))
	{
		/*
		echo "<pre>";
		print_r($ficha);
		echo "</pre>";
		*/
		
		echo "<table class='info'>";
		
		// Autor y portada
		if (!empty($ficha['autores']))
		{
			echo "<tr><td>";
			echo implode(" ; ", $ficha['autores']);
			echo "</td></tr>";
		}
		
		// Ttitulo
		if (!empty($ficha['titulos']) || !empty($ficha['portada_url']) || !empty($ficha['portada_url_asociada']))
		{
			echo "<tr><td>";
			echo "<span class='identar'>".implode(" ; ", $ficha['titulos'])."</span>";		
			echo "</td>";
				
			echo "<td class='portada' rowspan='9'>";
			if (!empty($ficha['portada_url']))
				echo "<img src='".$ficha['portada_url']."' alt='portada' height='150px;' />";
			if(!empty($ficha['portada_url_asociada']))
				echo "<p><a href='".$ficha['portada_url_asociada']."' target='blank'>Ver en línea</a></p>";
			else  // Por si no esta online
				echo "<p><a href='".$ficha['url']."' target='blank'>Ver disponibilidad</a></p>";
			
			echo "</td>";
			echo "</tr>";
		}
		
		// Pie de imprenta
		if (!empty($ficha['pies_imprenta']))
		{
			echo "<tr><td>";
			echo "<p>";
			echo "<strong>Pie de imprenta</strong><br>";
			echo implode(" ", $ficha['pies_imprenta']);
			echo "</p>";
			echo "</td></tr>";
		}
		
		// Descripcion
		if (!empty($ficha['descripciones']))
		{
			echo "<tr><td>";
			echo "<p><ul>";
			echo "<strong>Descripción</strong><br>";
			
			foreach ($ficha['descripciones'] as $desc)
				echo "<li>".$desc."</li>";	
			
			echo "</ul></p>";
			echo "</td></tr>";
		}
		
		// Materia
		if (!empty($ficha['materias']))
		{
			echo "<tr><td>";
			echo "<p><ul>";
			echo "<strong>Materias</strong><br>";
				
			foreach ($ficha['materias'] as $mat)
				echo "<li>".$mat."</li>";
				
			echo "</ul></p>";
			echo "</td></tr>";
		}
		
		// Autor secundario
		if (!empty($ficha['autores_secundarios']))
		{
			echo "<tr><td>";
			echo "<p>";
			echo "<strong>Autor secundario</strong><br>";
			echo implode(" ; ", $ficha['autores_secundarios']);
			echo "</p>";
			echo "</td></tr>";
		}
		
		// ISBN
		if (!empty($ficha['isbn']))
		{
			echo "<tr><td>";
			echo "<p>";
			echo "<strong>ISBN</strong><br>";
			echo $ficha['isbn'];
			echo "</p>";
			echo "</td></tr>";
		}
		
		// Clasificacion Dewey
		if (!empty($ficha['clasificacion_dewey']))
		{
			echo "<tr><td>";
			echo "<p>";
			echo "<strong>Clasificación Dewey</strong><br>";
			echo $ficha['clasificacion_dewey'];
			echo "</p>";
			echo "</td></tr>";
		}
		
		// Nota
		if (!empty($ficha['notas']))
		{
			echo "<tr><td>";
			echo "<p><ul>";
			echo "<strong>Notas:</strong><br>";
		
			foreach ($ficha['notas'] as $nota)
				echo "<li>".$nota."</li>";
		
			echo "</ul></p>";
			echo "</td></tr>";
		}
		
		// Liga electrónica
		if (!empty($ficha['ligas_electronicas']))
		{
			echo "<tr><td>";
			echo "<p><ul>";
			echo "<strong>Liga electrónica:</strong><br>";
		
			foreach ($ficha['ligas_electronicas'] as $liga)
			{
				$info_liga = explode(" ", $liga, 2);
				echo "<li><a href='".$info_liga[0]."' target='blank'>".$info_liga[1]."</a></li>";
			}
		
			echo "</ul></p>";
			echo "</td></tr>";
		}
		
		echo "<tr><td>";
		echo "<a href='' id='ocultar_ficha_".$ficha['ficha']."'>Contraer ficha completa</a>";
		echo "</td></tr>";
		echo "</table>";
		
	} else  // No existen datos
		echo $datos;	
			
} else
	echo "No existen datos para esta consulta";