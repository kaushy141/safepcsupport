<?php
	if(getLoginId())
	{	
		$app->logInSignInuser();
		setcookie("LOGOFF", 	false, 	time() - (86400 * 1)); // 86400 = 1 day
		echo json_encode(array("200", "success|Sign in successfully", time()));
	}
	else
		echo json_encode(array("300",  "danger|Session expired. Login again."));

?>