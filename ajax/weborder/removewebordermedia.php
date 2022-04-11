<?php
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$wopm = new WebsiteOrderProductMedia($record_id);
		$wopm->update(array("wpoi_status"=>0));
		echo json_encode(array("200",  "success|Weborder product image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No weborder image found."));	

?>