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
$CarrierVehicle = new CarrierVehicle($wcData['wc_vehicle_id']);
$CarrierVehicleData = $CarrierVehicle->getDetails();

$customer = new Customer($wcData['wc_customer_id']);
$customerData = $customer->getDetails();		

$customerAddress = new CustomerAddress($wcData['wc_customer_address_id']);
$customerAddressData = $customerAddress->getDetails();

$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
$data['wc_collection_code_number'] = $wcData['wc_code'];

$report			= new Report("Advanced Audit Report $wcData[wc_code]",true);
$report->heading= "Advanced Audit Report $wcData[wc_code]";
$report->query 	= CollectionProcess::getReportSqlBasicAuditReport($wc_id);
<<<<<<< HEAD
$report->address = $info['wc_collection_address'];
$data = array(
				"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
				"wc_waste_removed_from" => $customerData['customer_name']."<br/>".$customerAddressData['full_address'],
				"wc_waste_taken_to" => $info['wc_collection_address'],					 
=======
$report->address = $carrierData['carrier_address'] ? $carrierData['carrier_address'] : $info['wc_collection_address'];
$data = array(
				"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
				"wc_waste_removed_from" => $customerData['customer_name']."<br/>".$customerAddressData['full_address'],
				"wc_waste_taken_to" => $carrierData['carrier_address'] ? $carrierData['carrier_address'] : $info['wc_collection_address'],					 
>>>>>>> 77a717f (Version 2)
				"wc_carrier_name" => $info['wc_collection_address'], 						
				"wc_consignor_name" => $customerData['customer_name'],
				"wc_consignment_note_code" => $wcData['wc_consignment_note_code'],
				"wc_collection_code_number" => $wcData['wc_code'],
				"wc_carrier_name" => $info['wc_collection_address']
);

$report->addData($data);
$report->addData($wcData);
$report->addData($customerAddressData);
$report->setJRXML("collection-audit-report")->generate();
?>