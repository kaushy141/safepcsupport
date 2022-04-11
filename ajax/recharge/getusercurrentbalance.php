<?php

	Modal::load(array('Recharge'));
    
    $user_id =  0;
    $data = sanitizePostData($_POST);    
    extract($data);
	$recharge = new Recharge();
	$employee = new Employee($user_id);
	if($user_id >0 && $employee->isExist())
	{
		$employeeData = $employee->getDetails();
		if($employeeData['user_status'] == 1)
		{
			echo json_encode(array("200",  "success|Balance loaded", $recharge->getUserCurrentBalance($user_id)));
		}
		else
			echo json_encode(array("300",  "warning|User <b>$employeeData[user_name]</b> is not eligible for taking balance"));		
	}
	else
		echo json_encode(array("300",  "warning|User is not valid"));

?>