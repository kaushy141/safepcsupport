<?php
	
	$user_tag_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$userTag = new UserTag($user_tag_id);
	$userTag->update(array("tag_read_time"=>"NOW()"));
	$userTag->Activate();
	echo json_encode(array("200", "success|Mark readed"));

?>