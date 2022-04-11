<?php

    $user_pay_working_hours = $user_pay_joining_date = $user_pay_salary_invoicing = $user_appointment_issue_date = $user_father_name = $user_cnic_number = $user_payment_currency = $user_reporting_person = $user_working_time = $user_department_name = $user_cont_file_path = $user_char_exp_file_path = "";
	$user_pay_sales_commision = $user_pay_salary  = $user_pay_previous_balance = $user_pay_user_id =  $user_contract_store = $user_pay_working_days = $user_pay_id = $send_email_to_employee = 0;
	$user_pay_status = $user_appointment_upgrade_version = 0;
    $data         = sanitizePostData($_POST);
    extract($data);
	if($user_pay_user_id != 0) 
	{        
		$employee = new Employee($user_pay_user_id);
		if ($empdata = $employee->getDetails()) 
		{
			$values = array(
				"user_pay_user_id" => $user_pay_user_id,
				"user_contract_store"=> $user_contract_store,
				"user_pay_working_hours" => $user_pay_working_hours,
				"user_pay_joining_date" => $user_pay_joining_date,
				"user_pay_salary_invoicing" => $user_pay_salary_invoicing,
				"user_pay_sales_commision" => $user_pay_sales_commision,
				"user_pay_salary" => $user_pay_salary,
				"user_pay_previous_balance" => $user_pay_previous_balance,
				"user_pay_working_days" => $user_pay_working_days,
				"user_pay_status" => $user_pay_status,
				"user_appointment_issue_date" => $user_appointment_issue_date,
				"user_father_name" => $user_father_name,
				"user_cnic_number" => $user_cnic_number,
				"user_department_name" => $user_department_name,
				"user_payment_currency" => $user_payment_currency,
				"user_reporting_person" => $user_reporting_person,
				"user_working_time" => $user_working_time
			);
			if($user_appointment_upgrade_version == 1){
				$oriCont = new Contract($user_pay_id);
				$oriData = $oriCont->getDetails();
				$user_pay_id = 0;
			}
			if ($user_pay_id == 0) {
				$values = array_merge($values, array(
														"user_pay_created_date" => "NOW()",
														"user_pay_status" => $user_pay_status
													)
									 );					
				$contract = new Contract(0);
				$user_pay_id = $contract->insert($values);
			}
			else{
				$contract = new Contract($user_pay_id);
				$contract->update($values);
			}
			
			
			$record = $contract->getDetails();
			if ($user_cont_file_path != "") {
				$user_cont_file_path_path = "upload/user/contract/".getDirectorySeparatorPath()."$user_pay_user_id-" . time() . ".".pathinfo($user_cont_file_path, PATHINFO_EXTENSION);
				if (move_file($app->sitePath($user_cont_file_path), $app->sitePath($user_cont_file_path_path))){
					if($user_appointment_upgrade_version != 1)
					unlinkFile($record['user_pay_contract_file']);	
					$contract->update(array("user_pay_contract_file" => $user_cont_file_path_path));
				}
			}
			elseif($user_appointment_upgrade_version == 1 && $oriData['user_pay_contract_file'] != ""){
				$user_cont_file_path_path = "upload/user/contract/".getDirectorySeparatorPath()."$user_pay_user_id-" . time() . ".".pathinfo($oriData['user_pay_contract_file'], PATHINFO_EXTENSION);
				if (move_file($app->sitePath($oriData['user_pay_contract_file']), $app->sitePath($user_cont_file_path_path))){
					$contract->update(array("user_pay_contract_file" => $user_cont_file_path_path));
				}
			}
			
			/*
			if ($user_cont_file_path != "") {
				$file_name = pathinfo(BP.$user_cont_file_path);
                $extension  = strtolower($file_name['extension']);
				$user_pay_contract_file = "upload/user/contract/".getDirectorySeparatorPath()."$user_pay_user_id-" . time() . ".$extension";
				if (move_file($app->sitePath($user_cont_file_path), $app->sitePath($user_pay_contract_file)))
					$contract->update(array("user_pay_contract_file" => $user_pay_contract_file
					));
			}
			*/
			
			if ($user_char_exp_file_path != "") {
				$user_char_exp_file = "upload/user/charexp/".getDirectorySeparatorPath()."$user_pay_user_id-" . time() . ".".pathinfo($user_cont_file_path, PATHINFO_EXTENSION);
				if (move_file($app->sitePath($user_char_exp_file_path), $app->sitePath($user_char_exp_file))){
					if($user_appointment_upgrade_version != 1)
					unlinkFile($record['user_char_exp_file']);	
					$contract->update(array("user_char_exp_file" => $user_char_exp_file));
				}
			}
			elseif($user_appointment_upgrade_version == 1 && $oriData['user_char_exp_file'] != ""){
				$user_char_exp_file = "upload/user/charexp/".getDirectorySeparatorPath()."$user_pay_user_id-" . time() . ".".pathinfo($oriData['user_char_exp_file'], PATHINFO_EXTENSION);
				if (move_file($app->sitePath($oriData['user_char_exp_file']), $app->sitePath($user_char_exp_file))){
					$contract->update(array("user_char_exp_file" => $user_char_exp_file));
				}
			}
			/*
			if ($user_char_exp_file_path != "") {
				$file_name = pathinfo(BP.$user_char_exp_file_path);
                $extension  = strtolower($file_name['extension']);
				$user_char_exp_file = "upload/user/charexp/".getDirectorySeparatorPath()."$user_pay_user_id-" . time() . ".$extension";
				if (move_file($app->sitePath($user_char_exp_file_path), $app->sitePath($user_char_exp_file)))
					$contract->update(array("user_char_exp_file" => $user_char_exp_file
					));
			}
			*/
			
			new SMS($empdata['user_phone'], "Hi, $empdata[user_fname] Welcome to " . $app->siteName . " as a $empdata[user_type_name] contract updated.");
			
			if($send_email_to_employee)
			{
				$dataArray = array_merge($values, array(
					"user_name" => $empdata['user_fname'],
					"user_email" => $empdata['user_email'],
					"user_type_name" => $empdata['user_type_name'],
					"user_image" => image($app->imagePath($empdata['user_image']), 80, true),
					"role_name" => $empdata['user_type_name'],
					"login_page" => $app->basePath("login.php"),
					"employer_name" => APP_EMPLOYER_NAME
				));
				$email     = new Email("Employment joining details " . $app->siteName);
				$email->to($empdata['user_email'], $empdata['user_fname'], $empdata['user_image']);
				if($wc_attach_appointment_letter)
				$email->addFile(DOC::APPOINTMNET($user_pay_user_id), "Appointment Letter - $empdata[user_name].pdf");
				$email->template('employee_contract', $dataArray)->send();
			}
			
			Activity::add("updated Employee <b>$empdata[user_fname] $empdata[user_lname]</b> Contract Information");
			echo json_encode(array("200",  "success|Employee Contract updated Successfully",
				$user_pay_id));
		} else
			echo json_encode(array("300", "danger|Employee Not found"));
    } else
        echo json_encode(array("300", "danger|Ooops Respective Employee not Found"));

?>