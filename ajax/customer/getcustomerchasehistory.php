<?php
	Modal::load(array('ChaseCustomer'));
	$customer_id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$chaseCustomer = new ChaseCustomer($customer_id);
    echo json_encode(array("200",  "success|Customer History Loaded Successfully",
        $chaseCustomer->getCustomerChaseRecord()
    )); 
?>