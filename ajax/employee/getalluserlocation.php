<?php
	$timezone = date('Z');
	$data = sanitizePostData($_POST);
	extract($data);	
	$location = new Location();
	$records = $location->getAllUsersLiveLocation($timezone);
	if($records)	
		echo json_encode(array("200",  "success|location found", $records));
	else
		echo json_encode(array("300", "danger|No location found."));

?>