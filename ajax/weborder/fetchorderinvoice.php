<?php
	$web_order_id = 0;
	$data  = sanitizePostData($_POST);
	
	extract($data);
	if($web_order_id){
		$websiteOrder = new WebsiteOrder($web_order_id);
		if($websiteOrder->isExist())
		{
			echo $websiteOrder->fetchOrderInvoice();			
		}
		else
			echo json_encode(array("300",  "warning|Weborder not found"));
	}
	else
		echo json_encode(array("300",  "warning|Not a valid Web order"));

?>