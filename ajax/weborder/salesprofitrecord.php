<?php
	Modal::load(array('SalesProfit'));    
	$interval = 'custom';
	$data  = sanitizePostData($_POST);	
	extract($data);
	if($interval){
		$from_date = $from_date != "" ? $from_date : null;
		$to_date = $to_date != "" ? $to_date : null;
		$currency = isset($filter['currency'])?$filter['currency']:'GBP';
		$website = isset($filter['store_id'])?$filter['store_id']:0;
		$salesProfit = new SalesProfit();
		$record = $salesProfit->getProfitSummary($interval, $from_date, $to_date, $currency, $website);
		echo json_encode(array("200",  "success|Record loaded", $record));
	}
	else
		echo json_encode(array("300",  "warning|Not a valid Web order"));

?>