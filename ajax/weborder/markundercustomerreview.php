<?php
	$order_id = 0;
	$data  = sanitizePostData($_POST);
	$mark = 0;
	extract($data);
	if($order_id){
		$websiteOrder = new WebsiteOrder($order_id);
		if($websiteOrder->isExist())
		{
			$action_user_id = getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $websiteOrder->getDetails();
			if(($mark == 1 && $details['web_order_under_customer_review'] == NULL) || ($mark == 0 && $details['web_order_under_customer_review'] != NULL))
			{
				$orderUpdateArray = array(
					"web_order_under_customer_review" => $mark ? "NOW()" : "NULL"
				);
				$websiteOrder->update($orderUpdateArray);
				Activity::add("<b>".($mark ? "marked" : "unmarked")."</b> under customer review", "O", $order_id);
				echo json_encode(array("200",  "success|".($mark ? "marked" : "unmarked")." under customer review."));	
			}
			else
			{
				echo json_encode(array("300",  "warning|Original order status was not matched."));
			}
		}
		else
			echo json_encode(array("300",  "warning|Weborder not found"));
	}
	else
		echo json_encode(array("300",  "warning|Not a valid Web order"));

?>