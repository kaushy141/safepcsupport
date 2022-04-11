<?php
Modal::load(array('ChaseCustomer'));
	$customer_id = $mark_mailed = $mark_called = 0;
	$comment_text = $activity_action = "";
	$mentiony = array();
	$data           = sanitizePostData($_POST);
    extract($data);
	$chaseCustomer = new ChaseCustomer($customer_id);
	$customerData = $chaseCustomer->getDetails();
	if($chaseCustomer->isExist()){
		$customerRecord = array();
		$activityAction = array();
		if($mark_mailed){
			$customerRecord['chase_customer_last_email_on'] = 'NOW()';
			$activityAction[] = "Mailed";
		}
		if($mark_called){
			$customerRecord['chase_customer_last_call_on'] = 'NOW()';
			$activityAction[] = "Phone Call";
		}
		$customerRecord['chase_customer_last_time'] = 'NOW()';
		$chaseCustomer->update($customerRecord);
		$chaseCustomerRecord = new ChaseCustomerRecord();
		$chaseComments = array(
			'ccr_customer_id' => $customer_id,
			'ccr_action' => empty($activityAction) ? "No action" : implode("|", $activityAction),
			'ccr_comments' => $comment_text,
			'ccr_date' => 'NOW()',
			'ccr_user_id' => getLoginId(),
			'ccr_is_mailed' => $mark_mailed,
			'ccr_is_called' => $mark_called
		);
		$ccr_id = $chaseCustomerRecord->insert($chaseComments);
		
		if(!empty($mentiony)){
			$usedTagUser = array();
			foreach($mentiony as $_user){
				if(!in_array($_user['id'], $usedTagUser))
				{
					$userTag = new UserTag();
					$userTag->insert(array(
							"tag_mentioner_id" 	=> getLoginId(), 
							"tag_user_id" 		=> $_user['id'], 
							"tag_module_code"	=> 'G', 
							"tag_module_id"		=> $ccr_id, 
							"tag_time"			=> 'NOW()', 
							"tag_is_readed"		=> 0, 
							"tag_type"			=> 'tagged', 
							"tag_text"			=> $ccr_id,
							"tag_log_id"		=> 0
						)
					);
					array_push($usedTagUser, $_user['id']);
				}
			}
		}
		
		
		Activity::add("add comments|^|{$customerData['chase_customer_name']}", "H", $customer_id);
		echo json_encode(array("200",  "success|Customer'log saved"));
	}
	else{
		echo json_encode(array("300",  "success|Requested customer not found"));
	}

?>