<?php

	$id = $priority = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($id){
		$priority = intval($priority);
		$websiteOrder = new WebsiteOrder($id);		
		if($websiteOrder->isExist()){
			$orderData = $websiteOrder->getDetails();
			$websiteOrder->update(array("web_order_priority" => $priority));
			$piorityLabel = $orderData['web_order_priority'] > $priority ? "descrease" : "increase";
			Activity::add("$piorityLabel Priority by <b>$priority</b> on |^|{$orderData['web_order_number']}", "O", $id);
			echo json_encode(array("200", "success|Weborder priority updated successfully"));
		}
		else{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Order not found"));
	}

?>