<?php
require_once('fedex-common.php5');
$path_to_wsdl = __DIR__."/wsdl/ShipService_v26.wsdl";
$outputFileName = "FedexXOLTResult.xml";
define('SHIP_LABEL', 'shipexpresslabel.pdf');
define('SHIP_CODLABEL', 'CODexpressreturnlabel.pdf');

$endpointurl = $ProductionMode ? FEDEX_PROD_URL :FEDEX_DEVL_URL;
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
$request['TransactionDetail'] = array('CustomerTransactionId' => "$shipment_description");
$request['Version'] = array(
	'ServiceId' => 'ship', 
	'Major' => '26', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['RequestedShipment'] = array(
	'ShipTimestamp' => date('c'),
	'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION
	'ServiceType' => "$shipment_service_code", // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
	'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
	'TotalWeight' => array(
		'Value' => "$shipment_billing_weight", 
		'Units' => "$shipment_billing_weight_unit" // valid values LB and KG
	), 
	'Shipper' => array(
		'Contact' => array(
			'PersonName' => "$shipment_shipper_name",
			'CompanyName' => "$shipment_shipper_company",
			'PhoneNumber' => "$shipment_shipper_phone_no"
		),
		'Address' => array(
			'StreetLines' => array("$shipment_shipper_address_line1"),
			'City' => "$shipment_shipper_city",
			'StateOrProvinceCode' => "$shipment_shipper_state_code",
			'PostalCode' => "$shipment_shipper_post_code",
			'CountryCode' => "$shipment_shipper_country_code"
		)
	),
	'Recipient' => $recipient,
	'ShippingChargesPayment' => array(
		'PaymentType' => 'SENDER',
        'Payor' => array(
		'ResponsibleParty' => array(
			'AccountNumber' => "$AccountNumber",
			/*'Contact' => null,
			'Address' => array(
				'CountryCode' => "$shipment_to_country_code")*/
			)
		)
	),
	/*'SpecialServicesRequested' => array(
		'SpecialServiceTypes' => 'ELECTRONIC_TRADE_DOCUMENTS'
	),
	'CustomsClearanceDetail' =>array(
		'DutiesPayment' => array(
			'PaymentType' => 'SENDER',
			'Payor' =>array(
				'ResponsibleParty' => array(
					'AccountNumber' => "AccountNumber",
					'Contact' => NULL,
					'Address' => array(
						'CountryCode' => "$shipment_shipper_country_code"
					)
				)
			)
		),
		'CustomsValue' => array(
			'Currency' => 'GBP',
			'Amount' => '0'
		),
		'Commodities' => array(
			'NumberOfPieces' => 1,
			'Description' => "$shipment_description",
			'CountryOfManufacture'=>'GB',
			'Weight' => array(
				'Value' => "$shipment_billing_weight", 
				'Units' => rtrim($shipment_billing_weight_unit, 'S')
			),
			'Quantity' => 1,
			'QuantityUnits' => 'EA',
			'UnitPrice' => array(
				'Currency' => 'GBP',
				'Amount' => '0'
			)
		)
	),*/
	'LabelSpecification' => array(
		'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
		'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
		'LabelStockType' => 'PAPER_7X4.75'
	),
	/*'ShippingDocumentSpecification' => array(
		'ShippingDocumentTypes' => 'COMMERCIAL_INVOICE',
		'CommercialInvoiceDetail' =>array(
			'Format'=>array(
				'ImageType'=> 'PDF',
				'StockType' => 'PAPER_LETTER',
				'ProvideInstructions' => 'true'
			)
		)
	), */
	'PackageCount' => 1,
	'RequestedPackageLineItems' => array(
		'SequenceNumber'=>1,
		'GroupPackageCount'=>1,
		'Weight' => array(
			'Value' => "$shipment_billing_weight", 
			'Units' => "$shipment_billing_weight_unit"
		),
		/*'Dimensions' => array(
			'Length' => 20,
			'Width' => 20,
			'Height' => 10,
			'Units' => 'IN'
		)*/
		'CustomerReferences'=>array(
			'CustomerReferenceType' => "CUSTOMER_REFERENCE",
			'Value' => "$shipment_description"
		)
	)
);

$outputmessage = "Unknown";

try {
	
	$response = $client->processShipment($request);  // FedEx web service invocation	
	$fw = fopen ( $outputFileName, 'w' );	
	fwrite ( $fw, "Response: \n" . $client->__getLastResponse() . "\n" );
	fclose ( $fw );		
    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR'){ 
	
		if($response->HighestSeverity == 'SUCCESS')  
		{			
			$isSuccess = true;
		}	
    	
    }else{
		
		if($highestSeverity=="WARNING"){$outputmessage =  '<h2>The transaction returned a warning.</h2>';}
		if($highestSeverity=="ERROR"){$outputmessage =  '<h2>The transaction returned an Error.</h2>';}
		if($highestSeverity=="FAILURE"){$outputmessage =  '<h2>The transaction returned a Failure.</h2>';}
        //printError($client, $response);
    }

    //writeToLog($client);    // Write to log file
} catch (SoapFault $exception) {
	$outputmessage = $exception->faultcode. ":". $exception->faultstring;
    //printFault($exception, $client);
}
?>