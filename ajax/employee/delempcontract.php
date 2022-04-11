<?php
	$employee_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($employee_id != 0)
	{
		$employee = new ContractEmployee($employee_id);
		if($employee->isExist())
		{
			$employee->remove();
			echo json_encode(array("200",  "success|Employee record deleted", $employee_id));
		}
		else
			echo json_encode(array("300", "danger|No Employee found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Employee."));

?>