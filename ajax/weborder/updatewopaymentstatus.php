<?php
	$web_order_id = $web_order_is_paid = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$websiteOrder = new WebsiteOrder($web_order_id);
	if($websiteOrder->isExist() && in_array($web_order_is_paid, array("Yes","No")))
	{
		$websiteOrder->update(array("web_order_is_paid" => $web_order_is_paid));		
		echo json_encode(array("200",  "success|Weborder payment status updated successfully", $web_order_is_paid));
	}
	else
		echo json_encode(array("300",  "warning|No weborder found.", $web_order_is_paid));	

?>