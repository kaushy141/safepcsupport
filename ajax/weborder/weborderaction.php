<?php

	$order_action = '';
	$web_order_id = 0;
	$user_id = 0;
	$user_reset = 0;
	$web_order_is_outsourced = 0;
	$same_user_to_pack_order = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($web_order_id){
		$websiteOrder = new WebsiteOrder($web_order_id);
		if($websiteOrder->isExist())
		{
			$action_user_id = $user_id ?  $user_id : getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $websiteOrder->getDetails();
			if($details['web_order_cancel_user'] == 0 || $details['web_order_status'] != 3 )
			{
				if($order_action == 'pick')
				{
					$updateArry = array(
					"web_order_picking_user"=> $action_user_id,
					"web_order_picking_time" => 'NOW()');
					if($same_user_to_pack_order == 1){
						$updateArry["web_order_packing_user"] = $action_user_id;
						$updateArry["web_order_packing_time"] = 'NOW()';
						$updateArry["web_order_is_outsourced"]= 0;
					}
					
					if($action_user_id != getLoginId())
					{
						$userTag = new UserTag();
						$userTag->insert(array(
								"tag_mentioner_id" 	=> getLoginId(), 
								"tag_user_id" 		=> $action_user_id, 
								"tag_module_code"	=> 'O', 
								"tag_module_id"		=> $web_order_id, 
								"tag_time"			=> 'NOW()', 
								"tag_is_readed"		=> 0, 
								"tag_type"			=> 'assigned picking by', 
								"tag_text"			=> $details['web_order_number'],
								"tag_log_id"		=> 0
							)
						);
					}
					
					$websiteOrder->update($updateArry);
					Activity::add("marked <b>Picked</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
					if($same_user_to_pack_order == 1){
						
						if($action_user_id != getLoginId())
						{
							$userTag = new UserTag();
							$userTag->insert(array(
									"tag_mentioner_id" 	=> getLoginId(), 
									"tag_user_id" 		=> $action_user_id, 
									"tag_module_code"	=> 'O', 
									"tag_module_id"		=> $web_order_id, 
									"tag_time"			=> 'NOW()', 
									"tag_is_readed"		=> 0, 
									"tag_type"			=> 'assigned packing by', 
									"tag_text"			=> $details['web_order_number'],
									"tag_log_id"		=> 0
								)
							);
						}
							
						Activity::add("marked <b>Packed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
					}
					echo json_encode(array("200",  "success|Weborder marked as Picked".($same_user_to_pack_order ? " and Packed":"")));
				}
				elseif($order_action == 'pack')
				{
					if($details['web_order_picking_user'] != 0)
					{
						if($details['web_order_packing_user'] == 0 || ($user_reset == md5($details['web_order_packing_user']))){
							$websiteOrder->update(array(
							"web_order_packing_user"=> $action_user_id,
							"web_order_packing_time" => 'NOW()',
							"web_order_is_outsourced" => $web_order_is_outsourced
							));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'O', 
										"tag_module_id"		=> $web_order_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned packing by', 
										"tag_text"			=> $details['web_order_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add(($user_reset ? "Remarked":"marked")." <b>Packed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
							echo json_encode(array("200",  "success|Weborder ".($user_reset ? "Remarked":"marked")." as Packed"));
						}
						else{
							$employee = new Employee($details['web_order_packing_user']);
							echo json_encode(array("300",  "warning|Weborder already Packed by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder should be Picked first"));
					}
				}
				elseif($order_action == 'process')
				{
					if($details['web_order_packing_user'] != 0)
					{
						if($details['web_order_process_user'] == 0){
							$websiteOrder->update(array(
							"web_order_process_user"=> $action_user_id,
							"web_order_process_time" => 'NOW()',
							"web_order_status" => 4));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'O', 
										"tag_module_id"		=> $web_order_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned processing by', 
										"tag_text"			=> $details['web_order_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add("marked <b>Processed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
							echo json_encode(array("200",  "success|Weborder marked as Process"));
						}
						else{
							$employee = new Employee($details['web_order_process_user']);
							echo json_encode(array("300",  "warning|Weborder already Process by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder should be Packed first"));
					}
				}
				elseif($order_action == 'cancel')
				{				
					$websiteOrder->update(array(
					"web_order_cancel_user"=> $action_user_id,
					"web_order_cancel_time" => 'NOW()',
					"web_order_status" => 3
					));
					Activity::add("marked <b>Cancelled</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
					echo json_encode(array("200",  "success|Weborder marked as Cancel"));
				}
				else
				{
					echo json_encode(array("300",  "warning|Invalid Action Found"));
				}				
			}
			else
			{				
				$employee = new Employee($details['web_order_cancel_user']);
				echo json_encode(array("300",  "warning|Weborder already Cancel by ".$employee->get('user_fname')));
			}
		}
		else
		{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else
	{
		echo json_encode(array("300",  "warning|Weborder not found"));
	}

?>