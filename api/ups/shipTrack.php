<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
extract(App::exploreApiCredentilas('UpsShippingApi'));
$endpointurl = ($ProductionMode ? UPS_PROD_URL :UPS_DEVL_URL ).'Track';
$outputFileName = "XOLTResult.xml";

try {
	
	// Create AccessRequest XMl
	$accessRequestXML = new SimpleXMLElement ( "<AccessRequest></AccessRequest>" );
	$accessRequestXML->addChild ( "AccessLicenseNumber", $accessLicenseNumber );
	$accessRequestXML->addChild ( "UserId", $userId );
	$accessRequestXML->addChild ( "Password", $password );
	
	// Create TrackRequest XMl
	$trackRequestXML = new SimpleXMLElement ( "<TrackRequest></TrackRequest  >" );
	$request = $trackRequestXML->addChild ( 'Request' );
	$request->addChild ( "RequestAction", "Track" );
	$request->addChild ( "RequestOption", "activity" );
	
	$trackRequestXML->addChild ( "TrackingNumber", "$TrackingNumber" );
	
	$requestXML = $accessRequestXML->asXML () . $trackRequestXML->asXML ();
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $endpointurl );
	curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $requestXML );
	$response = curl_exec($ch);
	curl_close($ch);
	
	if ($response == false) {
		throw new Exception ( "Bad data." );
	} else {
		// save request and response to file
		$fw = fopen ( $outputFileName, 'w' );
		fwrite ( $fw, "Request: \n" . $requestXML . "\n" );
		fwrite ( $fw, "Response: \n" . $response . "\n" );
		fclose ( $fw );
	}
} catch ( Exception $ex ) {
	echo $ex;
}
?>