<?php
function createrecharge()
{
	Modal::load(array('Recharge'));
    global $app;
    $recharge_trans_amount =  0;
	$recharge_trans_user_id =  0;
    $data = sanitizePostData($_POST);    
    extract($data);
	$recharge = new Recharge();
	$employee = new Employee($recharge_trans_user_id);
	if($recharge_trans_amount >0 && $employee->isExist())
	{
		$employeeData = $employee->getDetails();
		if($employeeData['user_status'] == 1)
		{
			$recharge->creditBalance($recharge_trans_user_id, $recharge_trans_amount);
			$recharegData = $recharge->getdetails();
			echo json_encode(array("200",  "success|Recharge for amount $recharge_trans_amount is successfull. Transaction code is <b>$recharegData[recharge_trans_code]</b>"));
		}
		else
			echo json_encode(array("300",  "warning|User <b>$employeeData[user_name]</b> is not eligible for recharge"));		
	}
	else
		echo json_encode(array("300",  "warning|Recharge amount is not valid"));
	
}
?>