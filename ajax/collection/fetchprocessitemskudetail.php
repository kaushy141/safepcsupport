<?php
	$wc_process_item_id = 0;
	$wc_process_item_sku = "";
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($wc_process_item_id && $wc_process_item_sku != ""){
		$cp = new CollectionProcess(0);
		if($record = $cp->getItemSkuCopy($wc_process_item_id, $wc_process_item_sku))
			echo json_encode(array("200",  "success|Details loaded", $record));
		else
			echo json_encode(array("300",  "warning|No Details found"));
	}
	else
		echo json_encode(array("300",  "warning|No Item found."));	

?>