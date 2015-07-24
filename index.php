<?php

	$client = new SoapClient("http://www.webservicex.net/geoipservice.asmx?wsdl");
	print_r($client);
	echo "acabo";
