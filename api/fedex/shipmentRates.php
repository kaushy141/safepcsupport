<?php
require_once('fedex-common.php5');
$path_to_wsdl = __DIR__."/wsdl/RateService_v28.wsdl";
$outputFileName = "FedexResXOLTResult.xml";
$endpointurl = ($ProductionMode ? FEDEX_PROD_URL :FEDEX_DEVL_URL).'/dgds';
ini_set("soap.wsdl_cache_enabled", "0");

$opts = array(
	  'ssl' => array('verify_peer' => false, 'verify_peer_name' => false)
	);
$client = new SoapClient($path_to_wsdl, array('trace' => 1,'stream_context' => stream_context_create($opts))); 

$request['WebAuthenticationDetail'] = array(	
	'UserCredential' => array(
		'Key' => "$Key", 
		'Password' => "$Password"
	)
);

$recipient = array(
	'Contact' => array(
		'PersonName' => "$shipment_to_name",
		'CompanyName' => "$shipment_to_company",
		'PhoneNumber' => "$shipment_to_phone_number"
	),
	'Address' => array(
		'StreetLines' => array("$shipment_to_address_line1"),
		'City' => "$shipment_to_city",
		'StateOrProvinceCode' => $shipment_to_state_code ? "$shipment_to_state_code" : "VA",
		'PostalCode' => "$shipment_to_post_code",
		'CountryCode' => "$shipment_to_country_code",
		'Residential' => true
	)
);

$request['ClientDetail'] = array(
	'AccountNumber' => "$AccountNumber", 
	'MeterNumber' => "$MeterNumber"
);
$request['TransactionDetail'] = array('CustomerTransactionId' => 'mw_fedex_00000634836491304263696');
$request['Version'] = array(
	'ServiceId' => 'crs', 
	'Major' => '28', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['RequestedShipment'] = array(
	'ShipTimestamp' => date('c'),
	'DropoffType' => 'REGULAR_PICKUP', 
	'ServiceType' => $shipment_service_code,
	'PackagingType' => 'YOUR_PACKAGING',
	'TotalWeight' => array(
		'Value' => "$shipment_billing_weight", 
		'Units' => rtrim($shipment_billing_weight_unit, 'S') // valid values LB and KG
	), 
	'Shipper' => addShipper($shipment_shipper_id),
	'Recipient' => $recipient,
	'ShippingChargesPayment' => array(
		'PaymentType' => 'SENDER',
        'Payor' => array(
		'ResponsibleParty' => array(
			'AccountNumber' => "$AccountNumber"			
			)
		)
	),
	
	'LabelSpecification' => addLabelSpecification(),
	
	'PackageCount' => 1,
	'RequestedPackageLineItems' => array(
		'SequenceNumber'=>1,
		'GroupPackageCount'=>1,
		'Weight' => array(
			'Value' => "$shipment_billing_weight", 
			'Units' => rtrim($shipment_billing_weight_unit, 'S')
		),
		'CustomerReferences'=>array(
			'CustomerReferenceType' => "CUSTOMER_REFERENCE",
			'Value' => "$shipment_description"
		)
	)
);
$outputmessage = "Unknown";
$isSuccess = false;
try {
	
	$response = $client->getRates($request);  // FedEx web service invocation
	$fw = fopen ( $outputFileName, 'w' );	
	fwrite ( $fw, "Response: \n" . $client->__getLastResponse() . "\n" );
	fclose ( $fw );	
    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR'){
		$response_received = $client->__getLastResponse();
		$isSuccess = true;
		$outputmessage = "Rates qoutes loaded";
 
    }else{
		$outputmessage = "Couldn't load Rates qoutes";	
	}
} catch (SoapFault $exception) {
	$outputmessage = $exception->faultcode. ":". $exception->faultstring;
}

function addShipper($shipment_shipper_id){
	$shipmentShipper = new ShipmentShipper($shipment_shipper_id);
	$shipperRecord = $shipmentShipper->getDetails();
	$shipper = array(
		'Contact' => array(
			'PersonName' => $shipperRecord['shipment_shipper_name'],
			'CompanyName' => $shipperRecord['shipment_shipper_company'],
			'PhoneNumber' => $shipperRecord['shipment_shipper_phone_no']
		),
		'Address' => array(
			'StreetLines' => array($shipperRecord['shipment_shipper_address_line1']),
			'City' => $shipperRecord['shipment_shipper_city'],
			'StateOrProvinceCode' => "",
			'PostalCode' => $shipperRecord['shipment_shipper_post_code'],
			'CountryCode' => $shipperRecord['shipment_shipper_country_code']
		)
	);
	return $shipper;
}
function addRecipient(){
	$recipient = array(
		'Contact' => array(
			'PersonName' => 'Recipient Name',
			'CompanyName' => 'Recipient Company Name',
			'PhoneNumber' => '1234567890'
		),
		'Address' => array(
			'StreetLines' => array('Address Line 1'),
			'City' => 'Herndon',
			'StateOrProvinceCode' => 'VA',
			'PostalCode' => '20171',
			'CountryCode' => 'US',
			'Residential' => true
		)
	);
	return $recipient;	                                    
}

function addLabelSpecification(){
	$labelSpecification = array(
		'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
		'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
		'LabelStockType' => 'PAPER_7X4.75'
	);
	return $labelSpecification;
}
function addSpecialServices(){
	$specialServices = array(
		'SpecialServiceTypes' => 'ELECTRONIC_TRADE_DOCUMENTS'
	);
	return $specialServices; 
}

?>