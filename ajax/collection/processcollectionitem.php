<?php

	$wc_id = $item_id = 0;
	$data  = sanitizePostData($_POST);
    extract($data);
	if($wc_id && $item_id){
		$collectionItems = CollectionProcess::getAllProcessCodeOfItem($wc_id, $item_id);
		if(!$collectionItems)
		{
			$collectionItem = CollectionProcess::getColletionItemDetails($wc_id, $item_id);
			$collection = new Collection($wc_id);
			$colData = $collection->getDetails();
			$item = new WcItem($item_id);
			$itemData = $item->getDetails();	
			$wc_process_wc_id 	= $wc_id;
			$wc_process_item_id = $item_id;
			$wc_process_item_make = '';
			$wc_process_item_model = '';
			$wc_process_item_name = $itemData['wci_name'];
			$wc_process_item_make = '';
			$wc_process_item_sr_no = '';
			$wc_process_item_weight = '';
			$wc_process_item_qty = $itemData['wci_serialize_type'] == SERIALIZED ? 1 : 0;
			$wc_process_item_inext_phase = 0;
			$loopQty = $itemData['wci_serialize_type'] == SERIALIZED ? $collectionItem['wcr_item_qty'] : 1;
			for($i=0; $i<$loopQty; $i++)
			{
				if(CollectionProcess::getAllProcessItemCount($wc_id, $item_id) <= $loopQty)
				{
					$wc_process_asset_code = CollectionProcess::getProcessCode($colData['wc_id'], $colData['wc_code']);	
				CollectionProcess::addProcess($wc_process_asset_code, $wc_process_wc_id, $wc_process_item_id, $wc_process_item_make, $wc_process_item_model, $wc_process_item_name, $wc_process_item_sr_no, $wc_process_item_weight, $wc_process_item_qty, $wc_process_item_inext_phase);
				}
			}
		}
		$processList = CollectionProcess::getProcess($wc_id, $item_id);
		$processRows = '';
		foreach($processList as $item){
			$processRows .= CollectionProcess::getProcessItemTableRow($item);
		}
		echo json_encode(array("200",  "success|Collection Item Request Processed.", $processRows));
		exit();
	}
	else{
		echo json_encode(array("300",  "danger|No Processing Items found"));
		exit();
	}	

?>