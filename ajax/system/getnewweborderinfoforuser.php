<?php
	$web_order_id = 0;	
	$data  = sanitizePostData($_POST);
	extract($data);
	if(isAdmin()){
		$websiteOrder = new WebsiteOrder();
		$value_key_id = 'last_order_seen_'.getLoginId();
		$web_order_id = Values::getKeyValues($value_key_id) ?: $web_order_id;
		$orderData = $websiteOrder->getOrderFromWebOrderID($web_order_id);	
		Values::saveKeyValues($value_key_id, $websiteOrder->getLastId());
		echo json_encode(array("200",  "success|Web Order fetched", $orderData));		
	}
	else	
		echo json_encode(array("300",  "danger|Permission deniend"));

?>