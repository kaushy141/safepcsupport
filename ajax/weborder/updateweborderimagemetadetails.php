<?php

	$image_id = 0;
	$image_title = "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if($image_id){		
		$wopm = new WebsiteOrderProductMedia($image_id);
		$wopm->update(array("wpoi_title"=>$image_title));
		echo json_encode(array("200",  "success|Weborder product image details updated"));
	}
	else{
		echo json_encode(array("300",  "warning|Image not found"));
	}

?>