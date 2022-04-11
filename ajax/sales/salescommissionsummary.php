<?php
	$sales_commission_batch = '';
	$data = sanitizePostData($_POST);
	extract($data);	
	if($sales_commission_batch != "")
	{
		Modal::load(array('SalesCommission'));
		$salesCommission = new SalesCommission();		
		if($records = $salesCommission->getCommissionDetails($sales_commission_batch))
		{
			echo json_encode(array("200",  "success|Sales commission details loaded", $records));
		}
		else
			echo json_encode(array("300", "danger|No Commission records found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid commission."));

?>