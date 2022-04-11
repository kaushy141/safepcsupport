<?php
	$wc_code = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($wc_code != ""){
		$collection = new Collection();
		$collectionData = $collection->getDetailsByCode($wc_code);
		$col = new Collection($collectionData['wc_id']);
		echo json_encode(array("200", "success|Collection Items Loaded", $col->getCollectionItems()));
	}
	else{
		echo json_encode(array("300",  "danger|Collection not found."));
	}

?>