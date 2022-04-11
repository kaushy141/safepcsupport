<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$ChecklistReport = new ChecklistReport($id);
	if($ChecklistReport->isActive())
	{
		$record = $ChecklistReport->getDetails();	
		$ChecklistReport->Deactivate();
		Activity::add("Removed Checklist report <b>$record[checklist_name]</b>","", $id);
		echo json_encode(array("200",  "success|Checklist report removed Successfully"));
		
	}
	else
		echo json_encode(array("300",  "warning|Checklist report not found."));

?>