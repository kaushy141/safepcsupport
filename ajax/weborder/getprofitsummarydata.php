<?php
	$interval = '7';
	$data  = sanitizePostData($_POST);	
	extract($data);
	if($interval){
		$profitSummary = new ProfitSummary();
		$record = $profitSummary->getProfitSummary($interval);
		echo json_encode(array("200",  "success|Record loaded", $record));
	}
	else
		echo json_encode(array("300",  "warning|Not a valid Web order"));

?>