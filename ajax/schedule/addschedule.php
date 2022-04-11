<?php

    $schedule_status  = 0;
    $schedule_title   = $schedule_due_date = $customer_email = $schedule_scope = "";
	$mentiony = array();
    $crm_task_status  = 1;
    $schedule_user_id = getLoginId();
    $data             = sanitizePostData($_POST);
    extract($data);
    $Customer = new Customer(0);
    if (!$schedule_customer_id = $Customer->getIdByEmail(md5(trim(strtolower($customer_email)))))
        $schedule_customer_id = 0;
    $Schedule    = new Schedule();
    $schedule_id = $Schedule->add($schedule_user_id, $schedule_customer_id, $schedule_title, $schedule_scope, $schedule_due_date, $schedule_status);
	$taggingData = "";
			if(!empty($mentiony)){
				$taggingData .= " and tagged to ";
				$usedTagUser = array();
				foreach($mentiony as $_user){
					if(!in_array($_user['id'], $usedTagUser))
					{
						$userTag = new UserTag();
						$userTag->insert(array(
								"tag_mentioner_id" 	=> getLoginId(), 
								"tag_user_id" 		=> $_user['id'], 
								"tag_module_code"	=> 'A', 
								"tag_module_id"		=> $schedule_id, 
								"tag_time"			=> 'NOW()', 
								"tag_is_readed"		=> 0, 
								"tag_type"			=> 'tagged', 
								"tag_text"			=> $schedule_id,
								"tag_log_id"		=> 0
							)
						);
						$taggingData .= "<b class=\"badge badge-info\">{$_user['name']}</b> ";
						array_push($usedTagUser, $_user['id']);
					}
				}
				$taggingData .= "on ";
			}
    Activity::add("added New Schedule");
    echo json_encode(array("200",  "success|Schedule created Successfully",
        $schedule_id
    ));

?>