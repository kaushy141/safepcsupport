<?php
//die;

    $wc_transferor_signature = $wc_completion_date = $wc_arrival_time = $wc_departure_time = $wc_member_of_staff_name = $wc_authority_member_of_staff = "";
    $wc_id                   = $wc_status_id = 0;
    $data_wci_item_id = $data_wci_qtiy_id = $data_wci_weit_id = $data_wci_chamount_id = $data_wci_chformat_id = $data_wci_itmdesc_id = $data_wci_pdamount_id = array();
	
	$wc_mail_hwcn_to_customer = $wc_mail_wcnn_to_customer = $wc_mail_docn_to_customer = 0;
	
    $data                    = sanitizePostData($_POST);
    extract($data);
    $collection = new Collection($wc_id);
    $prevData   = $collection->load();
    if (!$prevData) {
        echo json_encode(array("300", "Error|Complaint not Exists."
        ));
        exit();
    }
    $signaturePath = "";
    if ($wc_transferor_signature != "") {
        $signaturePath = "upload/collection/sign/".getDirectorySeparatorPath()."$prevData[wc_code]-" . time() . ".png";
        if (!move_file($app->sitePath($wc_transferor_signature), $app->sitePath($signaturePath)))
            $signaturePath = "";
    } else
        $signaturePath = $prevData['wc_transferor_signature'];
    $wc_code = $prevData['wc_code'];
    $collection->update(array("wc_status_id" => $wc_status_id,
        "wc_transferor_signature" => $signaturePath,
        "wc_completion_date" => $wc_completion_date,
        "wc_arrival_time" => $wc_arrival_time,
        "wc_departure_time" => $wc_departure_time,
        "wc_member_of_staff_name" => $wc_member_of_staff_name,
        "wc_authority_member_of_staff" => $wc_authority_member_of_staff
    ));
    if ($signaturePath != $prevData['wc_transferor_signature'] && $prevData['wc_transferor_signature'] != "")
        unlink($app->sitePath($prevData['wc_transferor_signature']));
    	
	
	
	$storedAssetsCode = CollectionProcess::getAllProcessCode($wc_id);
	if(is_array($storedAssetsCode))
	$storedAssetsCodeArray = $storedAssetsCode;
	else
	$storedAssetsCodeArray = $storedAssetsCode ? explode("," , $storedAssetsCode) : array();
	$reUsedAssetCodeArray = array();	
	
	
	$WcrItem = new WcrItem();
	$AddedAllItemArray = $WcrItem->getAllItemArray($wc_id);
    if (!empty($data_wci_item_id)) {
        $i = 0;
		$reusedWciItemArray = array();
        foreach ($data_wci_item_id as $wcr_item_id) 
		{
			$reusedWciItemArray[] = $wcr_item_id;
			$WcrItemIsExist = new WcrItem();
			if($wcr_id = $WcrItemIsExist->isItemExist($wc_id, $wcr_item_id))
			{
				$WcrItemRow = new WcrItem($wcr_id);
				$WcrItemInfo = $WcrItemRow->getDetails();
				if(!$WcrItemInfo){
					$WcrItemInfo['wcr_item_qty'] = 0;
				}
				$WcrItemRow->update(array(
					"wcr_item_qty" => $data_wci_qtiy_id[$i], 
					"wcr_item_weight" => floatval($data_wci_weit_id[$i]), 
					"wcr_item_charge_amount" => floatval($data_wci_chamount_id[$i]), 
					"wcr_item_charge_amount_paid" => floatval($data_wci_pdamount_id[$i]), 
					"wcr_item_charge_format" => $data_wci_chformat_id[$i], 
					"wcr_item_description" => $data_wci_itmdesc_id[$i]
				));
				if($WcrItemInfo['wcr_item_qty'] != $data_wci_qtiy_id[$i]){
					if($data_wci_qtiy_id[$i] > $WcrItemInfo['wcr_item_qty']){
						for($j=0; $j<($data_wci_qtiy_id[$i] - $WcrItemInfo['wcr_item_qty']); $j++){
							$wc_process_asset_code = CollectionProcess::getProcessCode($wc_id, $prevData['wc_code']);
							CollectionProcess::addProcess($wc_process_asset_code, $wc_id, $wcr_item_id, '', '', '', '', floatval($data_wci_weit_id[$i]), 1, 0, 0);
						}
					}
					else{
						CollectionProcess::removeSingleItemFromCollectionProcess($wc_id, $wcr_item_id);
					}
				}
			}
			else
			{
				$WcrItemAdd = new WcrItem();
				$WcrItemAdd->insert(
					array(
						'wc_id' => $wc_id,
						'wcr_item_id' => $wcr_item_id, 
						'wcr_item_qty' => $data_wci_qtiy_id[$i], 
						'wcr_item_weight' => floatval($data_wci_weit_id[$i]), 
						'wcr_item_status' => 1, 
						'wcr_item_collection_date' => 'NOW()', 
						'wcr_item_charge_amount' => floatval($data_wci_chamount_id[$i]), 
						'wcr_item_charge_amount_paid' => floatval($data_wci_pdamount_id[$i]), 
						'wcr_item_charge_format' => $data_wci_chformat_id[$i], 
						'wcr_item_description' => $data_wci_itmdesc_id[$i]
					)
				);
				//$WcrItemAdd->add($wc_id, $wcr_item_id, $data_wci_qtiy_id[$i], $data_wci_weit_id[$i], 1, $data_wci_chamount_id[$i], $data_wci_pdamount_id[$i], $data_wci_chformat_id[$i], $data_wci_itmdesc_id[$i]);
			}			
			$i++;			
        }
		
		foreach($AddedAllItemArray as $wcr_item_id){
			if(!in_array($wcr_item_id, $reusedWciItemArray)){
				$WcrItemRow = new WcrItem($wcr_item_id);
				$WcrItemRow->removeItemFromCollection($wc_id, $wcr_item_id);
				CollectionProcess::removeItemFromCollectionProcess($wc_id, $wcr_item_id);
			}
		}
		
    }
	else{
		foreach($AddedAllItemArray as $wcr_item_id){
			$WcrItemRow = new WcrItem($wcr_item_id);
			$WcrItemRow->removeItemFromCollection($wc_id, $wcr_item_id);
			CollectionProcess::removeItemFromCollectionProcess($wc_id, $wcr_item_id);			
		}
	}
	
	
	
    //Email to Collector Manager 
    $comMgr    = new Employee($prevData['wc_manager_id']);
    $mger      = $comMgr->getDetails();
    $dataArray = array(
        "user_fname" => $mger['user_fname'],
		"manager_name" => $mger['user_fname'],
        "user_email" => $mger['user_email'],
        "wc_code" => $prevData['wc_code'],
        "login_page" => $app->basePath("login.php"),
        "wc_due_date" => date("d M Y", strtotime($prevData['wc_due_date']))
    );
    $email     = new Email("Collection Request #$prevData[wc_code] Updated");
    $email->to($mger['user_email'], $mger['user_fname'], $mger['user_image']);
	if($wc_mail_hwcn_to_customer)
	$email->addFile(DOC::HWCN($wc_id), $app->siteName . " Collection hwcn - $prevData[wc_code].pdf");
	if($wc_mail_wcnn_to_customer)
	$email->addFile(DOC::WCNN($wc_id), $app->siteName . " Collection wcnn - $prevData[wc_code].pdf");
	if($wc_mail_docn_to_customer)
	$email->addFile(DOC::DOCN($wc_id), $app->siteName . " Collection docn - $prevData[wc_code].pdf");
	
	$email->template('collection_request_collector_new', $dataArray);
	$email->send();
	
    $hwc_link = DOC::HWCN($wc_id);
    $wcn_link = DOC::WCNN($wc_id);
    $doc_link = DOC::DOCN($wc_id);
    $cer_link = DOC::CERT($wc_id);
    Activity::add("updated|^|$prevData[wc_code]", "W", $wc_id);
    echo json_encode(array("200",  "success|Collection Request Managed Successfully",
        $wc_id,
        $prevData['wc_code'],
        "hwc_link" => "javascript:newWindow('$hwc_link')",
        "wcn_link" => "javascript:newWindow('$wcn_link')",
        "doc_link" => "javascript:newWindow('$doc_link')",
        "cer_link" => "javascript:newWindow('$cer_link')"
    ));

?>