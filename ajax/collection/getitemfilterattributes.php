<?php
	$wci_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($wci_id){
		$technician = CollectionProcess::getTechnicianFilteration($wci_id);
		$otherfilter = array();
		array_push($otherfilter, $technician);
		$wcItem = new WcItem($wci_id);
		if($record = $wcItem->getFilterAttributes())
			echo json_encode(array("200",  "success|Filteration loaded", $record, $otherfilter));
		else
			echo json_encode(array("300",  "warning|No Filteration found"));
	}
	else
		echo json_encode(array("300",  "warning|No Item found."));		
	

?>