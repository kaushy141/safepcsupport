<?php

    $feedback_customer_id = $feedback_module_id = 0;
    $feedback_module_code = "";
    $feedback_store       = DEFAULT_FEEDBACK_STORE;
    $feedback_status      = 1;
    $data                 = sanitizePostData($_POST);
    extract($data);
	$store = new Store();
	$storeList = $store->getAll(array('store_id', 'store_title', 'store_name', 'store_link', 'store_key', 'store_logo'));	
	$feedback          = new Feedback();
	$feedback_stores       = $feedback->getSendedFeedback($feedback_customer_id, $feedback_module_id, $feedback_module_code);
	echo json_encode(array("200",  "success|Feedback request submitted Successfully", $feedback_stores, $storeList)); 

?>