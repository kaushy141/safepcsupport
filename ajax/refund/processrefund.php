<?php

	Modal::load(array('Refund', 'RefundProduct'));
    
    $refund_item_price =  array();
	$refund_id =  $refund_amount =  0;
	$refund_comments = '';
    $data = sanitizePostData($_POST);    
    extract($data);
	$refund = new Refund($refund_id);
	if($refund->isExist())
	{
		$refundData = $refund->getDetails();		
		$isFullRefund = true;				
		$refundSavedProducts = RefundProduct::getRefundProducts($refund_id);
		if(count($refund_item_price))
		{
			$isFullRefund = true;
			$netRefundAmount = 0;
			foreach($refund_item_price as $refund_pro_ref_id => $rAmount)
			{
				$refProduct = $refundSavedProducts[$refund_pro_ref_id];
				$refundProduct = new RefundProduct($refProduct['refund_pro_id']);
				if($rAmount > 0)
				{
					$netRefundAmount = $netRefundAmount + $rAmount;
					if($rAmount != $refProduct['refund_pro_sell_price'])
					{
						$isFullRefund = false;
					}
					$refundProduct->update( array( "refund_pro_refund_price"=>$rAmount));					
				}
				else
				{
					$refundProduct->remove();	
				}
			}
			if($netRefundAmount != $refund_amount)
			{
				echo json_encode(array("300",  "warning|Refunding amount mismatch by product's indivisual refund amount."));
				die;
			}
			$refund->update(array(
								"refund_amount"		=> $refund_amount,
								"refund_pattern"	=> $isFullRefund ? REFUND_STATUS_FULL: REFUND_STATUS_PARTIAL,
								"refund_process_by" => getLoginId(),
								"refund_process_date" => "NOW()",
								"refund_status" 	=> 4
								)
							);
			Activity::add("Processed|^|{$refundData['refund_code']}", "R", $refund_id);
		}
		if(trim($refund_comments) != "" && $refund->isExist())
		{
			$log = new ComplaintLog();
        	$log->add($refund_id, 'R', $refund_comments, 'TEXT', 1, 0);
		}		
		echo json_encode(array("200",  "success|Refund Processed", 'refundfinalise/'.md5($refund_id)));					
	}
	else
		echo json_encode(array("300",  "warning|Not a valid refund section"));

?>