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

$wc_ewc_identification_html = "<styled>
<table style=\"border:1px;\">
	<tr>
		<th style=\"width:220px;\"><b>Description of Waste</b></th>
		<th style=\"text-align:center; width:40px;\"><b>Quantity</b></th>
		<th style=\"text-align:center; width:135px;\"><b>Weight Per Item (kg)</b></th>
		<th style=\"text-align:center; width:120px;\"><b>Total Weight</b></th>			
		<th style=\"text-align:center; width:55px;\"><b>EWC Code</b></th>
	</tr>";
	$wcrItem = new WcrItem();
	$list = $wcrItem->getComplaintItemList($wc_id);
	$ItemTypeGroup = array();
	if($list)
	foreach($list as $item)
	{
		if(!in_array($item['wci_type_name'], $ItemTypeGroup))
		{
			$ItemTypeGroup[] = $item['wci_type_name'];
			$wc_ewc_identification_html.="<tr><td style=\"left:5px; width:570px; text-align:center; background-color:#CCC;\"><b>$item[wci_type_name]</b></td></tr>";
		}
		$item['wcr_item_qty']	= $item['wcr_item_qty']==0?"":$item['wcr_item_qty'];
		$item['wcr_item_weight']= $item['wcr_item_weight']==0?"":$item['wcr_item_weight'];
		$wc_ewc_identification_html.="<tr>
		<td style=\"left:5px; width:220px;\">$item[wci_name]</td>";
		if($item['wci_type_id']==3)
		$wc_ewc_identification_html.="<td style=\"text-align:center; left:5px; width:175px;\">$item[wcr_item_qty]</td>";
		else
		$wc_ewc_identification_html.="<td style=\"text-align:center; left:5px; width:40px;\">$item[wcr_item_qty]</td><td style=\"text-align:center; left:5px; width:135px;\">$item[wcr_item_weight]</td>";
		
		$wc_ewc_identification_html.="<td style=\"text-align:center; left:5px; width:120px;\">".($item['wcr_item_qty']==0?"":(intval($item['wcr_item_weight'])*intval($item['wcr_item_qty'])))."</td>
		<td style=\"text-align:center; left:5px; width:55px;\">$item[wci_ewc_code]</td>
		</tr>";
	
	}
$wc_ewc_identification_html.="</table></styled/>";
		
$info['wc_declaration_text'] = evalString($info['wc_declaration_text'], "wc_environment_permit", $info['wc_environment_permit'],"b");
$completion_date =  date("d/M/Y",strtotime($wcData['wc_completion_date']));
$departure_time  = 	date("h:i A",strtotime($wcData['wc_departure_time']));
$arrival_time  = 	date("h:i A",strtotime($wcData['wc_arrival_time']));		
$data = array(
			"wc_waste_removed_from" => $customerData['customer_name']."<br/>".$customerAddressData['full_address'].($customerData['customer_company']!=""?"<br/>Company: $customerData[customer_company]":""),
<<<<<<< HEAD
			"wc_waste_taken_to" => $info['wc_collection_address'],					 
			"wc_carrier_name" => $info['wc_collection_address'], 						
=======
			"wc_waste_taken_to" => $crrierData['carrier_address']?$crrierData['carrier_address'] :$info['wc_collection_address'],					 
			"wc_carrier_name" => $crrierData['carrier_name']? $crrierData['carrier_name']: $info['wc_collection_address'], 						
>>>>>>> 77a717f (Version 2)
			"wc_consignor_name" => $customerData['customer_name'],
			"wc_ewc_identification_html" => $wc_ewc_identification_html,
			"wc_on_behalf_of_user" => $wcData['wc_on_behalf_of_user'],
			"wc_consignment_note_code" => $wcData['wc_consignment_note_code'],
			"wc_collection_code_number" => $wcData['wc_code'],
			"wc_collection_manager_name" => $wcMgerData['user_name'],
			"wc_transferor_signature" => $wcData['wc_transferor_signature']!=""?$app->sitePath($wcData['wc_transferor_signature']):$app->sitePath(DEFAULT_TRANSFEROR_SIGNATURE_IMAGE),
			"wc_on_behalf_of_authority_name"=> substr($info['wc_collection_address'],0,strpos($info['wc_collection_address'],"Tel")),
			"driver_signature" => $wcDriverData['user_signature']!=""?$app->sitePath($wcDriverData['user_signature']):$app->sitePath(DEFAULT_SIGNATURE_IMAGE),
			"wc_on_behalf_of_authority_name"=> substr($info['wc_collection_address'],0,strpos($info['wc_collection_address'],"Tel")),
			"member_of_staff_name" => $customerData['customer_name'],
			"completion_date_time"=> $completion_date. " ".$departure_time,
			"completion_date" => $completion_date,
			"departure_time" => $departure_time,
			"arrival_time" => $arrival_time,
			"collection_date" => date("d/M/Y", strtotime($wcData['wc_due_date'])),
);

$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
$report	= new Report("Wastage Consignment $wcData[wc_code]",true);
$report->addData($data);
$report->addData($info);
$report->addData($crrierData);
if($CarrierVehicleData)
$report->addData($CarrierVehicleData);
else{
	$report->addData(array("vehicle_registration_number"=>$wcData['wc_drop_off_vehicle']));
}
if(!$wcDriverData)
{
	$report->addData(array("driver_name"=>$wcData['wc_drop_off_driver']));
}
$report->setJRXML("collection-wastage-consignment")->generate();
?>