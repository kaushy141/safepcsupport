<?php
	$leave_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($leave_id != 0)
	{
		$leave = new EmployeeLeave($leave_id);
		if($leave->isExist())
		{
			$record = $leave->getDetails();
			$employee = new Employee($record['leave_employee_id']);
			$empData = $employee->getDetails();
			$label = $record['leave_is_extra_hours'] ? "Extra hours" : "Leave";
			$leave->remove();
			echo json_encode(array("200",  "success|$label Record deleted", $leave_id));
			Activity::add("deleted Employee <b>$empData[user_name]</b> $label");
		}
		else
			echo json_encode(array("300", "danger|No Record found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Record."));

?>