<?php
	if(getLoginId())
	{
			
		$app->logOffUser();
		setcookie("LOGOFF", 	true, 	time() + (86400 * 1)); // 86400 = 1 day
		setcookie("LOGOFFTIME", time(), time() + (86400 * 1)); // 86400 = 1 day
		echo json_encode(array("200", "success|Log Off successfully", time()));
	}
	else
		echo json_encode(array("300",  "danger|Session expired. Login again."));

?>