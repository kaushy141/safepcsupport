<?php
    $Schedule      = new Schedule(0);	
	$ComplaintLog = new ComplaintLog(0);	
    echo json_encode(array(
		"200",  
		"data" => $Schedule->fetchNotification(), 
		"customer_message"=>$ComplaintLog->getCustomerMessageNotification()
		)
	);
?>