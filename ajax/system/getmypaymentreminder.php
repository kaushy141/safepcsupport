<?php
	
	$data  = sanitizePostData($_POST);
	extract($data);		
	Modal::load(array('PaymentReminder'));
	$paymentReminder = new PaymentReminder();
	$records = $paymentReminder->getMyPaymentReminder();
	echo json_encode(array("200", "success|Payment reminder record fetched", "data"=>$records));

?>