<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$endpointurl = ($ProductionMode ? DPD_PROD_URL :DPD_DEVL_URL ).'/shipping/network/?';
$outputFileName = "XOLTResultNetwork.xml";
$labelImageFormatCode = 'PNG';
$neworkReqParameter = array(
		"businessUnit"=>0,
		"deliveryDirection"=>2,
		"numberOfParcels"=>1,
		"shipmentType"=>0,
		"totalWeight"=>1.0,
		"deliveryDetails.address.countryCode"=>"$shipment_to_country_code",
		"deliveryDetails.address.countryName"=>"",
		"deliveryDetails.address.locality"=>"",
		"deliveryDetails.address.organisation"=>"",
		"deliveryDetails.address.postcode"=>"$shipment_to_post_code",
		"deliveryDetails.address.property"=>"",
		"deliveryDetails.address.street"=>"",
		"deliveryDetails.address.town"=>"",
		"deliveryDetails.address.county"=>"",
		
		"collectionDetails.address.countryCode"=>"$shipment_origin_country_code",
		"collectionDetails.address.countryName"=>"",
		"collectionDetails.address.locality"=>"",
		"collectionDetails.address.organisation"=>"",
		"collectionDetails.address.postcode"=>"$shipment_origin_post_code",
		"collectionDetails.address.property"=>"",
		"collectionDetails.address.street"=>"",
		"collectionDetails.address.town"=>"",
		"collectionDetails.address.county"=>""
	);
//print_r($neworkReqParameter);
$requestPrameter = http_build_query( (object)$neworkReqParameter);
try {	
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $endpointurl.$requestPrameter);
	//curl_setopt( $ch, CURLOPT_POST, true );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array("GeoClient: account/$delisId"));
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array("GeoSession: account/$geoSession"));	
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	//curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($neworkReqParameter));
	$networkResponse = curl_exec($ch);
	$fw = fopen ( $outputFileName, 'w' );	
	fwrite ( $fw, "Response: \n" . $networkResponse . "\n" );
	fclose ( $fw );	
} catch ( Exception $ex ) {
	echo $ex;
}

?>
