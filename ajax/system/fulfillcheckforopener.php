<?php

	$data_code = $data_id = $data_sku = $data_item_id = "";
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($data_sku != ""){
		$fulFillOptions = WebsiteOrderProduct::checkProductAvailability($data_sku);
		if($fulFillOptions){
			echo json_encode(array("200", "success|Fulfill options loaded", "data"=>$fulFillOptions));
		}
		else
			echo json_encode(array("300", "warning|Fulfill option not available"));
	}
	else
		echo json_encode(array("300", "warning|Product SKU is not valid"));

?>