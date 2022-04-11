<?php

	$order_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($order_id){
		$websiteOrder = new WebsiteOrder($order_id);
		if($websiteOrder->isExist()){
			$details = $websiteOrder->getDetails();
			if($details['web_order_tracking_mark_by'] == 0){
				$websiteOrder->update(array("web_order_tracking_mark_by"=> getLoginId()));
				echo json_encode(array("200",  "success|Weborder marked as tracked", "<img src=\"".$app->imagePath('img/track.png')."\" style=\"height:20px; width:24px;\">"));
			}
			else{
				$employee = new Employee($details['web_order_tracking_mark_by']);
				echo json_encode(array("300",  "warning|Weborder already marked as Tracked by ".$employee->get('user_fname')));
			}
		}
		else{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Weborder not found"));
	}

?>