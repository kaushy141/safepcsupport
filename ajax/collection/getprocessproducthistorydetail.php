<?php
	$wpca_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($wpca_id){
		$processProductSaleHistory = new ProcessProductSaleHistory($wpca_id);
		echo json_encode(array("200", "success|Product sale record detail loaded", $processProductSaleHistory->getDetails()));
	}
	else{
		echo json_encode(array("300",  "danger|Products saled record not found."));
	}

?>