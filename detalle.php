<?php

require 'JaniumService.php';

if (isset($_POST['ficha']) && !empty($_POST['ficha']))
{
	$client = new JaniumService();
	$client->callWs('RegistroBib/Detalle', 'ficha', $_POST['ficha']);
	$ficha = $client->muestraFicha();
	
	if (is_array($ficha))
	{
		$datos_ficha = $ficha['datos_ficha'];
		$datos_generales = $ficha['datos_generales'];
		
		
		
		/*
		echo "<pre>";
		print_r($ficha);
		echo "</pre>";
		*/
		
		echo "<table class='info'>";
		
		$con_negritas = array('Pie de imprenta', 'Descripción', 'Materias', 'Autor secundario', 'ISBN', 'ISSN', 'Clasificación Dewey', 
				'Notas', 'Liga electrónica', 'Fechas de publicación y/o designación secuencial', 'Nota de forma física adicional disponible');  // Faltan EN:, Serie Sec., Clasificación local, Edición, Autor Corporativo
		$ya_mostro_autor_secundario = false;
		
		foreach ($datos_ficha as $etiqueta => $array_valores)
		{
			if (in_array($etiqueta, $con_negritas))
			{
				echo "<tr><td>";
				echo "<ul>";
				echo "<span id='eti'>".$etiqueta."</span><br>";
		
				foreach ($array_valores as $valor)
				{
					if ($etiqueta == 'Liga electrónica')
					{
						$info_liga = explode(" ", $valor, 2);
						echo "<li><a href='".$info_liga[0]."' target='blank'>".$info_liga[1]."</a></li>";
					} else if ($etiqueta == 'Autor secundario')
					{
						if (!$ya_mostro_autor_secundario)
						{
							$ya_mostro_autor_secundario = true;
							echo implode(" ; ", $array_valores);
						}
						
					} else	
						echo "<li>".$valor."</li>";
				}	
		
				echo "</ul>";
				echo "</td></tr>";
			
			} else {
				echo "<tr><td>";
				foreach ($array_valores as $valor)
					echo "<h2>".$valor."</h2>";
				echo "</td></tr>";
			}					
		}	
		
		echo "<tr><td>";
		echo "<span id='submenu'><a href='' id='ocultar_ficha_".$datos_generales['ficha']."' class='nb'>Contraer ficha completa</a></span>";
		echo "</td></tr>";
		echo "</table>";
		
	} else  // No existen datos
		echo $datos;	
			
} else
	echo "No existen datos para esta consulta";