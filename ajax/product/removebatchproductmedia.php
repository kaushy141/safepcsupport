<?php

	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$bpm = new BatchProductMedia($record_id);
		$record = $bpm->getDetails();
		$bpm->remove();
		unlinkFile($record['image_path']);
		echo json_encode(array("200",  "success|Batch product image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No Batch product image found."));	

?>