<?php
	$user_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($user_id)
	{
		$location = new Location();
		$records = $location->getDetailsByUser($user_id, 20);
		if($records)	
			echo json_encode(array("200",  "success|location found", $records));
		else
			echo json_encode(array("300", "danger|No location record found."));
	}
	else
		echo json_encode(array("300", "danger|No user found."));

?>