<?php
$wc_id	= intval($_REQUEST['id']);
$appInfo = 	new AppInfo();
$info = $appInfo->getDetails();

$collection			= new Collection($wc_id);
$wcData = $collection->getDetails();		

<<<<<<< HEAD
=======
$carrier = new Carrier($wcData['wc_carrier_id']);
$carrierData	= $carrier->getDetails();

>>>>>>> 77a717f (Version 2)
$customer = new Customer($wcData['wc_customer_id']);
$customerData = $customer->getDetails();		

$customerAddress = new CustomerAddress($wcData['wc_customer_address_id']);
$customerAddressData = $customerAddress->getDetails();

$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
$data['wc_collection_code_number'] = $wcData['wc_code'];

$report			= new Report("Basic Audit Report $wcData[wc_code]",true);
$report->heading= "Basic Audit Report $wcData[wc_code]";
$report->query 	= $collection->getReportSqlBasicAuditReport();
<<<<<<< HEAD
$report->address = $info['wc_collection_address'];
=======
$report->address = $carrierData['carrier_address'] ? $carrierData['carrier_address']: $info['wc_collection_address'];
>>>>>>> 77a717f (Version 2)
$data = array(
				"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
				"wc_waste_removed_from" => $customerData['customer_name']."<br/>".$customerAddressData['full_address'],						
				"customer_company" => $customerData['customer_company'],
				"customer_name" => $customerData['customer_name'],
				"wc_consignment_note_code" => $wcData['wc_consignment_note_code'],
				"wc_collection_code_number" => $wcData['wc_code'],
				"wc_collection_date" => date("d/m/Y",strtotime($wcData['wc_due_date'])),
				"customer_address" => $customerAddressData['full_address'],
<<<<<<< HEAD
				"reportfootertext" => "Safe PC Disposal is the trading name of E World UK Ltd. Registered in England and Wales 05983190 ".$info['wc_collection_address']
=======
				"reportfootertext" => "Safe PC Disposal is the trading name of E World UK Ltd. Registered in England and Wales 05983190 ".( $carrierData['carrier_address'] ? $carrierData['carrier_address']:$info['wc_collection_address']),
				"poc_number"=>$carrierData['carrier_poc_number'],
				"environment_number"=>$carrierData['carrier_environment_number'],
				"hazardous_licence_number"=>$carrierData['carrier_hazardous_waste_licence_number'],
				"vat_number"=>$carrierData['carrier_vat_number']
>>>>>>> 77a717f (Version 2)
);

$report->addData($data);
//$report->addData($wcData);
//$report->addData($customerAddressData);
$report->setJRXML("collection-basic-audit-report")->generate();
?>