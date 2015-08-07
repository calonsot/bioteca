<?php
/**
 * Debug para cuando falla el servicio
 * @param unknown $client
 */

//error_reporting(E_ALL);
//ini_set('display_errors', 1);
header('Content-Type: text/json; charset=utf-8');

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
	public $ip = '200.12.166.51';
	public $client;
	
	function __construct($debug = null, $ip = null) {
		if (isset ( $debug ))
			$this->debug = $debug;
		if (isset ( $ip ))
			$this->ip = $ip;
		
		if ($this->debug) {
			$this->client = new SoapClient ( null, array (
					'location' => "http://".$this->ip."/janium/services/soap.pl",
					'uri' => 'http://janium.net/services/soap',
					'use' => SOAP_LITERAL,
					'trace' => 1,
					'exceptions' => true 
			) );
		} else {
			$this->client = new SoapClient ( null, array (
					'location' => "http://".$this->ip."/janium/services/soap.pl",
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
	function callWs($metodo, $llave, $valor) {
		try {
			$response = $this->client->JaniumRequest(new SoapParam($metodo, "method"), new SoapParam(new JaniumRequestArg($llave, $valor), "arg"));
			$response = array('status' => true, 'datos' => $response);
			echo json_encode($response);
		} catch ( SOAPFault $e ) {
			if ($this->debug)
			{
				var_dump ( $e );
				soapDebug ( $client );
			} else {
				$response = array('status' => false, 'datos' => array());
				echo json_encode($response);
			}
				
		}
	}
}

$client = new JaniumService();
$client->callWs('RegistroBib/BuscarPorPalabraClaveGeneral', 'terminos', 'Maíces mexicanos');