<?php

	$keyword = "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if($keyword != ""){
		if($record = WebsiteOrderProduct::checkProductStockStatus($keyword))
			echo json_encode(array("200", "success|Product details found", $record));
		else
			echo json_encode(array("300",  "danger|No product matched."));
	}
	else
		echo json_encode(array("300",  "danger|Input format is not correct."));

?>