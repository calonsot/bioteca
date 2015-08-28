<?php
/**
 * Creado por Carlos Alonso, 10/08/2015
 * @param unknown $client
 */

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//header('Content-Type: text/json; charset=utf-8');

class JaniumRequestArg {
	function JaniumRequestArg($a, $v) {
		$this->a = $a;
		$this->v = $v;
	}
}
class JaniumService extends \SoapClient {
	
	/**
	 * Para mostrar errores o no
	 *
	 * @var unknown
	 */
	public $debug = false;
	public $client;
	public $resultados;
	public $valido = true;
	
	function __construct($debug = null, $ip = null) {
		if (isset ( $debug ))
			$this->debug = $debug;
		if (isset ( $ip ))
			$this->ip = $ip;
		
		if ($this->debug) {
			$this->client = new SoapClient ( null, array (
					'location' => "http://200.12.166.51/janium/services/soap.pl",
					'uri' => 'http://janium.net/services/soap',
					'use' => SOAP_LITERAL,
					'trace' => 1,
					'exceptions' => true 
			) );
		} else {
			$this->client = new SoapClient ( null, array (
					'location' => "http://200.12.166.51/janium/services/soap.pl",
					'uri' => 'http://janium.net/services/soap',
					'use' => SOAP_LITERAL 
			) );
		}
	}
	function soapDebug() {
		print var_dump ( array (
				"client" => $this->client,
				"LastRequestHeaders" => $this->client->__getLastRequestHeaders (),
				"LastRequest" => $this->client->__getLastRequest (),
				"LastResponseHeaders" => $this->client->__getLastResponseHeaders (),
				"LastResponse" => $this->client->__getLastResponse () 
		) );
	}
	function callWs($metodo, $llave, $valor, $numero_de_pagina = null) {
		try {
			if (empty($numero_de_pagina))
				$response = $this->client->JaniumRequest(new SoapParam($metodo, "method"), new SoapParam(new JaniumRequestArg($llave, $valor), "arg"));
			else  // Para cuando es mas de una pagina
				$response = $this->client->JaniumRequest(new SoapParam($metodo, "method"), new SoapParam(new JaniumRequestArg($llave, $valor), "arg"), 
						new SoapParam(new JaniumRequestArg('numero_de_pagina', $numero_de_pagina), "arg"));
			
			$this->resultados = array('status' => true, 'datos' => $response);
		} catch ( SOAPFault $e ) {
			if ($this->debug)
			{
				var_dump ( $e );
				soapDebug ( $client );
			} else
				$this->resultados = array('status' => false, 'datos' => array());
		}
	}
	
	/**
	 * Para ver si tiene o no resultados
	 * @param string $resultados, booleano para saber si tiene informacion
	 * @return multitype:boolean string |multitype:boolean
	 */
	function validacion($resultados = false)
	{
		if ($this->resultados['status'])  //status del webservice
		{
			$datos = $this->resultados['datos'];
				
			if ($datos['status'] == 'ok')  //status de la ficha, dio respuesta el webservice
			{		
				$total_de_registros = (Int) $datos['total_de_registros'];
				
				if ($resultados)
				{
					if ($total_de_registros > 0)
					{
						return array("status" => true);
					} else
						return array("status" => false, "mensaje" => "No existen datos para esta consulta");
				} else
					return array("status" => true);
				
			} else
				return array("status" => false, "mensaje" => "No existen datos para esta consulta");
			
		} else
			return array("status" => false, "mensaje" => "No existen datos para esta consulta");
	}
	
	function iteraResultados()
	{
		$validacion = $this->validacion(true);
		
		if ($validacion["status"])
		{
			$datos = $this->resultados['datos'];
			$datos_array = array();
				
			foreach ($datos['registros']->registro as $ficha)
			{
				/*
				echo "<pre>";
				print_r($ficha);
				echo "</pre>";
				*/
				
				// Parte de clasificaciones 
				$clasificaciones = '';
				
				if (is_array($ficha->clasificaciones->clasificacion))
				{
					foreach ($ficha->clasificaciones->clasificacion as $clasificacion => $valor)
						$clasificaciones.= $valor." ; ";
					$clasificaciones = substr($clasificaciones, 0, -3);
				} else
					$clasificaciones.= $ficha->clasificaciones->clasificacion;
					 
				// Parte de fecha
				$fecha = $ficha->fecha;
				
				// Parte de titulos
				$titulos = '';
				if (is_array($ficha->titulos->titulo))
				{
					foreach ($ficha->titulos->titulo as $titulo => $valor)
						$titulos.= $valor." ; ";
					$titulos = substr($titulos, 0, -3);
				} else
					$titulos.= $ficha->titulos->titulo;
				
				// Parte de autores
				$autores = '';
				
				if (is_array($ficha->autores->autor))
				{
					foreach ($ficha->autores->autor as $autor => $valor)
						$autores.= $valor." ; ";
					$autores = substr($autores, 0, -3);
				} else
					$autores.= $ficha->autores->autor;
				
				// Parte de la URL
				$url = $ficha->url;
				
				// Parte de numero de ficha
				$ficha_no = $ficha->ficha;
				
				// Parte de portada
				$portada_url = $ficha->portada->url;
				$portada_url_asociada = $ficha->portada->url_asociada;
				
				// Empujandolo a $datos_array
				array_push($datos_array, array('clasificaciones' => $clasificaciones, 'titulos' => $titulos, 'fecha' => $fecha,
				'autores' => $autores, 'url' => $url, 'ficha' => $ficha_no, 'portada_url' => $portada_url, 'portada_url_asociada' => $portada_url_asociada,
				'total_de_registros' => $datos['total_de_registros']));
			}
			/*
			echo "<pre>";
			print_r($this->resultados);
			echo "</pre>";
			*/
			return $datos_array;
			
		} else
			echo $validacion["mensaje"];			
	}
	
