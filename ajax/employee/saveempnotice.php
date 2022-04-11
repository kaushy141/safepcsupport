<?php

	$notice_id = $notice_emp_id = $notice_count = $notice_status = 0;
	$notice_reason = $notice_date = $notice_attachement = '';
	
	$data = sanitizePostData($_POST);
	extract($data);	
	$notice_date 	= date("Y-m-d H:i:s", strtotime($notice_date));
	$notice_array = compact('notice_emp_id', 'notice_reason', 'notice_date', 'notice_status');
	
	$notice = new EmployeeNotice($notice_id);
	if($notice_id == 0){
		$notice_array['notice_created_date'] = 'NOW()';
		$notice_id = $notice->insert($notice_array);		
	}
	else{
		$notice->update($notice_array);
	}
	$notice_count = $notice->getEmpNoticeCount($notice_emp_id);
	$notice->update(compact('notice_count'));
	
	$record = $notice->getDetails();
	
	if ($notice_attachement != "") {
		$notice_attachement_path = "upload/notice/".getDirectorySeparatorPath()."$notice_id-" . time()  . ".".pathinfo($notice_attachement, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($notice_attachement), $app->sitePath($notice_attachement_path))){	
			$notice->update(array("notice_attachement" => $notice_attachement_path));
			unlinkFile($record['notice_attachement']);
		}
	}	
	echo json_encode(array("200",  "success|Notice record saved", $eil_id));

?>