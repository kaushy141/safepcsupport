<?php
//die;
	
	$wci_item_make = $wci_item_model =$wci_item_name = $wci_item_qty = $wci_item_srno =$wci_item_weight = $wci_aged_box  = $wci_aged_box_data = $wci_core_id = $wc_item_process_asset_code = $wci_core_id = array();
	
	$wc_mail_hwcn_to_customer = $wc_mail_wcnn_to_customer = $wc_mail_docn_to_customer = 0;
	$wc_id = 0;
	//$_POST = getRealPOST();
	$data  = sanitizePostData($_POST);  
    extract($data);
	
	$wci_item_make = $_POST['wci_item_make'];
	$wci_item_model = $_POST['wci_item_model'];
	$wci_item_name = $_POST['wci_item_name'];
	$wci_item_qty = $_POST['wci_item_qty'];
	$wci_item_srno = $_POST['wci_item_srno'];
	$wci_item_weight = $_POST['wci_item_weight'];
	$wci_aged_box  = $_POST['wci_aged_box'];
	$wci_aged_box_data = $_POST['wci_aged_box_data'];
	$wci_core_id = $_POST['wci_core_id'];
	$wc_mail_hwcn_to_customer = $_POST['wc_mail_hwcn_to_customer'];
	$wc_mail_wcnn_to_customer = $_POST['wc_mail_wcnn_to_customer'];
	$wc_mail_docn_to_customer = $_POST['wc_mail_docn_to_customer'];
	$wc_id = $_POST['wc_id'];
	
	if(count($wci_aged_box))
	foreach($wci_aged_box as $agedbox){
		$wci_aged_box_data[$agedbox['wci_id']] = $agedbox['aged'];	
	}
	$assetCounter = 1;
	//print_r($_POST);die;
	$collection = new Collection($wc_id);
	$colData = $collection->getDetails();
	if($colData && count($wci_core_id)>0)
	{
		$storedAssetsCode = CollectionProcess::getAllProcessCode($colData['wc_id']);
		if(!is_array($storedAssetsCode))
			$storedAssetsCodeArray = explode("," , $storedAssetsCode);
		else
			$storedAssetsCodeArray = $storedAssetsCode;
		//print_r($storedAssetsCodeArray);
		$reUsedAssetCodeArray = array();
		foreach($wci_core_id as $index=>$key)
		{
			$wc_process_wc_id			= $wc_id;
			$wc_process_item_id 		= $key;
			if(isset($wci_item_name[$key]))
			{	
				if(count($wci_item_name[$key]) < 9999)
				{
					$total_item_quantity = 0;
					for($i=0; $i < count($wci_item_name[$key]); $i++)
					{			
						$wc_process_item_make		= isset($wci_item_make[$key][$i])?$wci_item_make[$key][$i]:"";
						$wc_process_item_model		= isset($wci_item_model[$key][$i])?$wci_item_model[$key][$i]:"";
						$wc_process_item_name 		= isset($wci_item_name[$key][$i])?$wci_item_name[$key][$i]:"";					
						$wc_process_item_qty		= isset($wci_item_qty[$key][$i])?$wci_item_qty[$key][$i]:"";
						
						$wc_process_item_sr_no		= isset($wci_item_srno[$key][$i])?strtoupper($wci_item_srno[$key][$i]):"";
						
						$wc_process_item_weight		= isset($wci_item_weight[$key][$i])?$wci_item_weight[$key][$i]:"";
						$wc_process_item_inext_phase= isset($wci_aged_box_data[$key][$i])?$wci_aged_box_data[$key][$i]:"";
						
						$total_item_quantity += $wci_item_qty[$key][$i];
										
						if(isset($wc_item_process_asset_code[$key][$i]) && $wc_item_process_asset_code[$key][$i] != "")
						{
							$wc_process_asset_code = $wc_item_process_asset_code[$key][$i];
							CollectionProcess::updateProcess($wc_process_asset_code, $wc_process_wc_id, $wc_process_item_id, $wc_process_item_make, $wc_process_item_model, $wc_process_item_name, $wc_process_item_sr_no, $wc_process_item_weight, $wc_process_item_qty, $wc_process_item_inext_phase);
							array_push($reUsedAssetCodeArray, $wc_process_asset_code);
						}
						else
						{
							$wc_process_asset_code = CollectionProcess::getProcessCode($colData['wc_id'], $colData['wc_code']);	
							CollectionProcess::addProcess($wc_process_asset_code, $wc_process_wc_id, $wc_process_item_id, $wc_process_item_make, $wc_process_item_model, $wc_process_item_name, $wc_process_item_sr_no, $wc_process_item_weight, $wc_process_item_qty, $wc_process_item_inext_phase);
						}
					}
					
					$WcrItemRow = new WcrItem();
					$WcrItemRow->updateQuantity($wc_id, $key, $total_item_quantity);
				}
			}
		}
		//print_r($reUsedAssetCodeArray);
		$deletableAssetsCodes = array_diff($storedAssetsCodeArray, $reUsedAssetCodeArray);
		//print_r($deletableAssetsCodes);
		//die;
		if(count($deletableAssetsCodes) > 0)
		{
			foreach($deletableAssetsCodes as $assetsCode)
			{
				$itemData = CollectionProcess::getProcessByCode($assetsCode);
			 	CollectionProcess::deleteProcessByCode($assetsCode);
				WcItem::removeProcessItemValues($itemData['wc_process_id']);
			}	
		}
		
		if($wc_mail_hwcn_to_customer ==1 || $wc_mail_wcnn_to_customer == 1 || $wc_mail_docn_to_customer == 1)
		{
			$customer = new Customer($colData['wc_customer_id']);
			$costomerRecord = $customer->getDetails();
			new SMS($costomerRecord['customer_phone'], "Your Collection #$colData[wc_code] processed successfully. Your registered email id is $costomerRecord[customer_email].");
			$dataArray = array(
				"customer_name" => $costomerRecord['customer_fname'],
				"customer_email" => $costomerRecord['customer_email'],
				"wc_code" => $colData['wc_code'],
				"login_page" => $app->basePath("customer-login.php")
			);			
			
			$email     = new Email("Your Collection Request #$colData[wc_code] Processed");
			$email->to($costomerRecord['customer_email'], $costomerRecord['customer_fname'], $app->imagePath($costomerRecord["customer_image"]));				
			
			if($wc_mail_hwcn_to_customer)
			$email->addFile(DOC::HWCN($wc_id), $app->siteName . " Collection - $colData[wc_code].pdf");
			if($wc_mail_wcnn_to_customer)
			$email->addFile(DOC::WCNN($wc_id), $app->siteName . " Collection - $colData[wc_code].pdf");
			if($wc_mail_docn_to_customer)
			$email->addFile(DOC::DOCN($wc_id), $app->siteName . " Collection - $colData[wc_code].pdf");
			
			$email->template('updatecollection', $dataArray);
			$email->send();
			
			Activity::add("Processed|^|{$colData['wc_code']}", "W", $wc_id);					
		}	
		
			
		$hwc_link = DOC::HWCN($wc_id);
		$wcn_link = DOC::WCNN($wc_id);
		$doc_link = DOC::DOCN($wc_id);
		$cer_link = DOC::CERT($wc_id);	
	
		echo json_encode(array("200",  "success|Collection Request processed Successfully",
			$wc_id,
			$colData['wc_code'],
			"hwc_link" => "javascript:newWindow('$hwc_link')",
			"wcn_link" => "javascript:newWindow('$wcn_link')",
			"doc_link" => "javascript:newWindow('$doc_link')",
			"cer_link" => "javascript:newWindow('$cer_link')"
		));	
	}
	else{
		echo json_encode(array("300",  "danger|No Processing Items found"));
		exit();
	}	

?>