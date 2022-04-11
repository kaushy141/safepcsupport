<?php

	Modal::load(array('Refund', 'Recharge'));
    
	$refund_status = 1;
	$refund_comments = '';
    $data = sanitizePostData($_POST);    
    extract($data);
	$refund = new Refund($refund_id);
	if($refund->isExist())
	{
		$refundData = $refund->getDetails();
		$status_action = 'unknown';
		if($refund_status == 1)
		{			
			$status_action = 'Completed';
			$recharge = new Recharge();
			$spendAmount = $recharge->getTodaysRefundAmount(getLoginId());
			$myBalance = $recharge->getUserCurrentBalance(getLoginId());
			if($myBalance < $refundData['refund_amount'])
			{				
				echo json_encode(array("300",  "warning|Insufficient Wallet Balance &pound;".($refundData['refund_amount'] - $myBalance)." is more required."));
				die;
			}	
			if($spendAmount + $refundData['refund_amount'] > PER_DAY_REFUND_LIMIT)
			{				
				echo json_encode(array("300",  "warning|Your Perday refund limit of &pound;".PER_DAY_REFUND_LIMIT." is exceeded. Only &pound;".(PER_DAY_REFUND_LIMIT - $spendAmount)." amount can be processed today"));
				die;
			}			
			$recharge->debitBalance(getLoginId(), $refundData['refund_amount']);
		}
		elseif($refund_status == 3)
		{
			$status_action = 'Cancelled';
		}
		$refund->update(array(
			"refund_completed_by" => getLoginId(),
			"refund_completed_date" => "NOW()",
			"refund_status" 	=> $refund_status
			)
		);
		if(trim($refund_comments) != "" && $refund->isExist())
		{
			$log = new ComplaintLog();
        	$log->add($refund_id, 'R', $refund_comments, 'TEXT', 1, 0);
		}
		Activity::add("$status_action|^|{$refundData['refund_code']}", "R", $refund_id);
		echo json_encode(array("200",  "success|Refund $status_action", 'viewrefund/'.md5($refund_id)));					
	}
	else
		echo json_encode(array("300",  "warning|Not a valid refund section"));

?>