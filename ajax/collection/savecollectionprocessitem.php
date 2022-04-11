<?php
	$wc_process_asset_code = '';
	$wc_process_verified = 'NULL';
	$apply_on_all_items_of_this_collection = $wc_process_item_pallet = $apply_data_count = $wc_process_item_stock = $wc_process_under_technician = $wc_process_is_on_way = 0;
	$chkattribute = array();
	$wc_process_item_make = $wc_process_item_model = $wc_process_item_name = $wc_process_item_sku = $wc_process_item_sr_no = $wc_process_item_weight = $wc_process_age_date = $wc_process_item_location = '';
	$data  = sanitizePostData($_POST);
	$wc_process_item_stock = isset($_POST['wc_process_item_stock']) ? 1 : 0;
	extract($data);	
	$wc_process_verified = $wc_process_verified ? 'NOW()' : 'NULL';
	if($wc_process_asset_code)
	{
		$collectionProcess = new CollectionProcess($wc_process_asset_code);
		
		if(($wc_process_item_sr_no != "" && $wc_process_item_sr_no != "N/A" ) && $collectionProcess->isSerialNumberExist($wc_process_item_sr_no)){
			echo json_encode(array("300", "warning|Serial number \"$product_serial_number\" already exist."));	
			die;
		}
		$wcdata = $collectionProcess->getDetails();
		if($wcdata)
		{			
			$collectionProcess->update(
				array(
					'wc_process_item_make' 	=> $wc_process_item_make, 
					'wc_process_item_model' => $wc_process_item_model, 
					'wc_process_item_name' 	=> $wc_process_item_name, 
					'wc_process_item_sku' 	=> $wc_process_item_sku, 
					'wc_process_item_stock' => $wc_process_item_stock, 
					'wc_process_item_sr_no' => $wc_process_item_sr_no, 
					'wc_process_item_weight'=> $wc_process_item_weight,
					'wc_process_under_technician' =>$wc_process_under_technician,
					'wc_process_age_date'=>	$wc_process_age_date,
					'wc_process_verified' => $wc_process_verified,
					'wc_process_item_location' => $wc_process_item_location,
					'wc_process_is_on_way' => $wc_process_is_on_way
					)
			);
			if($wcdata['wc_process_under_technician'] != $wc_process_under_technician)
			{
				if($wc_process_under_technician)
				$techuser = new Employee($wc_process_under_technician);
				else
				$techuser = new Employee($wcdata['wc_process_under_technician']);
				$techuserData = $techuser->getDetails();
				Activity::add(($wc_process_under_technician ? "assigned":"discharged")." Collection item <b>$wc_process_asset_code</b> ".($wc_process_under_technician ? "to":"from")." ".$techuserData['user_name']);	
			}
			$pallet = new Pallet($wc_process_item_pallet);
			if($wc_process_item_pallet && $pallet->isExist() && !$pallet->isFull()){
				$PalletItems = new PalletItems($wc_process_item_pallet);
				$wpi_label_number = $PalletItems->getPalletItemLabel();
				$wpi_code_number = $PalletItems->getPalletItemNumber($wpi_label_number);	
				$PalletItems->saveItem($wc_process_asset_code, $wc_process_item_pallet, $wpi_code_number, $wpi_label_number, 0);
			}
			elseif($wc_process_item_pallet){
				echo json_encode(array("300",  "warning|Pallet is Full."));	
				die;
			}
			$values_wc_process_id = $wcdata['wc_process_id'];
			$wcItem = new WcItem($wcdata['wc_process_item_id']);
			$attributes = $wcItem->getItemAttributes();
			if($attributes){
				foreach($attributes as $item)
				{
					$values_realtion_id  = $item['attribute_relation_id'];
					$values_item_id 	 = $item['attribute_relation_item_id'];
					$values_attribute_id = $item['attribute_relation_attribute_id'];
					$values_wc_id 		 = $wcdata['wc_process_wc_id'];
					$values_data = isset($attribute[$values_attribute_id]) ? $attribute[$values_attribute_id] : "No";
					if(!$wcItem->isExistItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id))
						$wcItem->addItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
					else
						$wcItem->updateItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
				}				
								
				if($apply_on_all_items_of_this_collection && count($chkattribute)){
					$collectionProcess = new CollectionProcess();
					$getSimilarItems = $collectionProcess->getSimilarItemFromCollection($wcdata['wc_process_wc_id'], $wcdata['wc_process_item_id']);
					
					$process_fields = array_intersect($chkattribute, array('wc_process_item_make', 'wc_process_item_model', 'wc_process_item_name', 'wc_process_item_weight', 'wc_process_age_date', 'wc_process_item_sku', 'wc_process_item_location'));
					$similarUpdateFields = compact($process_fields);
					if($apply_data_count > 0){
						$similar_wc_process_asset_code_array = array_column($getSimilarItems, 'wc_process_asset_code');
						$from = array_search($wc_process_asset_code, $similar_wc_process_asset_code_array);
						$getSimilarItems = array_slice($getSimilarItems, $from, $apply_data_count+1, true);
					}
					
					if(count($getSimilarItems)){
						foreach($getSimilarItems as $_process){
							if($_process['wc_process_asset_code'] != $wc_process_asset_code)
							{
								$_collectionProcess = new CollectionProcess($_process['wc_process_asset_code']);
								$values_wc_process_id = $_process['wc_process_id'];
								if(count($process_fields)){
									$_collectionProcess->update($similarUpdateFields);
								}								
								$wcItem = new WcItem($wcdata['wc_process_item_id']);
								$attributes = $wcItem->getItemAttributes();
								foreach($attributes as $item)
								{
									if(in_array($item['attribute_relation_attribute_id'], $chkattribute))
									{
										$values_realtion_id  = $item['attribute_relation_id'];
										$values_item_id 	 = $item['attribute_relation_item_id'];
										$values_attribute_id = $item['attribute_relation_attribute_id'];
										$values_wc_id 		 = $wcdata['wc_process_wc_id'];
										$values_data = isset($attribute[$values_attribute_id]) ? $attribute[$values_attribute_id] : "No";
										if(!$wcItem->isExistItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id))
											$wcItem->addItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
										else
											$wcItem->updateItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
									}
								}
								/*
								$pallet = new Pallet($wc_process_item_pallet);
								if($wc_process_item_pallet && $pallet->isExist() && !$pallet->isFull())
								{
									$PalletItems = new PalletItems($wc_process_item_pallet);
									$wpi_label_number = $PalletItems->getPalletItemLabel();
									$wpi_code_number = $PalletItems->getPalletItemNumber($wpi_label_number);	
									$PalletItems->saveItem($wc_process_asset_code, $wc_process_item_pallet, $wpi_code_number, $wpi_label_number, $wpi_item_order);									
								}
								*/
							}
						}
					}
				}
				Activity::add("updated Collection item <b>$wc_process_asset_code</b>");				
				echo json_encode(array("200",  "success|Item Details Saved", $attribute));
			}
			else
				echo json_encode(array("200",  "warning|Product saved but Item attributes not found"));
		}
		else
			echo json_encode(array("300",  "warning|Item code not found."));	
	}
	else
		echo json_encode(array("300",  "warning|Item code not exist."));	

?>