<?php

	$eil_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($eil_id != 0)
	{
		$eil = new EmployeeLeaver($eil_id);
		if($eil->isExist())
		{
			$eil->update(array("eil_submitted"=>1));
			echo json_encode(array("200",  "success|Leaver submitted. and submitted to department", $eil_id));
			Activity::add("Submitted an Leaver report to department.");
		}
		else
			echo json_encode(array("300", "danger|No Leaver found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Leaver."));

?>