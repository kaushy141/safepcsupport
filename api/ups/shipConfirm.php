<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$endpointurl = ($ProductionMode ? UPS_PROD_URL :UPS_DEVL_URL ).'ShipConfirm';
$outputFileName = "XOLTResult.xml";
$labelImageFormatCode = 'PNG';

try {
	
	// Create AccessRequest XMl
	$accessRequestXML = new SimpleXMLElement ( "<AccessRequest></AccessRequest>" );
	$accessRequestXML->addChild ( "AccessLicenseNumber", $accessLicenseNumber );
	$accessRequestXML->addChild ( "UserId", $userId );
	$accessRequestXML->addChild ( "Password", $password );
	
	// Create ShipmentConfirmRequest XMl
	$shipmentConfirmRequestXML = new SimpleXMLElement ( "<ShipmentConfirmRequest ></ShipmentConfirmRequest>" );
	$request = $shipmentConfirmRequestXML->addChild ( 'Request' );
	$request->addChild ( "RequestAction", "ShipConfirm" );
	$request->addChild ( "RequestOption", "nonvalidate" );
	
	$labelSpecification = $shipmentConfirmRequestXML->addChild ( 'LabelSpecification' );
	$labelSpecification->addChild ( "HTTPUserAgent", "" );
	$labelPrintMethod = $labelSpecification->addChild ( 'LabelPrintMethod' );
	$labelPrintMethod->addChild ( "Code", "$labelImageFormatCode" );
	$labelPrintMethod->addChild ( "Description", "$shipment_description" );
	$labelImageFormat = $labelSpecification->addChild ( 'LabelImageFormat' );
	$labelImageFormat->addChild ( "Code", "$labelImageFormatCode" );
	$labelImageFormat->addChild ( "Description", "$shipment_description" );
	
	$shipment = $shipmentConfirmRequestXML->addChild ( 'Shipment' );
	$shipment->addChild ( "Description", "$shipment_description" );
	$rateInformation = $shipment->addChild ( 'RateInformation' );
	$rateInformation->addChild ( "NegotiatedRatesIndicator", "" );
	
	$shipper = $shipment->addChild ( 'Shipper' );
	$shipper->addChild ( "Name", "$shipment_shipper_name" );
	$shipper->addChild ( "PhoneNumber", "$shipment_shipper_phone_no" );
	$shipper->addChild ( "TaxIdentificationNumber", "$shipment_shipper_tax_id" );
	$shipper->addChild ( "ShipperNumber", "$shipment_shipper_number" );
	$shipperAddress = $shipper->addChild ( 'Address' );
	$shipperAddress->addChild ( "AddressLine1", "$shipment_shipper_address_line1");
	$shipperAddress->addChild ( "City", "$shipment_shipper_city" );
	$shipperAddress->addChild ( "StateProvinceCode", "$shipment_shipper_state_code" );
	$shipperAddress->addChild ( "PostalCode", "$shipment_shipper_post_code" );
	$shipperAddress->addChild ( "CountryCode", "$shipment_shipper_country_code" );
	
	$shipTo = $shipment->addChild ( 'ShipTo' );
	$shipTo->addChild ( "CompanyName", "$shipment_to_company" );
	$shipTo->addChild ( "AttentionName", "$shipment_to_name" );
	$shipTo->addChild ( "PhoneNumber", "$shipment_to_phone_number" );
	$shipTo->addChild ( "Email", "$shipment_to_email" );
	$shipToAddress = $shipTo->addChild ( 'Address' );
	$shipToAddress->addChild ( "AddressLine1", "$shipment_to_address_line1" );
	$shipToAddress->addChild ( "City", "$shipment_to_city" );
	$shipToAddress->addChild ( "StateProvinceCode", "$shipment_to_state_code" );
	$shipToAddress->addChild ( "PostalCode", "$shipment_to_post_code" );
	$shipToAddress->addChild ( "CountryCode", "$shipment_to_country_code" );
	
	$shipFrom = $shipment->addChild ( 'ShipFrom' );
	$shipFrom->addChild ( "CompanyName", "$shipment_origin_company" );
	$shipFrom->addChild ( "AttentionName", "$shipment_origin_name" );
	$shipFrom->addChild ( "PhoneNumber", "$shipment_origin_phone_no" );
	$shipFrom->addChild ( "TaxIdentificationNumber", "$shipment_origin_phone_no" );
	$shipFromAddress = $shipFrom->addChild ( 'Address' );
	$shipFromAddress->addChild ( "AddressLine1", "$shipment_origin_address_line1" );
	$shipFromAddress->addChild ( "City", "$shipment_origin_city" );
	$shipFromAddress->addChild ( "StateProvinceCode", "$shipment_origin_state_code" );
	$shipFromAddress->addChild ( "PostalCode", "$shipment_origin_post_code" );
	$shipFromAddress->addChild ( "CountryCode", "$shipment_origin_country_code" );
	
	$paymentInformation = $shipment->addChild ( 'PaymentInformation' );
	$prepaid = $paymentInformation->addChild ( 'Prepaid' );
	$billShipper = $prepaid->addChild ( 'BillShipper' );
	$billShipper->addChild ( "AccountNumber", "72620F" );
	
	$service = $shipment->addChild ( 'Service' );
	$service->addChild ( "Code", "$shipment_service_code" );
	$service->addChild ( "Description", "$shipment_service_name" );
	
	$package = $shipment->addChild ( 'Package' );
	$package->addChild ( "Description", "" );
	$packagingType = $package->addChild ( 'PackagingType' );
	$packagingType->addChild ( "Code", "02" );
	$packagingType->addChild ( "Description", "$shipment_description" );
	$packageWeight = $package->addChild ( 'PackageWeight' );
	$packageWeight->addChild ( "Weight", "$shipment_billing_weight" );
	$packageWeight->addChild ( 'UnitOfMeasurement', "$shipment_billing_weight_unit" );
	
	$requestXML = $accessRequestXML->asXML () . $shipmentConfirmRequestXML->asXML ();
	
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
		//echo $response;		
	} 	
	//Header ( 'Content-type: text/xml' );
} catch ( Exception $ex ) {
	echo $ex;
}

?>
