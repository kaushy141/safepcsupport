<?php
function addschedule()
{
    global $app;
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
}

function getscheduledetails(){
	$id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$schedule = new Schedule($id);
	if($schedule->isExist()){
		if($schedule->isPermission()){
			$scheduleData = $schedule->getDetails();
			$scheduleData['tags'] = UserTag::getModuleTag('A', $id, true);
			
			$comments = '<div class="comments_box">';
			$complaintLog = new ComplaintLog();
			if($commentsLog = $complaintLog->getLog($id, 'A')){
				foreach($commentsLog as $_comments)
				$comments .= ComplaintLog::drawLogRecord($_comments);
			}
			else{
				$comments .= '<p class="text-center">No comments added yet.</p>';
			}
			$comments .= '</div>';
			$scheduleData['comments'] = $comments;
			echo json_encode(array("200",  "warning|Schedule details loaded", $scheduleData));
		}
		else
			echo json_encode(array("300",  "warning|Ooops..Schedule you have requested not permited"));
	}
	else
		echo json_encode(array("300",  "warning|Ooops..Schedule you have requested not found"));
}

function updateschedule()
{
    global $app;
    $schedule_status = $keyid = 0;
    $schedule_title  = $schedule_due_date = $customer_email = $schedule_scope = "";
    $crm_task_status = 1;
    $data            = sanitizePostData($_POST);
    extract($data);
    $Customer = new Customer(0);
    if (!$schedule_customer_id = $Customer->getIdByEmail(md5(trim(strtolower($customer_email)))))
        $schedule_customer_id = 0;
    if ($keyid > 0) {
        $Schedule = new Schedule($keyid);
        $Schedule->update(array("schedule_customer_id" => $schedule_customer_id,
            "schedule_title" => $schedule_title,
            "schedule_scope" => $schedule_scope,
            "schedule_due_date" => $schedule_due_date,
            "schedule_status" => $schedule_status,
            "schedule_mark_as_read" => 0
        ));
        Activity::add("Updated a Schedule");
		$email = new Email($app->siteName . " : " . "Your Schedule updated");
		$email->to("test-e6e34to1l@srv1.mail-tester.com", "Tester");
		$email->send("You have successfully updated Scheduled on " . $app->siteName . " w.e.f. " . date('d M Y h:i:s A'));
        echo json_encode(array("200",  "success|Schedule Updated Successfully"
        ));
    }
}
function schedulenotif()
{
    global $app;
    $Schedule      = new Schedule(0);
    $notifications = $Schedule->fetchNotification();
    echo json_encode(array("200",  "data" => $notifications
    ));
}
function markasread()
{
    global $app;
    $schedule_id = 0;
    $data        = sanitizePostData($_POST);
    extract($data);
    if ($schedule_id > 0) {
        $Schedule = new Schedule($schedule_id);
        $Schedule->update(array("schedule_mark_as_read" => 1
        ));
        echo json_encode(array("200",  "success|Schedule marked as read"
        ));
    } else
        echo json_encode(array("200",  "warning|Ooops..couldn't marked as read"
        ));
}
?>