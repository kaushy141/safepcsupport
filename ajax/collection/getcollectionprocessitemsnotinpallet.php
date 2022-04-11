<?php
	$wc_code = "";
	$item_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($wc_code != ""){
		$collection = new Collection();
		$collectionData = $collection->getDetailsByCode($wc_code);
		$colProcess = new CollectionProcess();
		echo json_encode(array("200", "success|Collection Items Loaded", $colProcess->getCollectionItemsNotInPallet($collectionData['wc_id'], $item_id)));
	}
	else{
		echo json_encode(array("300",  "danger|Collection items not found."));
	}

?>