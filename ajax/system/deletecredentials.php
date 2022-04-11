<?php

	$credentials_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$credentials = new Credentials($credentials_id);
	if($credentials->canEdit())
	{
		$credentials->remove();
		echo json_encode(array("200", "success|Crentials deleted successfully"));		
	}
	else
	echo json_encode(array("300",  "danger|Credentials not permissable"));

?>