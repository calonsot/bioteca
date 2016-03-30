<?php

include('phpqrcode/qrlib.php');
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
		print_r($datos_generales);
		echo "</pre>";
		*/
		
		echo "<table class='info'>";
		
		$con_negritas = array('Pie de imprenta', 'Descripción', 'Materias', 'Autor secundario', 'ISBN', 'ISSN', 'Clasificación Dewey', 
				'Notas', 'Liga electrónica', 'Fechas de publicación y/o designación secuencial', 'Nota de forma física adicional disponible',
				'Serie', 'Serie Sec.', 'Nombre', 'Método de codificación para la coordenada plana', 'Nota de con', 'Nota de tesis', 
				'Nota de Resumen', 'Bibliografía', 'Clasificación local', 'Escala'
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
				
				// Para que divida el tr y poner solo en la primera el codigo QR
				if ($primer_valor)
				{
					echo "</td>";
					echo "<td width='40%' class='portada' rowspan='4'>";
					QRcode::png($datos_generales['url'], 'qrcodes/'.$datos_generales['ficha'].'.png', null, 6, 1, true);
					echo "<img src='qrcodes/".$datos_generales['ficha'].".png' />";
					$primer_valor = false;
				}
					
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
					QRcode::png($datos_generales['url'], 'qrcodes/'.$datos_generales['ficha'].'.png', null, 4, 1, true);
					echo "<img src='qrcodes/".$datos_generales['ficha'].".png' />";
					$primer_valor = false;
				}
				
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