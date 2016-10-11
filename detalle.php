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
		print_r($datos_ficha);
		echo "</pre>";
		*/
		
		echo "<table class='info'>";
		
		$con_negritas = array('Pie de imprenta', 'Descripción', 'Materias', 'Autor secundario', 'ISBN', 'ISSN', 'Clasificación Dewey', 
				'Notas', 'Liga electrónica', 'Fechas de publicación y/o designación secuencial', 'Nota de forma física adicional disponible',
				'Serie', 'Serie Sec.', 'Nombre', 'Método de codificación para la coordenada plana', 'Nota de con', 'Nota de tesis', 
				'Nota de Resumen', 'Bibliografía', 'Clasificación local', 'Escala', 'Título Variante', 'Edición',
				'Nota de condiciones de uso y reproducción', 'Nota de exposiciones'
		);  // Faltan EN:, Serie Sec., Clasificación local, Edición, Autor Corporativo
		$ya_mostro_autor_secundario = false;
		$primer_valor = true;
		
		foreach ($datos_ficha as $etiqueta => $array_valores)
		{
			if (in_array($etiqueta, $con_negritas))
			{
				echo "<tr><td align='left' width=60%'>";
				echo "<ul>";
				echo "<span id='eti'>".$etiqueta."</span><br>";
		
				foreach ($array_valores as $valor)
				{
					if ($etiqueta == 'Liga electrónica')
						echo "<li>".$client->make_clickable($valor)."</li>";
					
					else if ($etiqueta == 'Autor secundario')
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
					
				echo "</td>";
				echo "</tr>";
			
			} else {
				echo "<tr><td>";
				foreach ($array_valores as $valor)	
					echo "<h2>".$valor."</h2>";
				
				// Para que divida el tr y poner solo en la primera el codigo QR
				if ($primer_valor)
				{
					echo "</td>";
					echo "<td width='40%' class='portada' rowspan='4'>";
					$portada_url_limpio = str_replace("%20%20%20", "", $datos_generales['portada_url']);
					$portada_url_limpio = str_replace("%20%20", "", $datos_generales['portada_url']);
					$portada_url_limpio = str_replace("%20", "", $datos_generales['portada_url']);
					echo "<img src='".$portada_url_limpio."' alt='portada' height='150px;' />";
					
					if(!empty($datos_generales['portada_url_asociada']))
						echo "<span id='submenu'><img src='images/1_ic_ver.png' width='23' height='17'/><a href='".$datos_generales['portada_url_asociada']."' target='blank' class='nb'>Ver en línea</a></span>";
					
					$primer_valor = false;
				}
				
				echo "</td></tr>";
			}					
		}	
		
		
		// Para saber si es exposicion y ponerle el correo de contacto
		if (isset($datos_ficha['Materias']) && count($datos_ficha['Materias']) > 0 && strpos(strtolower($datos_ficha['Materias'][0]), 'exposición') !== false)
		{
			echo "<tr><td>";
			echo "<strong>Si te interesa esta exposición en préstamo contáctanos a bancoima@xolo.conabio.gob.mx</strong>";
			echo "</td></tr>";
			
		} elseif (isset($datos_ficha['Nota de Resumen']) && count($datos_ficha['Nota de Resumen']) > 0 && strpos(strtolower($datos_ficha['Nota de Resumen'][0]), 'exposición') !== false) {
			echo "<tr><td>";
			echo "<strong>Si te interesa esta exposición en préstamo contáctanos a bancoima@xolo.conabio.gob.mx</strong>";
			echo "</td></tr>";

		} elseif (isset($datos_ficha['Notas']) && count($datos_ficha['Notas']) > 0 && strpos(strtolower($datos_ficha['Notas'][0]), 'exposición') !== false) {
			echo "<tr><td>";
			echo "<strong>Si te interesa esta exposición en préstamo contáctanos a bancoima@xolo.conabio.gob.mx</strong>";
			echo "</td></tr>";
		} 			
		
		
		echo "<tr><td>";
		echo "<span id='submenu'><a href='' id='ocultar_ficha_".$datos_generales['ficha']."' class='nb'>Contraer ficha completa</a></span>";
		echo "</td></tr>";
		echo "</table>";
		
	} else  // No existen datos
		echo $datos;	
			
} else
	echo "No existen datos para esta consulta";