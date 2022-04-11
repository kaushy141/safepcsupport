<?php
	$user_id = $user_month = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($user_id != "" && $user_month != "")
	{
		$user = new Employee($user_id);
		if($user->isExist())
		{
			$user_month = date("Y-m", strtotime($user_month));
			$records = Activity::getUserWorkByMonth($user_month, $user_id);
			echo json_encode(array("200",  "success|Work record loaded for ".date("M Y", strtotime($user_month."-01")), $records, cal_days_in_month(CAL_GREGORIAN,date("m", strtotime($user_month."-01")),date("Y", strtotime($user_month."-01"))), date("M", strtotime($user_month."-01")), $user_month, date("Y-m", strtotime($user_month))));	
		}
		
	}
	else
		echo json_encode(array("300", "danger|No user found."));

?>