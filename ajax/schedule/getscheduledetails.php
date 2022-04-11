<?php
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

?>