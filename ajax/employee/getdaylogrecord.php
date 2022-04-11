<?php
	$user_id = $date = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($user_id != 0 && $date != 0)
	{
		$user = new Employee($user_id);
		if($user->isExist())
		{
			$records = Activity::getUserWorkByDay($date, $user_id);
			echo json_encode(array("200",  "success|Day Work record loaded", $records, date("d-M Y", strtotime($date))));	
		}		
	}
	else
		echo json_encode(array("300", "danger|No Work record found."));

?>