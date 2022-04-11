<?php
	$eic_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($eic_id != 0)
	{
		$eic = new EmployeeInduction($eic_id);
		if($eic->isExist())
		{
			$record = $eic->getDetails();
			$eic->remove();
			$eic->clearItem();
			echo json_encode(array("200",  "success|Induction record deleted", $eic_id));
			Activity::add("deleted an Induction record.");			
			unlinkFile($record['eic_employee_signature']);
			unlinkFile($record['eic_hr_signature']);				
		}
		else
			echo json_encode(array("300", "danger|No Induction found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Induction."));

?>