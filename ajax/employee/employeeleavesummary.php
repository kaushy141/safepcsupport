<?php
	$leave_employee_id = 0;
	$leave_from_time = $leave_end_time = "";
	$data = sanitizePostData($_POST);
	extract($data);	
	if($leave_employee_id != 0)
	{
		$employee = new Employee($leave_employee_id);
		if($employee->isExist())
		{
			$empData = $employee->getDetails();
			$employeeLeave = new EmployeeLeave();
			$leaveData = $employeeLeave->getEmployeeSummary($leave_employee_id, $leave_from_time, $leave_end_time);			
			echo json_encode(array("200",  "success|Details Loaded", $leaveData));
		}
		else
			echo json_encode(array("300", "danger|No Record found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Record."));

?>