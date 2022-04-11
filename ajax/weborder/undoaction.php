<?php
	$web_order_id = 0;
	$step = '';
	$data  = sanitizePostData($_POST);
	
	extract($data);
	if($web_order_id){
		$websiteOrder = new WebsiteOrder($web_order_id);
		if($websiteOrder->isExist())
		{
			$action_user_id = getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $websiteOrder->getDetails();
			if($step == "unpick")
			{
				if($details['web_order_cancel_user'] == 0 || $details['web_order_status'] != 3 )
				{
					if($details['web_order_picking_user'] != 0 && $details['web_order_packing_user'] == 0)
					{						
						$websiteOrder->update(array(
							"web_order_picking_user"=> 0,
							"web_order_picking_time" => 'NULL',
							"web_order_status" => 2
						));
						Activity::add("<b>Step back order</b> removing assigned Picking user from  |^|{$details['web_order_number']}", "O", $web_order_id);
						echo json_encode(array("200",  "success|Picking user removed."));
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder is Processed. Can't remove Packing user"));
					}				
				}
				else
					echo json_encode(array("300",  "warning|Weborder is Cancelled or Completed. Can't remove Packing user"));
			}
			elseif($step == "unpack")
			{
				if($details['web_order_cancel_user'] == 0 || $details['web_order_status'] != 3 )
				{
					if($details['web_order_packing_user'] != 0 && $details['web_order_process_user'] == 0)
					{						
						$websiteOrder->update(array(
							"web_order_packing_user"=> 0,
							"web_order_packing_time" => 'NULL',
							"web_order_status" => 2
						));
						Activity::add("<b>Step back order</b> removing assigned Packing user from  |^|{$details['web_order_number']}", "O", $web_order_id);
						echo json_encode(array("200",  "success|Packing user removed."));
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder is Processed. Can't remove Packing user"));
					}				
				}
				else
					echo json_encode(array("300",  "warning|Weborder is Cancelled or Completed. Can't remove Packing user"));
			}
			elseif($step == "unprocess")
			{
				if($details['web_order_cancel_user'] == 0 || $details['web_order_status'] != 3 )
				{
					if($details['web_order_process_user'] != 0 && $details['web_order_complete_user'] == 0)
					{						
						$websiteOrder->update(array(
							"web_order_process_user"=> 0,
							"web_order_process_time" => 'NULL',
							"web_order_status" => 2
						));
						Activity::add("<b>Step back order</b> by removing assigned Processing user from |^|{$details['web_order_number']}", "O", $web_order_id);
						echo json_encode(array("200",  "success|Processing user removed."));
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder is Processed. Can't remove Packing user"));
					}				
				}
				else
					echo json_encode(array("300",  "warning|Weborder is Cancelled or Completed. Can't remove Packing user"));
			}
		}
		else
			echo json_encode(array("300",  "warning|Weborder not found"));
	}
	else
		echo json_encode(array("300",  "warning|Not a valid Web order"));

?>