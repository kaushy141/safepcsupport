<?php
	Modal::load(array('ChaseCustomer'));
	$customer_id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$chaseCustomer = new ChaseCustomer($customer_id);
	
	$details = $chaseCustomer->getDetails();
	$details['status_options'] = $chaseCustomer->getChaseCustomerStatusOptions(isset($details['chase_customer_status']) ? $details['chase_customer_status'] : "");
	$details['type_options'] = $chaseCustomer->getChaseCustomerAttributeOptions("chase_customer_type", isset($details['chase_customer_type']) ? $details['chase_customer_type'] : "", "Select type");
	echo json_encode(array("200",  "success|Customer Loaded Successfully", $details));

?>