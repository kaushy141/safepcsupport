<?php
	$listprocesscode = $processcode = array();
	$pallet_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$pallet = new Pallet($pallet_id);	
	if($pallet->isExist())
	{
		if(count($processcode)>0)
		{
			$PalletItems = new PalletItems($pallet_id);
			$storedPalletItems = $PalletItems->getStoredItems();
			$storedPalletItems = $storedPalletItems == NULL ? array() : $storedPalletItems;
			
			foreach($processcode as $wc_process_asset_code)
			{
				$itemData = json_decode(stripslashes($listprocesscode[$wc_process_asset_code]), true);
				$collectionProcess = new CollectionProcess($wc_process_asset_code);
				if($collectionProcess->isExist()){					
					$allowedStoredColumns = array(
							"wc_process_item_make",
							"wc_process_item_model",
							"wc_process_item_name",
							"wc_process_item_sku",
							"wc_process_item_stock",
							"wc_process_item_sr_no",
							"wc_process_item_location",
							"wc_process_item_weight",
							"wc_process_item_qty",
							"wc_process_item_damage_status"
							);
					$itemData = array_intersect_key( $itemData, array_flip( $allowedStoredColumns ));					
					$collectionProcess->update($itemData);				
				}
				else
				{
					echo json_encode(array("300",  "error|Oooops.. Process beak. <br/>Item <b>$wc_process_asset_code</b> does not exit.<br/> Pallet items not updated. try again"));	
				}
			}
			
			$newItems = array_diff($processcode, $storedPalletItems);
			$removeItems = array_diff($storedPalletItems, $processcode);
			
			if(!empty($newItems))
			{
				foreach($newItems as $wc_process_asset_code)
				{
					$wpi_label_number = $PalletItems->getPalletItemLabel();
					$wpi_code_number = $PalletItems->getPalletItemNumber($wpi_label_number);					
					
					$PalletItems->add($wc_process_asset_code, $pallet_id, $wpi_code_number, $wpi_label_number, 0);
				}
			}
			if(!empty($removeItems))
			{
				foreach($removeItems as $wc_process_asset_code)
				{
					$PalletItems->removeByCodeItemCode($wc_process_asset_code);
				}
				
			}
			$wpi_item_order = 1;
			foreach($processcode as $wc_process_asset_code)
			{
				$PalletItems->updateItemOrderByCode($wc_process_asset_code, $wpi_item_order++);
			}
			echo json_encode(array("200",  "success|Pallet's Item updated.<br/>".count($newItems). " items added on Pallet<br/>".count($removeItems)." items removed from Pallet"));
		}
		else
			echo json_encode(array("300",  "warning|No Collection item found."));
	}
	else
		echo json_encode(array("300",  "warning|No Pallet found."));

?>