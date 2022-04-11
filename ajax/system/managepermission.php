<?php

	$employee_id = $user_type_id = 0;
	$module_id = array();
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($user_type_id != 0 && !empty($module_id) && is_array($module_id))
	{
		$user_type = new UserType($user_type_id);
		if($user_type->isExist())
		{
			$userTypeData = $user_type->getDetails();
			$employee = new Employee($employee_id);
			if($employee_id !=0 && $employee->isExist())
			{
				$employeeData = $employee->getDetails();
				$newPermission = array_unique(array_diff($module_id, explode(",",$userTypeData['user_type_module_permission'])));
				asort($newPermission);
				$employee->update(array("user_module_access"=> implode(",",$newPermission)));
				echo json_encode(array("200",  "success|User $employeeData[user_fname] access permission updated"));
			}
			else
			{
				asort($module_id);
				$user_type->update(array("user_type_module_permission"=> implode(",", $module_id)));
				echo json_encode(array("200",  "success|User type $userTypeData[user_type_name] access permission updated"));
			}
		}
		else
			echo json_encode(array("300",  "danger|No User type found"));
	}
	else
		echo json_encode(array("300",  "danger|User type must be selected"));

?>