<?php
	$user_id = $user_month = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($user_id != "" && $user_month != "")
	{
		Modal::load(array('SalesCommission'));
		$salesCommission = new SalesCommission();		
		if(!$salesCommission->isCommissionExist($user_id, date("Y-m-d", strtotime($user_month))))
		{			
			$user_month = date("Y-m", strtotime($user_month));
			$records = SalesCommission::getUserCompletedCommisionList($user_id);
			echo json_encode(array("200",  "success|Sales Records loaded", $records, $user_id, date("Y-m-d", strtotime($user_month))));
		}
		else
			echo json_encode(array("300", "danger|User already alloted commission for this month."));
	}
	else
		echo json_encode(array("300", "danger|No user found."));

?>