<?php

	$complaint_log_id = 0;
	$complaint_log_text = "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if(intval($complaint_log_id) != 0 && $complaint_log_text != ""){
		$complaintLog = new ComplaintLog($complaint_log_id);	
		if($complaintLog->isExist()){
			$logRecord = $complaintLog->getDetails();
			if((isAdmin() && $logRecord['complaint_user_id'] != 0) || (!isAdmin() && $logRecord['complaint_customer_id'] != 0)){
				if(isAdmin() || ($logRecord['complaint_customer_id'] == getLoginId())){
					$complaint_user_id 		= USER=="ADMIN"?getLoginId():0;
					$complaint_customer_id 	= USER=="ADMIN"?0:getLoginId();
					$complaintLog->saveHistory();
					$complaintLog->update(
						array(
							"complaint_log_text" 	=> $complaint_log_text,
							"complaint_user_id"		=> $complaint_user_id,
							"complaint_customer_id" => $complaint_customer_id,
							"complaint_log_time"	=> "NOW()"
						)
					);
					/*
					$record = array
						(
							'msg_format' 	=> 'module',
							'logger_id' 	=> getLoginId(),
							'logger_name' 	=> isAdmin() ? $_SESSION['user_name'] : $_SESSION['customer_name'],
							'logger_image' 	=> $app->imagePath(isAdmin() ? $_SESSION['user_image'] : $_SESSION['customer_image']),
							'logger_type' 	=> getUserType(),
							'complaint_log_text' 	=> htmlspecialchars($complaint_log_text),
							'complaint_id'	=>$logRecord['complaint_id'],
							'complaint_log_id'=>$complaint_log_id,
							'complaint_format'=>$logRecord['complaint_format'],
							'log_time'  	=> dateView(date('Y-m-d H:i:s'),'NOW'),
							'title'			=> "Message From ".(isAdmin() ? $_SESSION['user_name'] : $_SESSION['customer_name']),
							'sound'			=> 1,
							'complaint_log_privacy' => $logRecord['complaint_log_privacy'],
							'largeIcon'		=> $app->imagePath(isAdmin() ? $_SESSION['user_image'] : $_SESSION['customer_image']),
							'smallIcon'		=> $app->imagePath(isAdmin() ? $_SESSION['user_image'] : $_SESSION['customer_image']),
						);
					*/
					echo json_encode(array("200", "success|Comment log updated successfully", $complaintLog->getLog($logRecord['complaint_id'], $logRecord['complaint_format'], $complaint_log_id)));
						
				}else
					echo json_encode(array("300",  "danger|You can't update else comment than this."));
			}else
				echo json_encode(array("300",  "danger|You are not authorised to update this comment."));
		}else
			echo json_encode(array("300",  "danger|Requested log record not found"));
	}else
		echo json_encode(array("300",  "danger|Input format is not correct."));

?>