	function muestraFicha()
	{
		$validacion = $this->validacion();
		$ficha = array();
		
		// Atributos agrupados
		$autores = array();
		$titulos = array();
		$pies_imprenta = array();
		$descripciones = array();
		$materias = array();
		$autores_secundarios = array();
		$isbn = '';
		$clasificacion_dewey = '';
		$notas = array();
		$ligas_electronicas = array();
		
		if ($validacion["status"])
		{
			/*
			echo "<pre>";
			print_r($this->resultados['datos']);
			echo "</pre>";
			*/
			$etiquetas = $this->resultados['datos']['detalle']->etiquetas->etiqueta;
			
			if (count($etiquetas) == 0)
			{
				$ficha = "No existen datos para esta consulta";
				return $ficha;		
			} else {
				
				foreach ($etiquetas as $etq)
				{
					//echo $etq->etiqueta;
					switch ($etq->etiqueta)
					{
						case 'Autor':
							if (!isset($ficha['autores']))
								$ficha['autores'] = array();
							array_push($ficha['autores'], $etq->texto);	
							break;
						case 'Título':
							if (!isset($ficha['titulos']))
								$ficha['titulos'] = array();
							array_push($ficha['titulos'], $etq->texto);
							break;
						case 'Pie de imprenta':
							if (!isset($ficha['Pie de imprenta']))
								$ficha['Pie de imprenta'] = array();
							array_push($ficha['Pie de imprenta'], $etq->texto);
							break;
						case 'Descripción':
							if (!isset($ficha['Descripción']))
								$ficha['Descripción'] = array();
							array_push($ficha['Descripción'], $etq->texto);
							break;
						case 'Materia':
							if (!isset($ficha['Materias']))
								$ficha['Materias'] = array();
							array_push($ficha['Materias'], $etq->texto);
							break;
						case 'Autor Secundario':
							if (!isset($ficha['Autor secundario']))
								$ficha['Autor secundario'] = array();
							array_push($ficha['Autor secundario'], $etq->texto);
							break;
						case 'ISBN':
							if (!isset($ficha['ISBN']))
								$ficha['ISBN'] = array();
							array_push($ficha['ISBN'], $etq->texto);
							break;
						case 'Clasificación DEWEY':
							if (!isset($ficha['Clasificación Dewey']))
								$ficha['Clasificación Dewey'] = array();
							array_push($ficha['Clasificación Dewey'], $etq->texto);
							break;
						case 'Nota general':
							if (!isset($ficha['Notas']))
								$ficha['Notas'] = array();
							array_push($ficha['Notas'], $etq->texto);
							break;
						case 'Liga electrónica':
							if (!isset($ficha['Liga electrónica']))
								$ficha['Liga electrónica'] = array();
							array_push($ficha['Liga electrónica'], $etq->texto);
							break;	
						default:
							if (!isset($ficha[$etq->etiqueta]))
								$ficha[$etq->etiqueta] = array();
							array_push($ficha[$etq->etiqueta], $etq->texto);
							break; 					
					}  // End switch
				}  // End foreach
				
				/*
				 echo "<pre>";
				 print_r($ficha);
				 echo "</pre>";
				*/
				return array('datos_ficha' => $ficha, 'datos_generales' => array('portada_url' => $this->resultados['datos']['detalle']->portada->url,
						'portada_url_asociada' => $this->resultados['datos']['detalle']->portada->url_asociada, 
						'url' => $this->resultados['datos']['url'], 'ficha' => $this->resultados['datos']['ficha']));
			}

		} else
			return $validacion["mensaje"];
	}
	
	function paginado()
	{
		$datos = $this->resultados['datos'];
		$total_de_registros = (Int) $datos['total_de_registros'];
		$registros_por_pagina = (Int) $datos['registros_por_pagina'];
		$paginas = (Int) ($total_de_registros / $registros_por_pagina);  // castea a INT el resultado
		$residuo = $total_de_registros % $registros_por_pagina;
			
		if ($residuo > 0)
			$paginas+=1;
	}
}