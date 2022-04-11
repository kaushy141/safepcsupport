<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$endpointurl = ($ProductionMode ? DPD_PROD_URL :DPD_DEVL_URL )."shipping/shipment/$shipmentId/label/";
$outputFileName = "XOLTResultLabel.xml";
$labelImageFormatCode = 'PNG';
try {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $endpointurl);
	
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(	
													"Accept: text/html",
													"GeoClient: account/$delisId",
													"GeoSession: $geoSession"
												)
											);
	curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');	
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0 );
	curl_setopt( $ch, CURLOPT_ENCODING, '');
	
	$labelResponse = curl_exec($ch);	
	
	$fw = fopen ( $outputFileName, 'w' );	
	fwrite ( $fw, "Curl Request: \n" . json_encode(curl_getinfo($ch)) . "\n" );
	fwrite ( $fw, "Response: \n" . $labelResponse . "\n" );
	fclose ( $fw );	
	curl_close($ch);
} catch ( Exception $ex ) {
	$labelResponse = '';
	echo $ex;
}

?>
