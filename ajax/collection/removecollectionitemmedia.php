<?php
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$wopm = new CollectionItemMedia($record_id);
		$recordData  = $wopm->getDetails();
		$wopm->remove();
		if(isset($recordData['image_path']) && $recordData['image_path'] != '')
			unlinkFile($recordData['image_path']);
		echo json_encode(array("200",  "success|Item image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No Item image found."));

?>