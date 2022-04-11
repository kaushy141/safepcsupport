<?php
	
	$user_tag_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$userTag = new UserTag();
	$records = $user_tag_id  ? $userTag->getUserNewTag($user_tag_id) : $userTag->getUserTag();
	echo json_encode(array("200", "success|Tag record fetched", "data"=>$records));

?>