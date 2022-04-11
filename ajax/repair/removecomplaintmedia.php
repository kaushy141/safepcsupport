<?php
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$wopm = new ComplaintMedia($record_id);
		$wopm->update(array("repair_image_status"=>0));
		echo json_encode(array("200",  "success|Repair request image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No Repair request image found."));	

?>