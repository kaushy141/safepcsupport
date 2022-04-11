<?php

	$order_action = '';
	$complaint_id = 0;
	$user_id = 0;
	$user_reset = 0;
	$complaint_is_outsourced = 0;
	$same_user_to_pack_order = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($complaint_id){
		$complaint = new Complaint($complaint_id);
		if($complaint->isExist())
		{
			$action_user_id = $user_id ?  $user_id : getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $complaint->getDetails();
			if($details['complaint_cancel_user'] == 0 || $details['complaint_status'] != 8 )
			{
				if($order_action == 'pick')
				{
					$updateArry = array(
					"complaint_picking_user"=> $action_user_id,
					"complaint_picking_time" => 'NOW()');
					if($same_user_to_pack_order == 1){
						$updateArry["complaint_packing_user"] = $action_user_id;
						$updateArry["complaint_packing_time"] = 'NOW()';
						$updateArry["complaint_is_outsourced"]= 0;
					}
					$complaint->update($updateArry);
					
					if($action_user_id != getLoginId())
					{
						$userTag = new UserTag();
						$userTag->insert(array(
								"tag_mentioner_id" 	=> getLoginId(), 
								"tag_user_id" 		=> $action_user_id, 
								"tag_module_code"	=> 'C', 
								"tag_module_id"		=> $complaint_id, 
								"tag_time"			=> 'NOW()', 
								"tag_is_readed"		=> 0, 
								"tag_type"			=> 'assigned picking by', 
								"tag_text"			=> $details['complaint_ticket_number'],
								"tag_log_id"		=> 0
							)
						);
					}
					
					Activity::add("marked <b>Picked</b> RMA Repair by <b>$actionUserData[user_name]</b>|^|{$details['complaint_ticket_number']}", "C", $complaint_id);
					if($same_user_to_pack_order == 1){
						if($action_user_id != getLoginId())
						{
							$userTag = new UserTag();
							$userTag->insert(array(
									"tag_mentioner_id" 	=> getLoginId(), 
									"tag_user_id" 		=> $action_user_id, 
									"tag_module_code"	=> 'C', 
									"tag_module_id"		=> $complaint_id, 
									"tag_time"			=> 'NOW()', 
									"tag_is_readed"		=> 0, 
									"tag_type"			=> 'assigned packing by', 
									"tag_text"			=> $details['complaint_ticket_number'],
									"tag_log_id"		=> 0
								)
							);
						}
						Activity::add("marked <b>Packed</b> RMA Repair by <b>$actionUserData[user_name]</b>", "C", $complaint_id);
					}
					echo json_encode(array("200",  "success|RMA Reapir marked as Picked".($same_user_to_pack_order ? " and Packed":"")));
				}
				elseif($order_action == 'pack')
				{
					if($details['complaint_picking_user'] != 0)
					{
						if($details['complaint_packing_user'] == 0 || $user_reset == md5($details['complaint_packing_user'])){
							$complaint->update(array(
							"complaint_packing_user"=> $action_user_id,
							"complaint_packing_time" => 'NOW()',
							"complaint_is_outsourced" => $complaint_is_outsourced
							));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'C', 
										"tag_module_id"		=> $complaint_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned packing by', 
										"tag_text"			=> $details['complaint_ticket_number'],
										"tag_log_id"		=> 0
									)
								);
							}
						
							Activity::add(($user_reset ? "Remarked":"marked")." <b>Packed</b> RMA Repair by <b>$actionUserData[user_name]</b>|^|{$details['complaint_ticket_number']}", "C", $complaint_id);
							echo json_encode(array("200",  "success|RMA Reapir ".($user_reset ? "Remarked":"marked")." as Packed"));
						}
						else{
							$employee = new Employee($details['complaint_packing_user']);
							echo json_encode(array("300",  "warning|RMA Reapir already Packed by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|RMA Reapir should be Picked first"));
					}
				}
				elseif($order_action == 'process')
				{
					if($details['complaint_packing_user'] != 0)
					{
						if($details['complaint_process_user'] == 0){
							$complaint->update(array(
							"complaint_process_user"=> $action_user_id,
							"complaint_process_time" => 'NOW()'));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'C', 
										"tag_module_id"		=> $complaint_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned processing by', 
										"tag_text"			=> $details['complaint_ticket_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add("marked <b>Processed</b> RMA Repair by <b>$actionUserData[user_name]</b>|^|{$details['complaint_ticket_number']}", "C", $complaint_id);
							echo json_encode(array("200",  "success|RMA Reapir marked as Process"));
						}
						else{
							$employee = new Employee($details['complaint_process_user']);
							echo json_encode(array("300",  "warning|RMA Reapir already Process by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|RMA Reapir should be Packed first"));
					}
				}
				elseif($order_action == 'cancel')
				{				
					$complaint->update(array(
					"complaint_cancel_user"=> $action_user_id,
					"complaint_cancel_time" => 'NOW()',
					"complaint_status" => 8
					));
					Activity::add("marked <b>Cancelled</b> RMA Repair by <b>$actionUserData[user_name]</b>|^|{$details['complaint_ticket_number']}", "C", $complaint_id);
					echo json_encode(array("200",  "success|RMA Reapir marked as Cancel"));
				}
				else
				{
					echo json_encode(array("300",  "warning|Invalid Action Found"));
				}				
			}
			else
			{				
				$employee = new Employee($details['complaint_cancel_user']);
				echo json_encode(array("300",  "warning|RMA Reapir already Cancel by ".$employee->get('user_fname')));
			}
		}
		else
		{
			echo json_encode(array("300",  "warning|RMA Reapir not exist"));
		}
	}
	else
	{
		echo json_encode(array("300",  "warning|RMA Reapir not found"));
	}

?>