<?php
	$bpca_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($bpca_id){
		$batchProductSaleHistory = new BatchProductSaleHistory($bpca_id);
		echo json_encode(array("200", "success|Product sale record detail loaded", $batchProductSaleHistory->getDetails()));
	}
	else{
		echo json_encode(array("300",  "danger|Products saled record not found."));
	}
?>