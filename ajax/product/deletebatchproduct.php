<?php
	$product_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($product_id){
		$bp = new BatchProduct($product_id);
		if($bp->isExist())
		{
			$bp->remove();
			echo json_encode(array("200",  "success|Batch product removed"));
		}
		else
			echo json_encode(array("300",  "warning|No Batch product found."));
	}
	else
		echo json_encode(array("300",  "warning|Invalid Batch product request."));

?>