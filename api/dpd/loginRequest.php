<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$endpointurl = ($ProductionMode ? DPD_PROD_URL :DPD_DEVL_URL ).'/user/?action=login';
$outputFileName = "DPD_LOGIN_Result.xml";
$labelImageFormatCode = 'PNG';
$soapHeaderBody = base64_encode($delisId.":".$password);

try {	
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $endpointurl );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$soapHeaderBody));	
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $soapHeaderBody );
	$response = curl_exec($ch);
	$fw = fopen ( $outputFileName, 'w' );	
	fwrite ( $fw, "Response: \n" . $response . "\n" );
	fclose ( $fw );	
} catch ( Exception $ex ) {
	echo $ex;
}

?>
