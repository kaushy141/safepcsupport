<?php
	$eic_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($eic_id != 0)
	{
		$eic = new EmployeeInduction($eic_id);
		if($eic->isExist())
		{
			$eic->update(array("eic_submitted"=>1));
			echo json_encode(array("200",  "success|Induction submitted. and submitted to department", $eic_id));
			Activity::add("Submitted an Induction report to department.");
		}
		else
			echo json_encode(array("300", "danger|No Induction found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Induction."));

?>