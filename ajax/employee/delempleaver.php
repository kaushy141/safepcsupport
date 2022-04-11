<?php
	
	
	$eil_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($eil_id != 0)
	{
		$eil = new EmployeeLeaver($eil_id);
		if($eil->isExist())
		{
			$record = $eil->getDetails();
			$eil->remove();
			$eil->clearItem();
			echo json_encode(array("200",  "success|Leaver record deleted", $eil_id));
			Activity::add("deleted an Leaver record.");
			unlinkFile($record['eil_employee_signature']);
			unlinkFile($record['eil_hr_signature']);
		}
		else
			echo json_encode(array("300", "danger|No Leaver found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Leaver."));

?>