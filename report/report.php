<?php include("setup.php"); ?>
<?php //echo "ggg";die;?>
<?php $app = new App();?>
<?php
if(!isset($_SESSION['APP_ACCESS']))
{
	//die("Error... !!! Permission denied.");
}
?>
<?php
set_time_limit(0);
error_reporting(0);
switch($_REQUEST['format']){	
	case "complaint-list" :
	{	
		$report			= new Report("Repair List",true);
		$complaint		= new Complaint();
		$report->query 	= $complaint->getReportComplaintListSql();
		$report->setJRXML("complaint-list")->generate();
		break;
	}
	case "employee-list" :
	{	
		$report			= new Report("Employee List",true);
		$emp			= new Employee();
		$report->query 	= $emp->getReportSql();
		$report->setJRXML("employee-list")->generate();
		break;
	}
	
	case "customer-list" :
	{
		$report			= new Report("Customer List",true);
		$cst			= new Customer();
		$report->query 	= $cst->getReportSql();
		$report->setJRXML("customer-list")->generate();
		break;
	}	
	case "complaint-detail" :
	{
		if(isset($_REQUEST['id']))
		{
			$cmt			= new Complaint($_REQUEST['id']);	
			$data 			= $cmt->loadInvoice();	
			$generatorSVG   = new Picqer\Barcode\BarcodeGeneratorPNG();			
			file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['complaint_ticket_number'], $generatorSVG::TYPE_CODE_128));
			$barcode		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
				
			$report			= new Report($cmt->get("complaint_ticket_number")." - Repair Request Detail");
			$report->query 	= $cmt->getReportComplaintDetials();		
			$jrxml = isset($_REQUEST['type'])?"complaint-detail-multi":"complaint-detail-single";
			$report->addData(array("barcode"=>$barcode));			
			$report->setJRXML($jrxml)->generate();
		}
		break;
		
	}
	case "collection-list" :
	{
		$report			= new Report("Collection / Drop Off / Pickup List",true);
		$collecion		= new Collection();
		$report->query 	= $collecion->getReportcollecionListSql();
		$report->setJRXML("collection-list")->generate();
		break;
	}	
	case "complaint-list" :
	{
		$report			= new Report("Repair Request List",true);
		$cmt			= new Complaint();
		$report->query 	= $cmt->getReportComplaintListSql();
		$report->setJRXML("complaint-list")->generate();
		break;
	}	
	case "complaint-invoice" :
	{
		if(isset($_REQUEST['id']))
		{			
			$complaint_id	= intval($_REQUEST['id']);
			$cmt			= new Complaint($complaint_id);
			$data 			= $cmt->loadInvoice();
			$data['description']	.=	"<br/>".$data['problem_list'];
			$data['quantity']		= 	max($data['quantity'],1);	
			$data['amount']			= 	number_format($data['amount'],2);
			$data['rate']			= 	$data['amount'];
			
			$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
			file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['complaint_ticket_number'], $generatorSVG::TYPE_CODE_128));
			$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
			
			$appInfo = 	new AppInfo();
			$info = $appInfo->getDetails();	
			$data['bank_detail']	= 	$info["info_app_bank_details"];
			$data['acknowledge'] 	= 	$info["info_app_invoice_acknowledge"];
			$data['telephone']		= 	$info["info_app_contact"];
			$data['happytext']		=	$info["info_app_invoice_happytext"];			
			$data['terms']			= 	$info["info_app_invoice_terms"];
			
			$complaitTax = new ComplaintTax($data['complaint_tax_id']);
			$taxData = $complaitTax->getDetails();

			$taxData['complaint_vat_tax']	= number_format($taxData['complaint_vat_tax'],2);
			$taxData['complaint_e_tax']		= number_format($taxData['complaint_e_tax'],2);
			$taxData['complaint_z_tax']		= number_format($taxData['complaint_z_tax'],2);
			$taxData['complaint_n_tax']		= number_format($taxData['complaint_n_tax'],2);
			$taxData['complaint_r_tax']		= number_format($taxData['complaint_r_tax'],2);
			$taxData['complaint_s_tax']		= number_format($taxData['complaint_s_tax'],2);

			$taxData['complaint_vat_tax_middle']	= number_format(($data['amount']*$taxData['complaint_vat_tax'])/100,2);
			$taxData['complaint_e_tax_middle']		= number_format(($data['amount']*$taxData['complaint_e_tax'])/100,2);
			$taxData['complaint_z_tax_middle']		= number_format(($data['amount']*$taxData['complaint_z_tax'])/100,2);
			$taxData['complaint_n_tax_middle']		= number_format(($data['amount']*$taxData['complaint_n_tax'])/100,2);
			$taxData['complaint_r_tax_middle']		= number_format(($data['amount']*$taxData['complaint_r_tax'])/100,2);
			$taxData['complaint_s_tax_middle']		= number_format(($data['amount']*$taxData['complaint_s_tax'])/100,2);
			
			$data['sub_total']			= number_format(($data['amount']+$taxData['complaint_vat_tax_middle']),2);
			$data['vat_total']			= number_format(($taxData['complaint_e_tax_middle']+$taxData['complaint_z_tax_middle']+$taxData['complaint_n_tax_middle']+$taxData['complaint_r_tax_middle']+$taxData['complaint_s_tax_middle']),2);
			
			$data['total']				= number_format(($data['amount'] +$taxData['complaint_vat_tax_middle']+ $data['vat_total']),2);	
			$report			= new Report("Complaint Invoice",true);	
			$report->setFilename("Repair Request Invoice ".$data["invoice_ticket"]);
			$report->addData($data);
			$report->addData($taxData);
			$report->setJRXML("complaint-invoice")->generate();
		}
		break;
	}
	case "complaint-print" :
	{
		if(isset($_REQUEST['id']))
		{			
			$complaint_id	= intval($_REQUEST['id']);
			$cmt			= new Complaint($complaint_id);
			$data 			= $cmt->loadPrint();
			$data['complaint_description']	.=	"<br/>".$data['problem_list'];
			
			$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
			file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['complaint_ticket_number'], $generatorSVG::TYPE_CODE_128));
			$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
			
			$appInfo = 	new AppInfo();
			$info = $appInfo->getDetails();	
			$data['info_app_address']		= 	$info["info_app_address"];
			$data['info_disclaimer']		=	nl2br($info["info_app_disclaimer"]);
			$data['print_date']				=	date('d M-Y');	
			$data['under_waranty']			= 	$data['under_waranty']? "Yes" : "No";
			$data['disk_provided']			= 	$data['disk_provided']? "Yes" : "No";
			$data['complaint_is_backup']	= 	$data['complaint_is_backup']? "Yes" : "No";
			
			$hardwareT = new HardwareType();
			$data['hardware_not_working']	=	$hardwareT->simplelistName(array_filter(explode(",",$data["complaint_product_hardware_not_working"])));	
						
			$report			= new Report("Complaint Print Details",true);	
			$report->setFilename("Complaint Print Details ".$data["invoice_ticket"]);
			$report->addData($data);
			$report->setJRXML("complaint-print")->generate();
		}
		break;
	}
	case "collection-cert" :
	{		
		$wc_id	= intval($_REQUEST['id']);
		$collection			= new Collection($wc_id);
		$wcData = $collection->getDetails();
		$customer = new Customer($wcData['wc_customer_id']);
		$customerData = $customer->getDetails();
		
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
		$barcode		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);	
		
		$collMgr = $collection->getCollectionManager($wcData['wc_manager_id']);
		
		$customerAddress = new CustomerAddress($wcData['wc_customer_address_id']);
		$customerAddressData = $customerAddress->getDetails();
		
		$company = new Company(10000001);
		$companyData = $company->getDetails();
		
		$appInfo = 	new AppInfo();
		$info = $appInfo->getDetails();	
				
		$company_details_data = $companyData['company_name']. ", ".$info['wc_collection_address']
			. "<br/>".str_replace("|", "<br/>", $companyData['company_contact'])
			. "<br/>"."Registered in England and Wales : ".$companyData['company_registered_in_england_and_wales']
			. "<br/>"."Environment Agency Registered Firm : ".$companyData['company_environment_permit_number']
			. "<br/>"."Waste Carrier's License : ".$companyData['company_hazardous_waste_licence_number']
			. "<br/>"."VAT No : ".$companyData['company_vat_registration_number'];
		
		$report			= new Report("Collection Certificate $wcData[wc_code]",true);
		$report->addData($collMgr);
		$report->addData(array("consigner_name"=>$customerData['customer_name'], "company_name"=>$customerData['customer_company'], "consigner_address"=>$customerAddressData['full_address'],"collection_wc_code"=>$wcData['wc_code'],"certificate_representative"=>"Gemma Worsell","collection_date"=>date("dS M Y", strtotime($wcData['wc_due_date'])), "barcode"=>$barcode, "company_details_data"=>$company_details_data));
		//$report->watermark	=	$app->sitePath("/img/spd_report_background.png");
		$report->watermark	=	$app->sitePath("/img/spd-collection-purge-certificate.png");
		$report->setJRXML("collection-certificate")->generate();
		break;
	}
	case "collection-hwcn" :
	{
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
		$crrierData['carrier_address'] = $info['wc_collection_address'];
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
		$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);	
		
		
		$report	= new Report("Hazard waste Collection $wcData[wc_code]",true);
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
		
		$report->setJRXML("collection-hazard-waste-note")->generate();
		break;
	}
	case "collection-wcnn" :
	{		
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
					"wc_waste_taken_to" => $info['wc_collection_address'],					 
					"wc_carrier_name" => $info['wc_collection_address'], 						
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
		break;
	}
	case "collection-docn" :
	{
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
		break;
	}	
	case "printassetcode" :
	{
		$code = isset($_GET['type']) ? $_GET['type'] : 0;
		$CollectionItems = CollectionProcess::getPrintProcessCode($_GET['id'], $code);
		$barcode = new Barcode();
		if($CollectionItems)
		{
			foreach($CollectionItems as $item)		
			$barcode->addAssetCode($item["code"], $item["title"], $item["subtitle"]);
		}
		$barcode->printBarcode();
		break;
	}
	case "sales-invoice" :
	{
		if(isset($_REQUEST['id']))
		{			
			$sales_invoice_id	= intval($_REQUEST['id']);
			$salesinvoice		= new SalesInvoice($sales_invoice_id);
			$data 				= $salesinvoice->load();
			
			$data['invoice_number'] = $data['sales_invoice_number'];
			$data['invoice_to'] = $data['customer_name']."<br/>Email: ".$data['customer_email']." | Mob: ".$data['customer_phone']. (($data['customer_company']!="")? "<br/>Company: $data[customer_company]":"");
			$data['ship_to'] 	=	$data['customer_full_address'];
			$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
			file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['sales_invoice_number'], $generatorSVG::TYPE_CODE_128));
			$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
			
			$invoice_type_name = $data['sales_invoice_is_estimate'] ? "Estimate" : "Invoice";			
			
			if($data['sales_invoice_is_paid'] == 1)
			$data['paidimage'] = $app->sitePath(INVOICE_PAID_IMAGE_PATH);
			
			$store = new Store($data['sales_invoice_store_id']);
			$storeData = $store->getDetails();
			
			if($data['sales_invoice_is_vat_applicable'] == 1)
			{
				$storeData['store_vat_percent'] = $storeData['store_vat_percent'] ? $storeData['store_vat_percent'] : SALES_VAT_PERCENTAGE;
				$storeData['store_is_vat_disabled'] = 0;
			}
			else
			{
				$storeData['store_vat_percent'] = 0;
				$storeData['store_is_vat_disabled'] = 1;
			}
			
			$appInfo = 	new AppInfo();
			$info = $appInfo->getDetails();	
			
			if(trim($storeData['store_bank_details']) != "")
				$data['bank_detail']	= 	nl2br($storeData["store_bank_details"]);
			else
				$data['bank_detail']	= 	nl2br($info["info_app_bank_details"]);			
			
			
			$data['acknowledge'] 		= 	$info["info_app_invoice_acknowledge"];
			$data['telephone']			= 	$info["info_app_contact"];
			$data['happytext']			=	$info["info_app_invoice_happytext"];			
			$data['terms']				= 	$info["info_app_invoice_terms"];
			$data['product_query']		= 	$salesinvoice->geInvoiceProductsQuery($storeData['store_vat_percent']);
			$data['invoice_type_name']	= 	$invoice_type_name;
			$data['invoice_date'] 		= 	date("d/m/Y", strtotime($data["sales_invoice_created_date"]));
			
		//echo $data['product_query'];die;
			$sumAmount = $salesinvoice->getInvoiceSum($storeData['store_vat_percent']);
			$data['product_sum_amount']	= "£ ".number_format($sumAmount['product_sum_amount'],2);
			$data['product_sum_vat']	= "£ ".number_format($sumAmount['product_sum_vat'],2);
			$data['product_sum_total']	= "£ ".number_format(($sumAmount['product_sum_vat'] + $sumAmount['product_sum_amount']),2);
			
			
			$report			= new Report("Sales $invoice_type_name",true);	
			$report->headerimage = $app->sitePath($storeData['store_logo']);
			$report->address = $storeData['store_address'];
			$report->info_app_contact = $storeData['store_contact'];
			$report->setFilename("Sales Invoice ".$data["sales_invoice_number"]);
			$report->addData($data);
			if($storeData['store_is_vat_disabled'] == 1 || $storeData['store_vat_percent'] == 0)			
				$report->setJRXML("sales-invoice-no-vat")->generate();
			else
				$report->setJRXML("sales-invoice")->generate();
			
		}
		break;
	}
	case "sales-invoice-memo" :
	{
		if(isset($_REQUEST['id']))
		{			
			$sales_invoice_id	= intval($_REQUEST['id']);
			$salesinvoice		= new SalesInvoice($sales_invoice_id);
			$data 				= $salesinvoice->load();
			$data['invoice_number'] = $data['sales_invoice_number'];
			$data['invoice_to'] = $data['customer_name']."<br/>Email: ".$data['customer_email']." | Mob: ".$data['customer_phone']. (($data['customer_company']!="")? "<br/>Company: $data[customer_company]":"");
			$data['ship_to'] 	=	$data['customer_full_address'];
			$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
			file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['sales_invoice_number'], $generatorSVG::TYPE_CODE_128));
			$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
			
			$invoice_type_name = $data['sales_invoice_is_estimate'] ? "Estimate" : "Invoice";			
			
			$data['paidimage'] = $app->sitePath(INVOICE_CANCEL_IMAGE_PATH);
			
			$store = new Store($data['sales_invoice_store_id']);
			$storeData = $store->getDetails();
			
			$appInfo = 	new AppInfo();
			$info = $appInfo->getDetails();	
			
			if(trim($storeData['store_bank_details']) != "")
				$data['bank_detail']	= 	nl2br($storeData["store_bank_details"]);
			else
				$data['bank_detail']	= 	nl2br($info["info_app_bank_details"]);			
			
			
			$data['acknowledge'] 		= 	$info["info_app_invoice_acknowledge"];
			$data['telephone']			= 	$info["info_app_contact"];
			$data['happytext']			=	$info["info_app_invoice_happytext"];			
			$data['terms']				= 	$info["info_app_invoice_terms"];
			$data['product_query']		= 	$salesinvoice->geInvoiceProductsQuery();
			$data['invoice_type_name']	= 	$invoice_type_name;
			$data['invoice_date'] 		= 	date("d/m/Y", strtotime($data["sales_invoice_created_date"]));
			
		//echo $data['product_query'];die;
			$sumAmount = $salesinvoice->getInvoiceSum();
			$data['product_sum_amount']	= "£ ".$sumAmount['product_sum_amount'];
			$data['product_sum_vat']	= "£ ".$sumAmount['product_sum_vat'];
			$data['product_sum_total']	= "£ ".number_format(($sumAmount['product_sum_vat'] + $sumAmount['product_sum_amount']),2);
			
			if($storeData['store_is_vat_disabled'] == 1)
			{
				$data['product_sum_amount']	= "£ ".($sumAmount['product_sum_amount'] + $sumAmount['product_sum_vat']);
				$data['product_sum_vat'] = "£ 0.00";
				$data['product_query']	= 	$salesinvoice->geInvoiceProductsQuery(0);
			}
			$data['sum_after_refund'] = "£ 0.00";
			$report			= new Report("Sales $invoice_type_name",true);	
			$report->headerimage = $app->sitePath($storeData['store_logo']);
			$report->address = $storeData['store_address'];
			$report->info_app_contact = $storeData['store_contact'];
			$report->setFilename("Sales Invoice memo ".$data["sales_invoice_number"]);
			$report->addData($data);
			$report->setJRXML("sales-invoice-memo")->generate();
			
		}
		break;
	}
	case "sales-invoice-list" :
	{
		$report			= new Report("Sales Invoice List",true);
		$SalesInvoice	= new SalesInvoice();
		$report->query 	= $SalesInvoice->getReportSql();
		$report->setJRXML("sales-invoice-list")->generate();
		break;
	}
	case "pro-slip" :
	{
		$report			= new Report("Product Multi Slip",true);
		$Product	= new Product(isset($_REQUEST['id'])?$_REQUEST['id']:0);
		$data  = $Product->load();
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['product_id'], $generatorSVG::TYPE_CODE_128));
		$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
		$report->query 	= $Product->getReportSqlSlip();
		$report->addData($data);
		$report->setJRXML("product-slip-print")->generate();
		break;
	}
	case "pro-slip-single" :
	{
		$report			= new Report("Product Single Slip",true);
		$Product	= new Product(isset($_REQUEST['id'])?$_REQUEST['id']:0);
		$data  = $Product->load();
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['product_id'], $generatorSVG::TYPE_CODE_128));
		$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
		$report->query 	= $Product->getReportSqlSlip(1);
		$report->addData($data);
		$report->setJRXML("product-single-label-print")->generate();
		break;
	}
	case "collection-audit-report" :
	{
		$wc_id	= intval($_REQUEST['id']);
		$appInfo = 	new AppInfo();
		$info = $appInfo->getDetails();
		
		$collection			= new Collection($wc_id);
		$wcData = $collection->getDetails();
		
		$CarrierVehicle = new CarrierVehicle($wcData['wc_vehicle_id']);
		$CarrierVehicleData = $CarrierVehicle->getDetails();
		
		$customer = new Customer($wcData['wc_customer_id']);
		$customerData = $customer->getDetails();		
		
		$customerAddress = new CustomerAddress($wcData['wc_customer_address_id']);
		$customerAddressData = $customerAddress->getDetails();
		
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
		$data['wc_collection_code_number'] = $wcData['wc_code'];
		
		$report			= new Report(" Basic Audit Report $wcData[wc_code]",true);
		$report->query 	= CollectionProcess::getReportSqlBasicAuditReport($wc_id);
		$report->address = $info['wc_collection_address'];
		$data = array(
						"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
						"wc_waste_removed_from" => $customerData['customer_name']."<br/>".$customerAddressData['full_address'],
						"wc_waste_taken_to" => $info['wc_collection_address'],					 
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
		break;
	}
	case "collection-basic-audit-report" :
	{
		$wc_id	= intval($_REQUEST['id']);
		$appInfo = 	new AppInfo();
		$info = $appInfo->getDetails();
		
		$collection			= new Collection($wc_id);
		$wcData = $collection->getDetails();		
		
		$customer = new Customer($wcData['wc_customer_id']);
		$customerData = $customer->getDetails();		
		
		$customerAddress = new CustomerAddress($wcData['wc_customer_address_id']);
		$customerAddressData = $customerAddress->getDetails();
		
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
		$data['wc_collection_code_number'] = $wcData['wc_code'];
		
		$report			= new Report(" Destruction Report $wcData[wc_code]",true);
		$report->heading= " Destruction Report ";
		$report->query 	= $collection->getReportSqlBasicAuditReport();
		$report->address = $info['wc_collection_address'];
		$data = array(
						"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
						"wc_waste_removed_from" => $customerData['customer_name']."<br/>".$customerAddressData['full_address'],						
						"customer_company" => $customerData['customer_company'],
						"customer_name" => $customerData['customer_name'],
						"wc_consignment_note_code" => $wcData['wc_consignment_note_code'],
						"wc_collection_code_number" => $wcData['wc_code'],
						"wc_collection_date" => date("d/m/Y",strtotime($wcData['wc_due_date'])),
						"customer_address" => $customerAddressData['full_address'],
						"reportfootertext" => "Safe PC Disposal is the trading name of E World UK Ltd. Registered in England and Wales 05983190 ".$info['wc_collection_address']
		);
		
		$report->addData($data);
		//$report->addData($wcData);
		//$report->addData($customerAddressData);
		$report->setJRXML("collection-basic-audit-report")->generate();
		break;
	}
	case "pay-slip" :
	{
		$pay_slip_id	= intval($_GET['id']);
		$SalaryRegister = 	new SalaryRegister($pay_slip_id);
		$info = $SalaryRegister->getDetails();
		if($info)
		{
			$month_name = date("M-Y", strtotime("20".substr($info['pay_slip_month_id'],0,2)."-".substr($info['pay_slip_month_id'],2,2)."-01"));
					
			$employee	= new Employee($info['pay_slip_user_id']);
			$empData = $employee->getDetails();	
			
			$contract = new Contract();
			$contractData = $contract->getDetailsByUser($info['pay_slip_user_id']);	
			
			$storeOrg = new Store($contractData['user_contract_store']);
			$storeOrgData = $storeOrg->getDetails();
			$accMgr = new Employee(ACCOUNT_MANAGER);
			$mgrData = $accMgr->getDetails();
							
			$report			= new Report("Pay Slip $month_name",true);
			$report->headerimage = $app->sitePath($storeOrgData['store_logo']);
			$report->address = $storeOrgData['store_address'];
			$data = array(
							"company_name" => $storeOrgData['store_official_name'],
							"pay_slip_date" => $month_name,					 
							"pay_slip_heading" => "$empData[user_name] Pay Slip for $month_name", 						
							"account_manager" => $mgrData['user_name'],
							"account_signature" => $app->sitePath($mgrData['user_signature']),
							"employee_name" => $empData['user_name'],
							"employee_designation" => $empData['user_type_name'],
							"joining_date" => date("M d Y", strtotime($contractData['user_pay_joining_date'])),
							"basic_salary" => $info['pay_slip_basic_salary'],
							"commission_rate" => $info['pay_slip_commision'],
							"commission_amount" => round(($info['pay_slip_total_sale']*$info['pay_slip_commision'])/100),
							"leaves" => $info['pay_slip_leave_taken'],
							"net_salary" => $info['pay_slip_grant_pay'],
							"total_sale" => $info['pay_slip_total_sale'],
							"paidimage" => $app->sitePath($info['pay_slip_paid_status']==PAY_STATUS_PAID ? INVOICE_PAID_IMAGE_PATH : INVOICE_UNPAID_IMAGE_PATH),
							"pay_mode" => $info['pay_slip_pay_mode'],
							"pay_status" => $info['pay_slip_paid_status'],
							"pay_remark" => $info['pay_slip_payment_reference'],
							"pay_date" => date("M d Y")
			);
			//print_r($data);die;
			
			$report->watermark	=	$app->sitePath("/img/employee_appointment_".$storeOrgData['store_key'].".jpg");		
			$report->addData($data);
			$report->setJRXML("employee-pay-slip")->generate();
		}
		break;
	}
	case "appointment" :
	{
		$report			= new Report("Appointment ",true);
		$employee	= new Employee(intval($_REQUEST['id']));
		$employeeRecord  = $employee->getDetails();
		
		$contract = new Contract();
		if($contractRecord = $contract->getDetailsByUser($employeeRecord['user_id'])){
			$storeOrg = new Store($contractRecord['user_contract_store']);
			$storeOrgData = $storeOrg->getDetails();
			$dataArray = array(
				"issue_date" => date("d M-Y", strtotime($contractRecord['user_appointment_issue_date'])),
				"employee_name" => $employeeRecord['user_name'],
				"employee_position" => $employeeRecord['user_type_name'],
				"father_name" => $contractRecord['user_father_name'],
				"employee_cnic_number" => $contractRecord['user_cnic_number'],
				"department_name" => $contractRecord['user_department_name'],
				"joining_date" => date("d M-Y", strtotime($contractRecord['user_pay_joining_date'])),
				"currency_code" => $contractRecord['user_payment_currency'],
				"employee_salary" => $contractRecord['user_pay_salary'],
				"reporting_person" => $contractRecord['user_reporting_person'],
				"working_time" => $contractRecord['user_working_time'],
				"working_days" => $contractRecord['user_pay_working_days'],
				"pay_shedule" => strtolower($contractRecord['user_pay_salary_invoicing']),
				"employee_org_name"=>$storeOrgData['store_official_name']
			);
			
			$appointment_text = App::readTemplate("employee_appointment", $dataArray);			
			$data = array("appointment_text" => $appointment_text);
			$report->addData($data);
			$report->watermark	=	$app->sitePath("/img/employee_appointment_".$storeOrgData['store_key'].".jpg");
			$report->setJRXML("employee-appointment")->generate();
		}
		break;
	}
	case "printsoftassets" :
	{
		$SoftwareAssets	= new SoftwareAssets(intval($_REQUEST['id']));
		if($SoftwareAssets->isExist()){
			$carrier = new Carrier(1);
			$carrierData = $carrier->getDetails();
			$report	= new Report("Software Assets ",true);
			$data = array(
				"soft_asset_id" => intval($_REQUEST['id'])
			);
			$report->addData(array_merge($data, $carrierData));
			$report->setJRXML("print-software-assets")->generate();
		}
		break;
	}
	case "printphycassets" :
	{
		$PhysicalAsset	= new PhysicalAsset(intval($_REQUEST['id']));
		if($PhysicalAsset->isExist()){
			$phycData = $PhysicalAsset->getDetails();
			$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
			file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($phycData['asset_tag'], $generatorSVG::TYPE_CODE_128));
			$carrier = new Carrier(1);
			$carrierData = $carrier->getDetails();
			$report	= new Report("Physical Assets $phycData[asset_tag]",true);
			$data = array(
				"asset_id" => intval($_REQUEST['id']),
				"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
			);
			$report->addData(array_merge($data, $carrierData));
			$report->setJRXML("print-physical-assets")->generate();
		}
		break;
	}
	case "printcontractinsurance" :
	{
		$ContractInsurance	= new ContractInsurance(intval($_REQUEST['id']));
		if($ContractInsurance->isExist()){
			$carrier = new Carrier(1);
			$carrierData = $carrier->getDetails();
			$report	= new Report("Software Assets ",true);
			$data = array(
				"cont_ins_id" => intval($_REQUEST['id'])
			);
			$report->addData(array_merge($data, $carrierData));
			$report->setJRXML("print-contract-insurance")->generate();
		}
		break;
	}
	case "printutilities" :
	{
		$Utilities	= new Utilities(intval($_REQUEST['id']));
		if($Utilities->isExist()){
			$carrier = new Carrier(1);
			$carrierData = $carrier->getDetails();
			$report	= new Report("Utilities document",true);
			$data = array(
				"utility_id" => intval($_REQUEST['id'])
			);
			$report->addData(array_merge($data, $carrierData));
			$report->setJRXML("print-utilities")->generate();
		}
		break;
	}
	case "printequipmentdisposal" :
	{
		$DestructionMethod	= new DestructionMethod(intval($_REQUEST['id']));
		if($DestructionMethod->isExist()){
			$DestructionMethodData = $DestructionMethod->getDetails();
			$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
			file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($DestructionMethodData['eqipment_disposal_log_no'], $generatorSVG::TYPE_CODE_128));
			$carrier = new Carrier(1);
			$carrierData = $carrier->getDetails();
			$report	= new Report("Equiment Disposal $DestructionMethodData[eqipment_disposal_log_no]",true);
			$data = array(
				"eqipment_disposal_id" => intval($_REQUEST['id']),
				"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
			);
			$report->addData(array_merge($data, $carrierData));
			$report->setJRXML("print-equipment-disposal")->generate();
		}
		break;
	}
	case "printrfc" :
	{
		$Rfc	= new Rfc(intval($_REQUEST['id']));
		if($Rfc->isExist()){
			$RfcData = $Rfc->getDetails();
			$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
			file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($RfcData['rfc_code'], $generatorSVG::TYPE_CODE_128));
			$carrier = new Carrier(1);
			$carrierData = $carrier->getDetails();
			$report	= new Report("RFC $RfcData[rfc_code]",true);
			$data = array(
				"rfc_id" => intval($_REQUEST['id']),
				"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
			);
			$report->addData(array_merge($data, $carrierData));
			$report->setJRXML("print-rfc")->generate();
		}
		break;
	}
	case "printcompany" :
	{
		$company	= new Company(intval($_REQUEST['id']));
		if($company->isExist()){
			$companyData = $company->getDetails();			
			$report	= new Report("Company $companyData[company_name]",true);
			$companyData['company_bank_details'] = nl2br($companyData['company_bank_details']);
			$companyData['company_trademark'] = BP.$companyData['company_trademark'];
			
			$report->addData($companyData);
			$report->setJRXML("print-company")->generate();
		}
		break;
	}
	case "printsupplier" :
	{
		$supplier_id = intval($_REQUEST['id']);
		$supplier	= new Supplier($supplier_id);
		if($supplier->isExist()){
			$supplierData = $supplier->getDetails();			
			$report	= new Report("Supplier $supplierData[supplier_name]", true);			
			$report->addData(array("supplier_id"=>$supplier_id));
			$report->setJRXML("print-supplier")->generate();
		}
		break;
	}
	case "printsupplierlist" :
	{
		$supplier	= new Supplier();			
		$report	= new Report("Supplier List", true);		
		$report->setJRXML("print-supplier-list")->generate();		
		break;
	}	
	case "exportpallet" :
	{
		$pallet_id = intval($_REQUEST['id']);
		$PalletItems	= new PalletItems($pallet_id);
		$PalletItems->getItemsExcelExport();		
		break;
	}
	case "empcontractlist" :
	{
			$employee	= new ContractEmployee();		
			$report	= new Report("Contract employee list", true);
			$report->query 	= $employee->getReportSql();
			$report->setJRXML("contract-employee-contract-list")->generate();		
		break;
	}
	case "empcontract" :
	{
		$emp_id = intval($_REQUEST['id']);
		$ContEmp	= new ContractEmployee($emp_id);
		$empData = $ContEmp->getDetails();
		$empData['employee_dob'] = date('d M, Y', strtotime($empData['employee_dob']));
		$empData['employee_contract_date'] = date('d M, Y', strtotime($empData['employee_contract_date']));
		$empData['employee_employment_date'] = date('d M, Y', strtotime($empData['employee_employment_date']));
		
		$store = new Store($empData['employee_contract_store']);
		$storeData = $store->getDetails();
		
		$employer = new Employee(18); // 18 farhan Id
		$emplyerData = $employer->getDetails();
		
		$empData['application_address'] = $storeData['store_address'];
		$empData['employee_job_location'] = preg_replace('/\s+/', ' ', trim($storeData['store_address']));
		$empData['employee_org_name'] = $storeData['store_official_name'];
		
		$html = nl2br(trim(getTemplateView($empData['employee_is_zero_hour_contract'] ? 'template/report/contract_employee_contract_zero_hour.txt' :  'template/report/contract_employee_contract.txt', $empData)));
		$report	= new Report("Employee Contract", true);
		$report->addData(array(
			"html"=>$html, 
			"contract_date" => $empData['employee_contract_date'],
			"employee_org_name" => $storeData['store_official_name'],
			"employee_signature"=>$empData['employee_signature'] != "" ? $app->sitePath($empData['employee_signature']) : $app->sitePath(DEFAULT_SIGNATURE_IMAGE),
			"employer_signature"=>$app->sitePath($emplyerData['user_signature'])
		));
		
		$report->setJRXML("contract-employee-contract");
		$report->generate();
		break;
	}
	case "emptraininglist" :
	{
			$training	= new Training();		
			$report	= new Report("Employee training list", true);
			$report->query 	= $training->getReportSql();
			$report->setJRXML("employee-training-list")->generate();		
		break;
	}
	case "emptraining" :
	{
		$training_id = intval($_REQUEST['id']);
		$ContEmp	= new Training($training_id);
		$empData = $ContEmp->getDetails();
		$report	= new Report("Employee Contract", true);
		$report->addData($empData);
		$report->setJRXML("employee-training")->generate();	
		break;
	}
	case "empinduction" :
	{
		$eic_id = intval($_REQUEST['id']);
		$eic	= new EmployeeInduction($eic_id);
		$eicData = $eic->getDetails();
		$report	= new Report("Employee induction", true);
		$eicData['emp_sign_date'] = date('d/M/Y', strtotime($eicData['eic_employee_sign_date']));
		$eicData['hr_sign_date'] = date('d/M/Y', strtotime($eicData['eic_hr_signature_date']));
		$eicData['emp_signature'] = $app->sitePath($eicData['eic_employee_signature']);
		$eicData['hr_signature'] = $app->sitePath($eicData['eic_hr_signature']);
		$eicData['relevant_information'] = $eicData['eic_relevant_issue'];
		$eicData['emp_name'] = strtoupper($eicData['eic_employee_name']);
		$eicData['html'] = $eic->getItemsReportHtml();
		$report->addData($eicData);
		
		$report->setJRXML("emp-induction-document")->generate();	
		break;
	}
	case "empleaver" :
	{
		$eil_id = intval($_REQUEST['id']);
		$eil	= new EmployeeLeaver($eil_id);
		$eilData = $eil->getDetails();
		$report	= new Report("Employee induction", true);
		$eilData['emp_sign_date'] = date('d/M/Y', strtotime($eilData['eil_employee_sign_date']));
		$eilData['hr_sign_date'] = date('d/M/Y', strtotime($eilData['eil_hr_signature_date']));
		$eilData['emp_signature'] = $app->sitePath($eilData['eil_employee_signature']);
		$eilData['hr_signature'] = $app->sitePath($eilData['eil_hr_signature']);
		$eilData['relevant_information'] = $eilData['eil_relevant_issue'];
		$eilData['emp_name'] = strtoupper($eilData['eil_employee_name']);
		$eilData['html'] = $eil->getItemsReportHtml();
		$report->addData($eilData);
		
		$report->setJRXML("emp-leaver-document")->generate();	
		break;
	}
	case "batchproductcode" :
	{
		$batchProduct = new BatchProduct($_REQUEST['id']);
		if($item = $batchProduct->getDetails())
		{	
			$barcode = new Barcode();
			$barcode->addAssetCode($item['product_code'], $item['product_type']." / ".$item['product_processor']." / ".$item['product_model']." / ".$item['product_screen_size']."\"", $item['product_serial_number']." / ".$item['product_ram']." / ".($item['product_ssd'] ? $item['product_ssd']."-SSD" : $item['product_hdd']."-HDD"));
			$barcode->printBarcode();
		}
		break;
	}
	case "batchproductbarcode" :
	{
		if(isset($_SESSION['BATCH-PROD']['PRINT'][$_REQUEST['id']])){
			$batchProductsItems = $_SESSION['BATCH-PROD']['PRINT'][$_REQUEST['id']];
			$barcode = new Barcode();
			if(count($batchProductsItems))
			{
				foreach($batchProductsItems as $item)		
				$barcode->addAssetCode($item['product_code'], $item['product_type']." / ".$item['product_processor']." / ".$item['product_model']." / ".$item['product_screen_size']."\"", $item['product_serial_number']." / ".$item['product_ram']." / ".($item['product_ssd'] ? $item['product_ssd']."-SSD" : $item['product_hdd']."-HDD"));
			}
			$barcode->printBarcode();
		}
		break;
	}
	case "collectionprocessexport" :
	{
		if(isset($_SESSION['COLL-PROD']['EXPORT'][$_REQUEST['id']])){
			$filename = "product_collection_" . date('Y-m-d-H-i-s') . ".csv";
			$batchProductsItems = $_SESSION['COLL-PROD']['EXPORT'][$_REQUEST['id']];
			$f = fopen('php://memory', 'w');
			$delimiter = ",";
			if(count($batchProductsItems))
			{
				$counter = 0;
				foreach($batchProductsItems as $item){	
					if($counter++ == 0)	{
						$fields = array_keys($item);
						fputcsv($f, $fields, $delimiter);
					}
					fputcsv($f, array_values($item), $delimiter);
				}
			}
			fseek($f, 0);
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '";');
			fpassthru($f);
		}
		break;
	}
	default:
	{
		$report			= new Report("Default 404",true);
		$report->setJRXML("default")->generate();
		break;
	}
}
?>