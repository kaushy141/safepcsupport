<?php
	$product_code = '';
	$verify = '';
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($product_code){
		$cp = new CollectionProcess($product_code);
		if(in_array($verify, array(0,1)) && $cp->isExist())
		{
			$cp->update(array('wc_process_verified'=> $verify ? 'NOW()':'NULL'));
			echo json_encode(array("200",  "success|Collection product ".($verify ? 'Verified':'Unverified')));
		}
		else
			echo json_encode(array("300",  "warning|No Collection product found."));
	}
	else
		echo json_encode(array("300",  "warning|Invalid Collection product request."));

?>