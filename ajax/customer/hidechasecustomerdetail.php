<?php
	Modal::load(array('ChaseCustomer'));
	$customer_id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$chaseCustomer = new ChaseCustomer($customer_id);
	$details = $chaseCustomer->getDetails();
	$chaseCustomer->update(array("chase_customer_schedule_date"=>"NULL"));	
    echo json_encode(array("200",  "success|Customer unscheduled Successfully"));
?>