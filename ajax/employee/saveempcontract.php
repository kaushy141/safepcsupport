<?php

	$employee_id = $employee_status = $employee_upgrade_version = $employee_contract_store = $employee_is_zero_hour_contract = 0;
	$employee_name = $employee_address = $employee_country = $employee_email = $employee_phone = $employee_dob = $employee_id_card = $employee_salary_rate_mode = $employee_salary_rate_price = $employee_signature = $employee_job_title = $employee_contract_date = $employee_employment_date = $employee_salary_rate_currency = "";
	$employee_created_date =  $employee_modified_date = '';
	$employee_created_by = $_SESSION['user_id'];
	
	$data = sanitizePostData($_POST);
	extract($data);	
	$employee_id = intval($employee_id);
	$emp_array = compact('employee_contract_store', 'employee_name', 'employee_address', 'employee_country', 'employee_email', 'employee_phone', 'employee_dob', 'employee_salary_rate_mode', 'employee_salary_rate_price', 'employee_salary_rate_currency', 'employee_job_title', 'employee_contract_date', 'employee_employment_date', 'employee_is_zero_hour_contract');
	if($employee_upgrade_version == 1){
		$oriEmp = new ContractEmployee($employee_id);
		$oriData = $oriEmp->getDetails();
		$employee_id = 0;
	}
	$employee = new ContractEmployee($employee_id);
	if($employee_id == 0){
		$emp_array['employee_created_date'] = 'NOW()'; 
		$emp_array['employee_modified_date'] = 'NOW()'; 
		$emp_array['employee_status'] = '1'; 
		$employee_id = $employee->insert($emp_array);		
	}
	else{
		$employee->update($emp_array);
	}
	$record = $employee->getDetails();
	if ($employee_id_card != "") {
		$employee_id_card_path = "upload/employee/id/".getDirectorySeparatorPath()."$employee_id-" . time() . ".".pathinfo($employee_id_card, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($employee_id_card), $app->sitePath($employee_id_card_path))){
			unlinkFile($record['employee_id_card']);	
			$employee->update(array("employee_id_card" => $employee_id_card_path));
		}
	}
	elseif($employee_upgrade_version == 1 && $oriData['employee_id_card'] != ""){
		$employee_id_card_path = "upload/employee/id/".getDirectorySeparatorPath()."$employee_id-" . time() . ".".pathinfo($oriData['employee_id_card'], PATHINFO_EXTENSION);
		if (move_file($app->sitePath($oriData['employee_id_card']), $app->sitePath($employee_id_card_path))){
			$employee->update(array("employee_id_card" => $employee_id_card_path));
		}
	}

	if ($employee_signature != "") {
		$employee_signature_path = "upload/employee/sign/".getDirectorySeparatorPath()."$employee_id-" . time() . ".".pathinfo($employee_signature, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($employee_signature), $app->sitePath($employee_signature_path))){
			unlinkFile($oriData['employee_signature']);	
			$employee->update(array("employee_signature" => $employee_signature_path));
		}
	}elseif($employee_upgrade_version == 1 && $oriData['employee_signature'] != ""){
		$employee_signature_path = "upload/employee/sign/".getDirectorySeparatorPath()."$employee_id-" . time() . ".".pathinfo($oriData['employee_signature'], PATHINFO_EXTENSION);
		if (move_file($app->sitePath($record['employee_signature']), $app->sitePath($employee_signature_path))){
			$employee->update(array("employee_signature" => $employee_signature_path));
		}
	}	
	echo json_encode(array("200",  "success|Employee record saved", $employee_id));
?>