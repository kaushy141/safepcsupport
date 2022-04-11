<?php

    $customer_email = "";
	$customer_id = 0;
    $data         = sanitizePostData($_POST);
    extract($data);
	$customer = new Customer($customer_id);
	if(!$customer->isEmailExists($customer_email, $customer_id == 0 ? NULL : $customer_id)){
		echo json_encode(array("200",  "success|Email id is available to use"));
	}
	else{
		echo json_encode(array("300",  "warning|Email id already exist"));
	}

?>