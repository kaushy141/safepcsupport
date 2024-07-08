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
<<<<<<< HEAD
$crrierData	= $carrier->getDetails();
=======
$carrierData	= $carrier->getDetails();
>>>>>>> 77a717f (Version 2)

$collMgr = new Employee($wcData['wc_manager_id']);
$wcMgerData	= $collMgr->getDetails();	

$colDriver = new Employee($wcData['wc_driver_id']);
$wcDriverData	= $colDriver->getDetails();	

$customerAddress = new CustomerAddress($wcData['wc_customer_address_id']);
$customerAddressData = $customerAddress->getDetails();		

$wc_ewc_identification_html = "<styled>
<table style=\"border:1px;\">
	<tr>
		<th style=\"width:100px\"><b>Description of Waste</b></th>
		<th style=\"text-align:center; width:45px;\"><b>EWC Code</b></th>
		<th style=\"text-align:center; width:20px;\"><b>Qty</b></th>
		<th style=\"text-align:center; width:100px;\"><b>Chemical Comp.</b></th>
		<th style=\"text-align:center; width:100px;\"><b>Concentration</b></th>
		<th style=\"text-align:center; width:40px;\"><b>Ph.Form</b></th>
		<th style=\"text-align:center; width:125px;\"><b>Hazard Code</b></th>
		<th style=\"text-align:center; width:42px;\"><b>Container</b></th>
	</tr>";
	$wcrItem = new WcrItem();
	$unique_ewc_code_array = array();
	$list = $wcrItem->getComplaintItemList($wc_id);
	if($list)
	foreach($list as $item)
	{
		if($item['wci_type_id'] == 1)
		{
			if(!in_array($item['wci_ewc_code'], $unique_ewc_code_array))
			$unique_ewc_code_array[] = $item['wci_ewc_code'];
			$wc_ewc_identification_html.="<tr>
			<td style=\"left:5px; width:100px;\">$item[wci_name]</td>
			<td style=\"text-align:center; left:5px; width:45px;\">$item[wci_ewc_code]</td>
			<td style=\"text-align:center; left:5px; width:20px;\">".($item['wcr_item_qty']==0?"":$item['wcr_item_qty'])."</td>
			<td style=\"text-align:center; left:5px; width:100px;\">$item[wci_chemical_component]</td>
			<td style=\"text-align:center; left:5px; width:100px;\">$item[wci_concentration]</td>
			<td style=\"text-align:center; left:5px; width:40px;\">$item[wci_physical_form]</td>
			<td style=\"text-align:center; left:5px; width:125px;\">$item[wci_hazard_codes]</td>
			<td style=\"text-align:center; left:5px; width:42px;\">$item[wci_container_type]</td>
		</tr>";
		}
	}
	
$wc_ewc_identification_html.="</table>
<b>[The Information given below is to be completed for each EWC identified]</b><br/>
<table style=\"border:1px\">
	<tr>
		<th style=\"text-align:center;\"><b>EWC Code</b></th>
		<th style=\"text-align:center;\"><b>UN Identification No.</b></th>
		<th style=\"text-align:center;\"><b>Proper Shipping Name</b></th>
		<th style=\"text-align:center;\"><b>UN Class</b></th>
		<th style=\"text-align:center;\"><b>Special Handelling</b></th>
	</tr>";
if(is_array($unique_ewc_code_array)&& count($unique_ewc_code_array)>0){
	foreach($unique_ewc_code_array as $wci_ewc_code)
	{				
		$wc_ewc_identification_html.="<tr>
			<td style=\"text-align:center;\">$wci_ewc_code</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>";
	}
}
$wc_ewc_identification_html.="</table></styled/>";
		
<<<<<<< HEAD
$info['wc_declaration_text'] = evalString($info['wc_declaration_text'], "wc_environment_permit", $info['wc_environment_permit'],"b");
=======
$info['wc_declaration_text'] = evalString($info['wc_declaration_text'], "wc_environment_permit", $carrierData['carrier_environment_number'] ? $carrierData['carrier_environment_number'] : $info['wc_environment_permit'],"b");
>>>>>>> 77a717f (Version 2)
$completion_date =  date("d/M/Y",strtotime($wcData['wc_completion_date']));
$departure_time  = 	date("h:i A",strtotime($wcData['wc_departure_time']));
$arrival_time  = 	date("h:i A",strtotime($wcData['wc_arrival_time']));
$data = array(
				"wc_waste_removed_from" => $customerData['customer_name']."<br/>".$customerAddressData['full_address'].($customerData['customer_company']!=""?"<br/>Company: $customerData[customer_company]":""),
<<<<<<< HEAD
				"wc_waste_taken_to" => $info['wc_collection_address'],					 
=======
				"wc_waste_taken_to" => $carrierData['carrier_address'] ? $carrierData['carrier_address'] :  $info['wc_collection_address'],					 
>>>>>>> 77a717f (Version 2)
				"wc_carrier_name" => $info['wc_collection_address'], 						
				"wc_consignor_name" => $customerData['customer_name'],
				"wc_ewc_identification_html" => $wc_ewc_identification_html,
				"wc_on_behalf_of_user" => $wcData['wc_on_behalf_of_user'],
				"wc_consignment_note_code" => $wcData['wc_consignment_note_code'],
				"wc_collection_code_number" => $wcData['wc_code'],
				"wc_collection_manager_name" => $wcMgerData['user_name'],
				"wc_collection_manager_signature" => $wcMgerData['user_signature']!=""?$app->sitePath($wcMgerData['user_signature']):$app->sitePath(DEFAULT_SIGNATURE_IMAGE),
				"wc_on_behalf_of_authority_name"=> substr($info['wc_collection_address'],0,strpos($info['wc_collection_address'],"Tel")),
				"driver_name" => $wcDriverData['user_name'],
				"completion_date_time"=> $completion_date. " ".$departure_time,
				"completion_date" => $completion_date,
				"departure_time" => $departure_time,
				"arrival_time" => $arrival_time,
				"collection_date" => date("d/M/Y", strtotime($wcData['wc_due_date'])),
				"driver_signature" => $wcDriverData['user_signature']!=""?$app->sitePath($wcDriverData['user_signature']):$app->sitePath(DEFAULT_SIGNATURE_IMAGE),
				"consigner_signature" => $wcData['wc_transferor_signature']!=""?$app->sitePath($wcData['wc_transferor_signature']):$app->sitePath(DEFAULT_TRANSFEROR_SIGNATURE_IMAGE),
);
<<<<<<< HEAD
$crrierData['carrier_address'] = $info['wc_collection_address'];
=======
$carrierData['carrier_address'] = $carrierData['carrier_address']?$carrierData['carrier_address'] : $info['wc_collection_address'];
>>>>>>> 77a717f (Version 2)
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);	


$report	= new Report("Hazard waste Collection $wcData[wc_code]",true);
$report->addData($data);
$report->addData($info);
<<<<<<< HEAD
$report->addData($crrierData);
=======
$report->addData($carrierData);
>>>>>>> 77a717f (Version 2)
if($CarrierVehicleData)
$report->addData($CarrierVehicleData);
else{
	$report->addData(array("vehicle_registration_number"=>$wcData['wc_drop_off_vehicle']));
}

if(!$wcDriverData)
{
	$report->addData(array("driver_name"=>$wcData['wc_drop_off_driver']));
}

$report->setJRXML("collection-hazard-waste-note")->generate();
?>