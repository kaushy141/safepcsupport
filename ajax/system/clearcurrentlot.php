<?php
	
	if(isset($_SESSION['LOT']))
	unset($_SESSION['LOT']);
	echo json_encode(array("200", "success|Current lot cleared"));	

?>