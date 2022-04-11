<?php
	$product_id = 0;
	$verify = '';
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($product_id){
		$bp = new BatchProduct($product_id);
		if(in_array($verify, array(0,1)) && $bp->isExist())
		{
			$bp->update(array('product_verified'=> $verify ? 'NOW()':'NULL'));
			echo json_encode(array("200",  "success|Batch product ".($verify ? 'Verified':'Unverified')));
		}
		else
			echo json_encode(array("300",  "warning|No Batch product found."));
	}
	else
		echo json_encode(array("300",  "warning|Invalid Batch product request."));

?>