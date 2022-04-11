<?php

	$user_id = $month_id = 0;
	$pay_slip_user_id = $pay_slip_month_id = $pay_slip_grant_pay = $pay_slip_total_sale = $pay_slip_commision = $send_pay_slip_to_employee = 0;
	$pay_slip_basic_salary = $pay_slip_commision = $pay_slip_leave_taken = $pay_slip_paid_status = $pay_slip_pay_mode = $pay_slip_payment_reference = "";
	$data = sanitizePostData($_POST);
	extract($data);	
	
	if($user_id>0 && $month_id>0)
	{
		$month_name = date("M-Y", strtotime("20".substr($month_id,0,2)."-".substr($month_id,2,2)."-01"));
		$employee = new Employee($user_id);
		if($empData = $employee->getDetails())
		{
			$empdata['month_name'] = $month_name;
			$contract = new Contract();
			if($contractRecord = $contract->getDetailsByUser($user_id))
			{
				$pay_slip_user_id = $user_id;
				$pay_slip_month_id = $month_id;
				$dataArray = array(
									"pay_slip_user_id" => $pay_slip_user_id,
									"pay_slip_month_id" => $pay_slip_month_id,
									"pay_slip_basic_salary" => $pay_slip_basic_salary,
									"pay_slip_commision" => $pay_slip_commision,
									"pay_slip_leave_taken" => $pay_slip_leave_taken,
									"pay_slip_grant_pay" => $pay_slip_grant_pay,
									"pay_slip_total_sale" => $pay_slip_total_sale,
									"pay_slip_paid_status" => $pay_slip_paid_status,
									"pay_slip_pay_mode" => $pay_slip_pay_mode,
									"pay_slip_payment_reference" => $pay_slip_payment_reference,
								  );
				$slr = new SalaryRegister();
				if($registerData = $slr->isMprGenerated($pay_slip_user_id, $pay_slip_month_id)){
					$pay_slip_id = $registerData['pay_slip_id'];
					$salaryRegister = new SalaryRegister($pay_slip_id);
					$salaryRegister->update($dataArray);
				}else{
					$pay_slip_id = $slr->insert($dataArray);
				}
				$month_name = date("M-Y", strtotime("20".substr($pay_slip_month_id,0,2)."-".substr($pay_slip_month_id,2,2)."-01"));
				$commission = round(($pay_slip_total_sale*$pay_slip_commision)/100, 2);
				if($send_pay_slip_to_employee==1){
						$dataArray = array(
							"month_name" => $month_name,
							"user_name" => $empData['user_name'],
							"user_email" => $empData['user_email'],
							"user_type_name" => $empData['user_type_name'],
							"user_image" => image($app->imagePath($empData['user_image']), 80, true),
							"net_salary" => htmlentities(CURRENCY).$pay_slip_grant_pay,
							"pay_status" => $pay_slip_paid_status,
							"pay_mode" 	 => $pay_slip_pay_mode,
							"total_sale" 	 => htmlentities(CURRENCY).$pay_slip_total_sale,
							"commission" 	 => htmlentities(CURRENCY).$commission,
							"leave" 	 => $pay_slip_leave_taken,
							"login_page" => $app->basePath("login.php"),
							"employer_name" => APP_EMPLOYER_NAME
						);
						$email     = new Email("Pay Slip $month_name " . $app->siteName);
						$email->to($empData['user_email'], $empData['user_fname'], $empData['user_image']);
						$email->addFile(DOC::PAYSLIP($pay_slip_id), $app->siteName . " Pay Slip $month_name.pdf");
						$email->template('employee_payslip', $dataArray)->send();
				}
				echo json_encode(array("200",  "success|MPR Saved successfully"));
			}else
			echo json_encode(array("300", "danger|Contract not Created. Please created Contract first."));
		}else
        	echo json_encode(array("300", "danger|Employee not exist."));
	}else
        echo json_encode(array("300", "danger|Requested Data not Found."));

?>