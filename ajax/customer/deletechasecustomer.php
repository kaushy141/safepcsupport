<?php
	Modal::load(array('ChaseCustomer'));
	$customer_id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$chaseCustomer = new ChaseCustomer($customer_id);
	if($chaseCustomer->isExist()){
		$details = $chaseCustomer->getDetails();
		$chaseCustomer->remove();	
		$chaseCustomer->deleteRecord();
		Activity::add("deleted chase customer <b>{$details['chase_customer_name']} record</b>");		
		echo json_encode(array("200",  "success|Customer {$details['chase_customer_name']} deleted Successfully"));
	}
	else{
		echo json_encode(array("300",  "warning|Customer record not found"));
	}
?>