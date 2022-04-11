<?php

	$user_filter_state = 1;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$user_filter_state = 1 - $user_filter_state;
	$user = new Employee($_SESSION['user_id']);
	$_SESSION['app_filter_state'] = $user_filter_state;	
	$user->update(array('user_filter_state'=> $user_filter_state));
	echo json_encode(array("200", "success|Filter State Applied successfully"));

?>