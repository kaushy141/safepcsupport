<?php
	if($id = isAdmin())
	{
		$employee = new Employee($id);
		$employee->setLive();
	}
	$liveUser = Employee::getLiveUser();
	echo json_encode(array("200",  "success|User Loaded", $liveUser));

?>