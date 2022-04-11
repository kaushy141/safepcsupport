<?php
	$user_id = 0;
	$offsetId = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	
	if($user_id>0)
	{
		$message = new Message();
		$message->markMessageReaded();
		$records = $message->getUsersMessage($user_id, $offsetId);
		echo json_encode(array("200",  "success|Chat history loaded", $records));
	}
	else
		echo json_encode(array("300", "danger|Invalid user found."));

?>