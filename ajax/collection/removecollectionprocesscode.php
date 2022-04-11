<?php

	$wc_id = $item_id = $process_code = 0;
	$data  = sanitizePostData($_POST);
    extract($data);
	if($wc_id && $item_id && $process_code)
	{
		$collectionItem = CollectionProcess::getColletionItemDetails($wc_id, $item_id);
		$cp = new CollectionProcess($process_code);
		if($cp->isExist())
		{
			$collItemData = $cp->getDetails();
			$cp->remove();
			$WcrItemRow = new WcrItem();
			$WcrItemRow->updateQuantity($wc_id, $item_id, $collectionItem['wcr_item_qty']-1);
			$cpi = new CollectionProcessItem();
			$cpi->removeCollectionItemAttributeValues($collItemData['wc_process_id']);
			echo json_encode(array("200",  "success|Collection Item Product removed."));
			exit();
		}
		else
		{
			echo json_encode(array("300",  "danger|Collection item not found"));
			exit();
		}
	}
	else{
		echo json_encode(array("300",  "danger|No Processing Items found"));
		exit();
	}
?>