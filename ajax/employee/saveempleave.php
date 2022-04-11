<?php

	$leave_id = 0;
	$leave_status = 1;
	$leave_employee_id = $leave_from_time = $leave_end_time = $leave_reason = "";
	$leave_is_approved = $leave_is_extra_hours = 0;
	
	$data = sanitizePostData($_POST);
	extract($data);	
	$label = $leave_is_extra_hours ? "Extra hours" : "Leave";
	$leave_from_time 	= date("Y-m-d H:i:s", strtotime($leave_from_time));
	$leave_end_time 	= date("Y-m-d H:i:s", strtotime($leave_end_time));
	$leave_array = compact('leave_employee_id', 'leave_from_time', 'leave_end_time', 'leave_is_approved', 'leave_reason', 'leave_is_extra_hours');
	$employee = new Employee($leave_employee_id);
	$empData = $employee->getDetails();
	$leave = new EmployeeLeave($leave_id);
	if($leave_id == 0){
		$leave_array['leave_created_date'] = 'NOW()';
		$leave_id = $leave->insert($leave_array);
		Activity::add("added Employee <b>$empData[user_name]</b> $label");
	}
	else{
		$leave->update($leave_array);
		Activity::add("Updated Employee <b>$empData[user_name]</b> $label");
	}	
	echo json_encode(array("200",  "success|$label record saved", $leave_id));

?>