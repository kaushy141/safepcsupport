<?php
$appInfo = 	new AppInfo();
$info = $appInfo->getDetails();	
$wc_id	= intval($_REQUEST['id']);
$collection			= new Collection($wc_id);
$wcData = $collection->getDetails();

$CarrierVehicle = new CarrierVehicle($wcData['wc_vehicle_id']);
$CarrierVehicleData = $CarrierVehicle->getDetails();

$customer = new Customer($wcData['wc_customer_id']);
$customerData = $customer->getDetails();

$carrier = new Carrier($wcData['wc_carrier_id']);
$crrierData	= $carrier->getDetails();

$collMgr = new Employee($wcData['wc_manager_id']);
$wcMgerData	= $collMgr->getDetails();		

$colDriver = new Employee($wcData['wc_driver_id']);
$wcDriverData	= $colDriver->getDetails();	

$customerAddress = new CustomerAddress($wcData['wc_customer_address_id']);
$customerAddressData = $customerAddress->getDetails();		


$wcCodePrintArray = array();
$wcrItem = new WcrItem();
$list = $wcrItem->getComplaintItemList($wc_id);
		
$wc_ewc_item_html_array=array();
foreach($list as $item)
$wc_ewc_item_html_array[]=$item['wci_name'];
$wc_ewc_identification_html = limitText("IT Equipment(s) - ".implode(", ", $wc_ewc_item_html_array),200);
		
$info['wc_declaration_text'] = evalString($info['wc_declaration_text'], "wc_environment_permit", $info['wc_environment_permit'],"b");
$completion_date =  date("d/M/Y",strtotime($wcData['wc_completion_date']));
$departure_time  = 	date("h:i A",strtotime($wcData['wc_departure_time']));
$arrival_time  = 	date("h:i A",strtotime($wcData['wc_arrival_time']));				
$data = array(
			"wc_waste_removed_from" => $customerData['customer_name']."<br/>".$customerAddressData['full_address'].($customerData['customer_company']!=""?"<br/>Company: $customerData[customer_company]":""),
			"wc_waste_taken_to" => $info['wc_collection_address'],					 
			"wc_carrier_name" => $info['wc_collection_address'], 						
			"wc_consignor_name" => $customerData['customer_name'],
			"wc_ewc_identification_html" => $wc_ewc_identification_html,
			"wc_on_behalf_of_user" => $wcData['wc_on_behalf_of_user'],
			"wc_consignment_note_code" => $wcData['wc_consignment_note_code'],
			"wc_collection_code_number" => $wcData['wc_code'],
			"wc_carrier_name" => $info['wc_collection_address'],
			"wc_collection_manager_name" => $wcMgerData['user_name'],
			"wc_transferor_signature" => $wcData['wc_transferor_signature']!=""?$app->sitePath($wcData['wc_transferor_signature']):$app->sitePath(DEFAULT_SIGNATURE_IMAGE),
			"wc_on_behalf_of_authority_name"=> substr($info['wc_collection_address'],0,strpos($info['wc_collection_address'],"Tel")),
			"driver_name" => $wcDriverData['user_name'],
			"completion_date_time"=> $completion_date. " ".$departure_time,
			"completion_date" => $completion_date,
			"departure_time" => $departure_time,
			"arrival_time" => $arrival_time,
			"collection_date" => date("d/M/Y", strtotime($wcData['wc_due_date'])),
);

$crrierData['carrier_address'] = $info['wc_collection_address'];
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);

$report	= new Report("Duty of Care $wcData[wc_code]",true);
$report->addData($data);
$report->addData($info);
$report->addData($crrierData);
if($CarrierVehicleData)
$report->addData($CarrierVehicleData);
$report->setJRXML("collection-duty-of-care-note")->generate();
?>