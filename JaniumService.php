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
				array_push($datos_array, array('clasificaciones' => $clasificaciones, 'fecha' => $fecha, 'titulos' => $titulos,
				'autores' => $autores, 'url' => $url, 'ficha' => $ficha_no, 'portada_url' => $portada_url, 'portada_url_asociada' => $portada_url_asociada));
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
		$ficha = '';
		
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
					switch ($etq->etiqueta)
					{
						case 'Autor':
							array_push($autores, $etq->texto);	
							break;
						case 'Título':
							array_push($titulos, $etq->texto);
							break;
						case 'Pie de imprenta':
							array_push($pies_imprenta, $etq->texto);
							break;
						case 'Descripción':
							array_push($descripciones, $etq->texto);
							break;
						case 'Materia':
							array_push($materias, $etq->texto);
							break;
						case 'Autor Secundario':
							array_push($autores_secundarios, $etq->texto);
							break;
						case 'ISBN':
							$isbn.= $etq->texto;
							break;
						case 'Clasificación DEWEY':
							$clasificacion_dewey.= $etq->texto;
							break; 			
						case 'Nota general':
							array_push($notas, $etq->texto);
							break;
						case 'Liga electrónica':
							array_push($ligas_electronicas, $etq->texto);
							break;					
					}  // End switch
				}  // End foreach
				
				return $ficha = array('autores' => $autores, 'titulos' => $titulos, 'pies_imprenta' => $pies_imprenta,
						'descripciones' => $descripciones, 'materias' => $materias, 'autores_secundarios' => $autores_secundarios,
						'isbn' => $isbn, 'clasificacion_dewey' => $clasificacion_dewey, 'notas' => $notas,
						'ligas_electronicas' => $ligas_electronicas, 'portada_url' => $this->resultados['datos']['detalle']->portada->url,
						'portada_url_asociada' => $this->resultados['datos']['detalle']->portada->url_asociada, 
						'url' => $this->resultados['datos']['url'], 'ficha' => $this->resultados['datos']['ficha']
				);
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