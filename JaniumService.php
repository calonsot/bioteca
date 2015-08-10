<?php
/**
 * Debug para cuando falla el servicio
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
				$response = $this->client->JaniumRequest(new SoapParam($metodo, "method"), new SoapParam(new JaniumRequestArg($llave, $valor), "arg"), new SoapParam(new JaniumRequestArg('numero_de_pagina', $numero_de_pagina), "arg"));
			
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
	
	function iteraResultados()
	{
		if ($this->resultados['status'])  //status del webservice
		{
			$datos = $this->resultados['datos'];
			
			if ($datos['status'] == 'ok')  //status de la ficha, dio respuesta el webservice
			{
				$total_de_registros = (Int) $datos['total_de_registros'];
				if ($total_de_registros > 0)
				{
					//Para armar el paginado
					$registros_por_pagina = (Int) $datos['registros_por_pagina'];
					$paginas = (Int) ($total_de_registros / $registros_por_pagina);  // castea a INT el resultado
					$residuo = $total_de_registros % $registros_por_pagina;
					
					if ($residuo > 0)
						$paginas+=1;
					
					echo "<pre>";
					print_r($this->resultados);
					echo "</pre>";
					echo "<br>";
					echo $paginas;
				} else
					echo "Sin resultados";
		
			} else
				echo "Sin resultados";
			
		} else
			echo "Sin resultados";			
	}
	
	function muestraFicha()
	{
		echo "<pre>";
		print_r($this->resultados);
		echo "</pre>";
	}
}