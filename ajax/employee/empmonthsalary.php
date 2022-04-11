<?php

	$user_id = $month_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($user_id>0 && $month_id>0)
	{
		$month_name = date("M-Y", strtotime("20".substr($month_id,0,2)."-".substr($month_id,2,2)."-01"));
		$employee = new Employee($user_id);
		if($empdata = $employee->getDetails())
		{
			$empdata['month_name'] = $month_name;
			$contract = new Contract();
			if($contractRecord = $contract->getDetailsByUser($user_id))
			{			
				if($contractRecord['user_pay_status']==1)
				{
					$slr = new SalaryRegister();
					if($registerData = $slr->isMprGenerated($user_id, $month_id))
					{
						$contractRecord['paid_option'] = getOptionPaid($registerData['pay_slip_paid_status']);
						$contractRecord['paid_paymode'] = getOptionPayMode($registerData['pay_slip_pay_mode']);
						echo json_encode(array("200",  "success|MPR Detail", array_merge($empdata, $contractRecord, $registerData), "contractRecord"=>$contractRecord));
					}
					else
					{
						$contractRecord['paid_option'] = getOptionPaid();
						$contractRecord['paid_paymode'] = getOptionPayMode();
						echo json_encode(array("200",  "success|MPR Form", array_merge($empdata, $contractRecord)));
					}
						
				}else
					echo json_encode(array("300", "danger|Employee Contract status dissabled"));
			}else
        		echo json_encode(array("300", "danger|Contract not Created. Please created Contract first."));
		}else
        	echo json_encode(array("300", "danger|Employee not exist."));
	}else
        echo json_encode(array("300", "danger|Requested Data not Found."));

?>