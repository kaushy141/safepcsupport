<?php
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$wopm = new SalesMedia($record_id);
		$wopm->update(array("sales_image_status"=>0));
		echo json_encode(array("200",  "success|Sales invoice image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No Sales invoice image found."));	

?>