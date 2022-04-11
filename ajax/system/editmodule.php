<?php

	$module_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$navbar = new Navbar($module_id);
	if($module_id != 0 && $navbar->isExist())
	{
		echo json_encode(array("200", "success|Module loaded", $navbar->load()));
		
	}
	else
		echo json_encode(array("300",  "danger|Module not found"));

?>