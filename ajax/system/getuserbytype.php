<?php
	$user_type_id = 0;
	
	$data  = sanitizePostData($_POST);
	extract($data);
	$userType = new UserType($user_type_id);
	$userTypeModuleData = $userType->get("user_type_module_permission");	
	$Employee = new Employee(0);
	$EmployeeData = $Employee->getOptions($user_type_id);	
	echo json_encode(array("200",  "success|Users list loaded", $EmployeeData, $userTypeModuleData));

?>