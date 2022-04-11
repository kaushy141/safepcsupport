<?php
	$data = sanitizePostData($_POST);
	extract($data);	
	$timeZone = $_REQUEST['tz'];
	$start = date("Y-m-d", strtotime(date("Y-m-d", strtotime($_REQUEST['start'])) . " -7 day"));
	$end = date("Y-m-d", strtotime(date("Y-m-d", strtotime($_REQUEST['end'])) . " +7 day"));
	$Employee = new Employee($user_id);
	echo $Employee->getAttendaceList($user_id, $start, $end, $timeZone);	

?>