<?php
function isuseremailavailable()
{
    if (isset($_POST['user_email'])) {
        $user_email = checkData($_POST['user_email']);
        $employee   = new Employee(0);
        if ($employee->IsEmialAvailable($user_email))
            echo json_encode(array(
                200,
                "success|\"$user_email\" is available to use"
            ));
        else
            echo json_encode(array(
                300,
                "danger|\"$user_email\" is allready used..."
            ));
    } else
        echo json_encode(array(
            300,
            "danger|Data Not found..."
        ));
}
function addemployee()
{
    global $app;
    $user_code    = $user_fname = $user_lname = $user_password = $user_email = $user_phone = $user_address = $user_image = $user_created_by = $field_handler = $user_signature = $user_qualification = $user_skills = $user_experience = $user_responsibility = $user_cv_file = $user_experience_certificate = $user_education_doc = $user_driving_licence = $user_gender = $user_joining_date = $user_work_positions = "";
    $user_type_id = $user_is_mobile_verified = $user_is_email_verified = 0;
    $user_is_live = $user_status = 1;
    $data         = sanitizePostData($_POST);
    extract($data);
	if($_SESSION['user_is_admin']==0 && 1==0)
	{
		echo json_encode(array("300", "danger|Warning... You are not authorised to add user"));
	}
    elseif (is_email($user_email) && $user_type_id != 0) {
        $userType         = new UserType($user_type_id);
        $user_type_detail = $userType->getDetails();
        if ($user_type_detail && $user_type_detail['user_type_status'] == 1) {
            $employee = new Employee(0);
            if ($employee->IsEmialAvailable($user_email)) {
                $user_code       = $employee->getNewCode($user_email, $user_fname . $user_lname);
                $user_password   = gePassword();
                $user_created_by = $_SESSION['user_id'];
                $signaturePath   = "";
                if ($user_signature != "") {
                    $signaturePath = "upload/user/sign/".getDirectorySeparatorPath()."$user_code-" . time() . ".png";
                    if (!move_file($app->sitePath($user_signature), $app->sitePath($signaturePath)))
                        $signaturePath = "";
                }
				$user_email = strtolower($user_email);
                $user_image = DEFAULT_USER_IMAGE;
                //$user_id    = $employee->add($user_type_id, $user_code, $user_fname, $user_lname, $user_password, $user_email, $user_phone, $user_address, $user_image, $user_signature, $user_status, $user_created_by, $user_is_mobile_verified, $user_is_email_verified, $user_is_live, $user_qualification, $user_skills, $user_experience, $user_responsibility, $user_cv_file);
				$employeeDaata = array(
										'user_type_id' => $user_type_id, 
										'user_code' => $user_code, 
										'user_fname' => $user_fname, 
										'user_lname' => $user_lname, 
										'user_password' => $user_password, 
										'user_email' => $user_email, 
										'user_phone' => $user_phone, 
										'user_address' => $user_address, 
										'user_image' => $user_image, 
										'user_signature' => $user_signature,	
										'user_gender'=> $user_gender,	
										'user_joining_date'=> $user_joining_date,	
										'user_work_positions' => $user_work_positions,
										'user_status' => $user_status, 
										'user_created_date' => 'NOW()',
										'user_created_by' => $user_created_by, 
										'user_is_mobile_verified' => $user_is_mobile_verified, 
										'user_is_email_verified' => $user_is_email_verified, 
										'user_is_live' => $user_is_live, 
										'user_qualification' => $user_qualification, 
										'user_skills' => $user_skills, 
										'user_experience' => $user_experience, 
										'user_responsibility' => $user_responsibility, 
										'user_cv_file' => $user_cv_file
										);
                $emp        = new Employee($user_id);
				$user_id = $emp->insert($employeeDaata);
                if (isset($_SESSION['UPLOAD'][$field_handler]['PIC'])) {
                    $image_name = pathinfo($_SESSION['UPLOAD'][$field_handler]['PIC']);
                    $extension  = strtolower($image_name['extension']);
                    $user_image = "upload/user/pic/".getDirectorySeparatorPath()."user_" . time() . "_" . $user_id . ".$extension";
                    if (move_file($app->sitePath($_SESSION['UPLOAD'][$field_handler]['PIC']), $app->sitePath($user_image))) {
                        $emp->update(array("user_image" => $user_image
                        ));
                    }
                    unset($_SESSION['UPLOAD'][$field_handler]['PIC']);
                }
				
				if ($user_cv_file != "") {
                    $user_cv_file_path = "upload/user/cv/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_cv_file, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_cv_file), $app->sitePath($user_cv_file_path)))
                        $emp->update(array("user_cv_file" => $user_cv_file_path));
                }
				
				if ($user_id_card != "") {
                    $user_id_card_path = "upload/user/ic/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_id_card, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_id_card), $app->sitePath($user_id_card_path)))
                        $emp->update(array("user_id_card" => $user_id_card_path));
                }
				
				if ($user_experience_certificate != "") {
                    $user_experience_certificate_path = "upload/user/exp/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_experience_certificate, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_experience_certificate), $app->sitePath($user_experience_certificate_path)))
                        $emp->update(array("user_experience_certificate" => $user_experience_certificate_path));
                }
				
				if ($user_education_doc != "") {
                    $user_education_doc_path = "upload/user/edu/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_education_doc, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_education_doc), $app->sitePath($user_education_doc_path)))
                        $emp->update(array("user_education_doc" => $user_education_doc_path));
                }
				
				if ($user_driving_licence != "") {
                    $user_driving_licence_path = "upload/user/dl/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_driving_licence, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_driving_licence), $app->sitePath($user_driving_licence_path)))
                        $emp->update(array("user_driving_licence" => $user_driving_licence_path));
                }
				
                $employeedetail  = $emp->getDetails();
                $activation_link = $app->basePath("activation.php?r=emp&u=$user_email&l=" . md5($user_email . $user_id) . "&i=" . md5($user_id . $user_email));
                new SMS($user_phone, "Hi, $user_fname Welcome to " . $app->siteName . " as a $employeedetail[user_type_name] Account created. Username is $user_email and Password is $user_password on site " . $app->basePath('login.php'));
                $dataArray = array(
                    "user_name" => $user_fname,
                    "user_email" => $user_email,
                    "user_image" => image($app->imagePath($user_image), 80, true),
                    "user_password" => $user_password,
                    "role_name" => $employeedetail['user_type_name'],
                    "login_page" => $app->basePath("login.php"),
                    "activation_link" => $activation_link
                );
                $email     = new Email("Account Created on " . $app->siteName);
                $email->to($user_email, $user_fname, $user_image)->template('employee_registration', $dataArray)->send();
                Activity::add("added New Employee <b>$user_fname $user_lname</b> Information");
                echo json_encode(array("200",  "success|New Employee Added Successfully",
                    $user_id
                ));
            } else
                echo json_encode(array("300", "danger|Email is not vaialbale."
                ));
        } else
            echo json_encode(array("300", "danger|Selected User type is Not Active Please try another type"
            ));
    } else
        echo json_encode(array("300", "danger|Ooops Employee could not added. Email Incorrect Please try again"
        ));
}
function updateemployee()
{
    global $app;
    $user_code    = $user_fname = $user_lname = $user_password = $user_email = $user_phone = $user_address = $user_image = $user_created_by = $field_handler = $user_qualification = $user_skills = $user_experience = $user_responsibility = $user_cv_file = $user_experience_certificate = $user_education_doc = $user_driving_licence = "";
    $user_type_id = $user_is_mobile_verified = $user_is_email_verified = $user_id = 0;
    $user_is_live = $user_status = 1;
    $data         = sanitizePostData($_POST);
    extract($data);
	if($_SESSION['user_is_admin']==0 && 1==0)
	{
		echo json_encode(array("300", "danger|Warning... You are not authorised to add user"));
	}
    elseif (is_email($user_email) && $user_type_id != 0 && $user_id != 0) {
        $userType         = new UserType($user_type_id);
        $user_type_detail = $userType->getDetails();
        if ($user_type_detail && $user_type_detail['user_type_status'] == 1) {
            $employee = new Employee($user_id);
            if ($employee->IsEmialAvailable($user_email, $user_id)) {
                $emp_user = $employee->getDetails();
				$user_email = strtolower($user_email);
				
                $employee->update(array(
					'user_type_id' => $user_type_id,  
					'user_fname' => $user_fname, 
					'user_lname' => $user_lname,  
					'user_email' => $user_email, 
					'user_phone' => $user_phone, 
					'user_address' => $user_address,  	
					'user_gender'=> $user_gender,	
					'user_joining_date'=> $user_joining_date,	
					'user_work_positions' => $user_work_positions,  
					'user_qualification' => $user_qualification, 
					'user_skills' => $user_skills, 
					'user_experience' => $user_experience, 
					'user_responsibility' => $user_responsibility, 
                ));
                if ($user_signature != "") {
                    $signaturePath = "upload/user/sign/".getDirectorySeparatorPath()."$user_id-" . time() . ".png";
                    if (move_file($app->sitePath($user_signature), $app->sitePath($signaturePath)))
                        $employee->update(array("user_signature" => $signaturePath
                        ));
                }
                if (isset($_SESSION['UPLOAD'][$field_handler]['PIC'])) {
                    $image_name = pathinfo($_SESSION['UPLOAD'][$field_handler]['PIC']);
                    $extension  = strtolower($image_name['extension']);
                    $user_image = "upload/user/pic/".getDirectorySeparatorPath()."user_" . time() . "_" . $user_id . ".$extension";
                    if(move_file($app->sitePath($_SESSION['UPLOAD'][$field_handler]['PIC']), $app->sitePath($user_image))) {
                        $employee->update(array("user_image" => $user_image
                        ));
                    }
                    unset($_SESSION['UPLOAD'][$field_handler]['PIC']);
                }
				
				if ($user_cv_file != "") {
                    $user_cv_file_path = "upload/user/cv/".getDirectorySeparatorPath()."$user_id-" . time() . ".".pathinfo($user_cv_file, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_cv_file), $app->sitePath($user_cv_file_path)))
                        $employee->update(array("user_cv_file" => $user_cv_file_path));
                }
				
				if ($user_id_card != "") {
                    $user_id_card_path = "upload/user/ic/".getDirectorySeparatorPath()."$user_id-" . time() . ".".pathinfo($user_id_card, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_id_card), $app->sitePath($user_id_card_path)))
                        $employee->update(array("user_id_card" => $user_id_card_path));
                }
				
				if ($user_experience_certificate != "") {
                    $user_experience_certificate_path = "upload/user/exp/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_experience_certificate, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_experience_certificate), $app->sitePath($user_experience_certificate_path)))
                        $employee->update(array("user_experience_certificate" => $user_experience_certificate_path));
                }				
				
				if ($user_education_doc != "") {
                    $user_education_doc_path = "upload/user/edu/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_education_doc, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_education_doc), $app->sitePath($user_education_doc_path)))
                        $employee->update(array("user_education_doc" => $user_education_doc_path));
                }
				
				if ($user_driving_licence != "") {
                    $user_driving_licence_path = "upload/user/dl/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_driving_licence, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_driving_licence), $app->sitePath($user_driving_licence_path)))
                        $employee->update(array("user_driving_licence" => $user_driving_licence_path));
                }
				
                if ($emp_user['user_email'] != $user_email) {
                    $employee->update(array("user_is_email_verified" => 0
                    ));
                    $activation_link = $app->basePath("activation.php?r=emp&u=$user_email&l=" . md5($user_email . $emp_user['user_id']) . "&i=" . md5($emp_user['user_id'] . $user_email));
                    $dataArray       = array(
                        "user_name" => $user_fname,
                        "user_email" => $user_email,
                        "user_image" => image($app->imagePath($emp_user['user_image']), 80, true),
                        "role_name" => $emp_user['user_type_name'],
                        "login_page" => $app->basePath("login.php"),
                        "activation_link" => $activation_link
                    );
                    $email           = new Email("Employee account info ipdated on " . $app->siteName);
                    $email->to($user_email, $user_fname, $user_type_detail['user_image'])->template('employee_update', $dataArray)->send();
                }
				
                new SMS($user_phone, "Hi, $user_fname Your account information had been updated with us successfully. Use your registered email id $user_email to login.");
                Activity::add("updated Employee <b>$user_fname $user_lname</b> Information");
                echo json_encode(array("200",  "success|Employee Details Updated Successfully",
                    $user_id
                ));
            } else
                echo json_encode(array("300", "danger|Email is not vaialbale."
                ));
        } else
            echo json_encode(array("300", "danger|Selected User type is Not Active Please try another type"
            ));
    } else
        echo json_encode(array("300", "danger|Ooops Employee could not Updated. Please try again"
        ));
}
function updateemployeestatus()
{
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Employee       = new Employee($idvalue);
        $Employee_fname = $Employee->get('user_fname');
        $status ? $Employee->Activate() : $Employee->Deactivate();
        Activity::add(status($status) . " Employee <b>" . $Employee_fname . "</b> staus");
        echo json_encode(array("200",  "success|User " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Employee " . status($status));
        $email->send("Employee \"" . $Employee_fname . "\" " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|User Status not found."
        ));
}


function getloghistory()
{
    $id               = 0;
    $complaint_format = '';
    $data             = sanitizePostData($_POST);
    extract($data);
    if (intval($id) != 0 && $complaint_format != '') {
        $complaint_id = $id;		
		//UserTag::markUserTagRead();		
        $log          = new ComplaintLog($complaint_format, $complaint_id);
        $logdata      = $log->getLog($complaint_id, $complaint_format);		
        echo json_encode(array("200",  $logdata?"success|Log comments loaded successfully.":"success|No Log comments available for it.", $logdata ));
    } 
	else
        echo json_encode(array("300", "warning|Unable to fetch Log Text"));
}
function updateuserpassword()
{
    global $app;
    $old_password = $new_password = $conf_password = "";
    $data         = sanitizePostData($_POST);
    extract($data);
    if ($old_password != "" && $new_password != "" && $conf_password != "") {
        $length          = strlen($new_password);
        $approval_length = APP_PASSWORD_MIN_LENGTH;
        if ($length - $approval_length >= 0) {
            $emp          = new Employee($_SESSION['user_id']);
            $employee     = $emp->getDetails();
            $emp_password = $employee["user_password"];
            if ($emp_password == $old_password) {
                $user_id = $emp->update(array("user_password" => $new_password
                ));
                new SMS($employee['user_phone'], "Hi, $employee[user_fname] Your Password updated successfully.");
                Activity::add("changed Password");
                echo json_encode(array("200",  "success|Your Password updated successfully"
                ));
                $email = new Email($app->siteName . " : " . "Password Changed");
                $email->send("You have successfully chnaged your Password on " . $app->siteName . ". If you have not then contact to Administration immediately.");
            } else
                echo json_encode(array("300", "danger|Old Password not Matched."
                ));
        } else
            echo json_encode(array("300", "danger|Password must have atleast " . APP_PASSWORD_MIN_LENGTH . " Character"
            ));
    } else
        echo json_encode(array("300", "danger|Old password or New Password can't Blank"
        ));
}

function setuserfcmtoken()
{
	global $app;
    $currentToken = "";
    $data         = $_POST;
    extract($data);
    if ($currentToken != "") {
        $_SESSION['currentToken'] = $currentToken;
        $tokenUpdate              = false;
        if (getUserType() == "E") {
            $emp = new Employee($_SESSION['user_id']);
            if ($emp->get("user_fcm_token") != $currentToken) {
                $emp->setFCMToken($currentToken);
                $tokenUpdate = true;
            }
        } else {
            $cst = new Customer($_SESSION['customer_id']);
            if ($cst->get("customer_fcm_token") != $currentToken) {
                $cst->setFCMToken($currentToken);
                $tokenUpdate = true;
            }
        }
        echo json_encode(array("200",  "success|" . ($tokenUpdate ? "Token Updated on Server" : "Token Exist") . ". You are ready to chat"
        ));
    } else
        echo json_encode(array("300", "warning|Opps Could not set up token on Server"
        ));
}

function addcontract(){
	global $app;
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
}

function empmonthsalary(){
	global $app;
	$user_id = $month_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($user_id>0 && $month_id>0)
	{
		$month_name = date("M-Y", strtotime("20".substr($month_id,0,2)."-".substr($month_id,2,2)."-01"));
		$employee = new Employee($user_id);
		if($empdata = $employee->getDetails())
		{
			$empdata['month_name'] = $month_name;
			$contract = new Contract();
			if($contractRecord = $contract->getDetailsByUser($user_id))
			{			
				if($contractRecord['user_pay_status']==1)
				{
					$slr = new SalaryRegister();
					if($registerData = $slr->isMprGenerated($user_id, $month_id))
					{
						$contractRecord['paid_option'] = getOptionPaid($registerData['pay_slip_paid_status']);
						$contractRecord['paid_paymode'] = getOptionPayMode($registerData['pay_slip_pay_mode']);
						echo json_encode(array("200",  "success|MPR Detail", array_merge($empdata, $contractRecord, $registerData), "contractRecord"=>$contractRecord));
					}
					else
					{
						$contractRecord['paid_option'] = getOptionPaid();
						$contractRecord['paid_paymode'] = getOptionPayMode();
						echo json_encode(array("200",  "success|MPR Form", array_merge($empdata, $contractRecord)));
					}
						
				}else
					echo json_encode(array("300", "danger|Employee Contract status dissabled"));
			}else
        		echo json_encode(array("300", "danger|Contract not Created. Please created Contract first."));
		}else
        	echo json_encode(array("300", "danger|Employee not exist."));
	}else
        echo json_encode(array("300", "danger|Requested Data not Found."));
}

function saveemployeecontract(){
	global $app;
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
}

function loadlivechatuser(){
	if($id = isAdmin())
	{
		$employee = new Employee($id);
		$employee->setLive();
	}
	$liveUser = Employee::getLiveUser();
	echo json_encode(array("200",  "success|User Loaded", $liveUser));
}

function loaduserlivechathistory()
{
	$user_id = 0;
	$offsetId = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	
	if($user_id>0)
	{
		$message = new Message();
		$message->markMessageReaded();
		$records = $message->getUsersMessage($user_id, $offsetId);
		echo json_encode(array("200",  "success|Chat history loaded", $records));
	}
	else
		echo json_encode(array("300", "danger|Invalid user found."));
}

function employeerecords(){
	$data = sanitizePostData($_POST);
	extract($data);	
	$timeZone = $_REQUEST['tz'];
	$start = date("Y-m-d", strtotime(date("Y-m-d", strtotime($_REQUEST['start'])) . " -7 day"));
	$end = date("Y-m-d", strtotime(date("Y-m-d", strtotime($_REQUEST['end'])) . " +7 day"));
	$Employee = new Employee($user_id);
	echo $Employee->getAttendaceList($user_id, $start, $end, $timeZone);	
}


function updatemylocation(){
	$data = sanitizePostData($_POST);
	$location_id = 0 ;
	$location_user_id = $_SESSION['user_id'];
	$location_lat_lng = NULL;
	$loation_save_time = "";
	$loation_is_expired = 0;
	
	extract($data);	
	if($location_lat_lng != NULL && isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0)
	{
		$location_lat_lng = json_encode($location_lat_lng);
		$location = new Location();
		$location->expireMyLocation($_SESSION['user_id']);
		$location_id = $location->insert(
				array(
						"location_user_id"=>$location_user_id,
						"location_lat_lng"=>$location_lat_lng,
						"loation_save_time"=>"NOW()",
						"loation_is_expired"=>$loation_is_expired
					)
				);
		echo json_encode(array("200",  "success|location updated", $location_id, $data));
	}
	else
		echo json_encode(array("300", "danger|Invalid location found."));
}

function getalluserlocation(){
	$timezone = date('Z');
	$data = sanitizePostData($_POST);
	extract($data);	
	$location = new Location();
	$records = $location->getAllUsersLiveLocation($timezone);
	if($records)	
		echo json_encode(array("200",  "success|location found", $records));
	else
		echo json_encode(array("300", "danger|No location found."));
}

function getusertrackingroot(){
	$user_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($user_id)
	{
		$location = new Location();
		$records = $location->getDetailsByUser($user_id, 20);
		if($records)	
			echo json_encode(array("200",  "success|location found", $records));
		else
			echo json_encode(array("300", "danger|No location record found."));
	}
	else
		echo json_encode(array("300", "danger|No user found."));
}

function getuserworkrecord(){
	$user_id = $user_month = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($user_id != "" && $user_month != "")
	{
		$user = new Employee($user_id);
		if($user->isExist())
		{
			$user_month = date("Y-m", strtotime($user_month));
			$records = Activity::getUserWorkByMonth($user_month, $user_id);
			echo json_encode(array("200",  "success|Work record loaded for ".date("M Y", strtotime($user_month."-01")), $records, cal_days_in_month(CAL_GREGORIAN,date("m", strtotime($user_month."-01")),date("Y", strtotime($user_month."-01"))), date("M", strtotime($user_month."-01")), $user_month, date("Y-m", strtotime($user_month))));	
		}
		
	}
	else
		echo json_encode(array("300", "danger|No user found."));
}

function getdaylogrecord(){
	$user_id = $date = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($user_id != 0 && $date != 0)
	{
		$user = new Employee($user_id);
		if($user->isExist())
		{
			$records = Activity::getUserWorkByDay($date, $user_id);
			echo json_encode(array("200",  "success|Day Work record loaded", $records, date("d-M Y", strtotime($date))));	
		}		
	}
	else
		echo json_encode(array("300", "danger|No Work record found."));
}

function saveempcontract(){
	global $app;
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
}

function sendcontractdoctoemployee(){
	global $app;
	$employee_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($employee_id != 0)
	{
		$employee = new ContractEmployee($employee_id);
		if($employee->isExist())
		{
			$store = new Store(APP_EMPLOYER_DOMAIN);
			$storeData = $store->getDetails();
			$empData = $employee->getDetails();
			$empData['employee_employment_date'] = date('D, d M Y', strtotime($empData['employee_employment_date']));
			$empData['employer_name'] = $storeData['store_title'];
			$email     = new Email("Contract information");
			$email->to($empData['employee_email'], $empData['employee_name'], $app->basePath(DEFAULT_USER_IMAGE));
			$email->addFile(DOC::EMPCONTRACT($empData['employee_id']), $app->siteName . " contract.pdf");
			$email->template('cont_employee_contract', $empData);
			$email->send();
			echo json_encode(array("200",  "success|Contract copy sent"));
		}
		else
			echo json_encode(array("300", "danger|No Employee found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Employee."));
}

function delempcontract(){
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
}

function saveemptraining(){
	global $app;
	$training_id = 0;
	$training_status = 1;
	$training_type = $training_date = $training_date = $training_description = $training_trainee_name = $training_job_title = $training_learning_detail = $training_trainer_name = "";
	$training_trainee_signature = $training_trainer_signature = '';
	
	$data = sanitizePostData($_POST);
	extract($data);	
	
	$training_array = compact('training_type', 'training_date', 'training_description', 'training_trainee_name', 'training_job_title', 'training_learning_detail', 'training_trainer_name');
	
	$training = new Training($training_id);
	if($training_id == 0){
		$training_array['training_created_date'] = 'NOW()';
		$training_array['training_status'] = '1'; 
		$training_id = $training->insert($training_array);		
	}
	else{
		$training->update($training_array);
	}
	$record = $training->getDetails();
	if ($training_trainee_signature != "") {
		$training_trainee_signature_path = "upload/trainee/sign/".getDirectorySeparatorPath()."$training_id-" . time() . ".".pathinfo($training_trainee_signature, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($training_trainee_signature), $app->sitePath($training_trainee_signature_path))){
			$training->update(array("training_trainee_signature" => $training_trainee_signature_path));
			unlinkFile($record['training_trainee_signature']);	
		}
			
	}
	if ($training_trainer_signature != "") {
		$training_trainer_signature_path = "upload/trainer/sign/".getDirectorySeparatorPath()."$training_id-" . time()  . ".".pathinfo($training_trainer_signature, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($training_trainer_signature), $app->sitePath($training_trainer_signature_path))){
			$training->update(array("training_trainer_signature" => $training_trainer_signature_path));
			unlinkFile($record['training_trainer_signature']);	
		}
			
	}
	
	echo json_encode(array("200",  "success|Training record saved", $training_id));
}

function delemptraining(){
	$training_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($training_id != 0)
	{
		$training = new Training($training_id);
		if($training->isExist())
		{
			$record = $training->getDetails();
			$training->remove();
			unlinkFile($record['training_trainee_signature']);	
			unlinkFile($record['training_trainer_signature']);	
			echo json_encode(array("200",  "success|Training record deleted", $training_id));
		}
		else
			echo json_encode(array("300", "danger|No Training found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Training."));
}

function saveempleave(){
	global $app;
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
}

function delempleave(){
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
}

function employeeleavesummary(){
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
}

function delempinduction(){
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
}

function saveempinduction(){
	global $app;
	$eic_id = 0;
	$eic_employee_name = $eic_employee_signature = $eic_employee_sign_date = $eic_hr_signature = $eic_hr_signature_date = $eic_relevant_issue = '';
	$eic_review_status = 0;
	$checkbox_item = array();
	$data = sanitizePostData($_POST);
	extract($data);	
	
	$eic_array = compact('eic_employee_name', 'eic_employee_sign_date', 'eic_hr_signature_date', 'eic_relevant_issue', 'eic_review_status');
	
	$eic = new EmployeeInduction($eic_id);
	if($eic_id == 0){
		$eic_array['eic_created_date'] = 'NOW()';
		$eic_array['eic_status'] = '1'; 
		$eic_array['eic_submitted'] = '0'; 
		$eic_id = $eic->insert($eic_array);		
	}
	else{
		$eic->update($eic_array);
	}
	
	$record = $eic->getDetails();
	if ($eic_employee_signature != "") {
		$eic_employee_signature_path = "upload/eicemp/sign/".getDirectorySeparatorPath()."$eic_id-" . time()  . ".".pathinfo($eic_employee_signature, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($eic_employee_signature), $app->sitePath($eic_employee_signature_path))){
			$eic->update(array("eic_employee_signature" => $eic_employee_signature_path));
			unlinkFile($record['eic_employee_signature']);
		}
			
	}
	if ($eic_hr_signature != "") {
		$eic_hr_signature_path = "upload/eichr/sign/".getDirectorySeparatorPath()."$eic_id-" . time()  . ".".pathinfo($eic_hr_signature, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($eic_hr_signature), $app->sitePath($eic_hr_signature_path))){
			$eic->update(array("eic_hr_signature" => $eic_hr_signature_path));
			unlinkFile($record['eic_hr_signature']);
		}
			
	}
	
	if(count($checkbox_item)){
		$eic->clearItem();
		foreach($checkbox_item as $eici_ci_id => $value){
			$eici_eic_id = $eic_id;
			$eici_checklist_item_id = $eici_ci_id;
			$eici_comment = isset($checkbox_comment[$eici_ci_id]) ? trim($checkbox_comment[$eici_ci_id]) : "";
			$eici_completed_date = isset($checkbox_date[$eici_ci_id]) ? trim($checkbox_date[$eici_ci_id]) : NULL;
			$eici_completed = isset($checkbox_completed[$eici_ci_id]) ? trim($checkbox_completed[$eici_ci_id]) : "";
			$eic->saveItem(compact('eici_eic_id', 'eici_checklist_item_id', 'eici_comment', 'eici_completed_date', 'eici_completed'));
		}
	}
	
	echo json_encode(array("200",  "success|Induction record saved", $eic_id));
}

function completeempinduction(){
	$eic_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($eic_id != 0)
	{
		$eic = new EmployeeInduction($eic_id);
		if($eic->isExist())
		{
			$eic->update(array("eic_submitted"=>1));
			echo json_encode(array("200",  "success|Induction submitted. and submitted to department", $eic_id));
			Activity::add("Submitted an Induction report to department.");
		}
		else
			echo json_encode(array("300", "danger|No Induction found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Induction."));
}

#=============================================Leaver--------------------------------

function delempleaver(){
	$eil_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($eil_id != 0)
	{
		$eil = new EmployeeLeaver($eil_id);
		if($eil->isExist())
		{
			$record = $eil->getDetails();
			$eil->remove();
			$eil->clearItem();
			echo json_encode(array("200",  "success|Leaver record deleted", $eil_id));
			Activity::add("deleted an Leaver record.");
			unlinkFile($record['eil_employee_signature']);
			unlinkFile($record['eil_hr_signature']);
		}
		else
			echo json_encode(array("300", "danger|No Leaver found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Leaver."));
}

function saveempleaver(){
	global $app;
	$eil_id = 0;
	$eil_employee_name = $eil_employee_signature = $eil_employee_sign_date = $eil_hr_signature = $eil_hr_signature_date = $eil_relevant_issue = '';
	$eil_review_status = 0;
	$checkbox_item = array();
	$data = sanitizePostData($_POST);
	extract($data);	
	
	$eil_array = compact('eil_employee_name', 'eil_employee_sign_date', 'eil_hr_signature_date', 'eil_relevant_issue', 'eil_review_status');
	
	$eil = new EmployeeLeaver($eil_id);
	if($eil_id == 0){
		$eil_array['eil_created_date'] = 'NOW()';
		$eil_array['eil_status'] = '1'; 
		$eil_array['eil_submitted'] = '0'; 
		$eil_id = $eil->insert($eil_array);		
	}
	else{
		$eil->update($eil_array);
	}
	$record = $eil->getDetails();
	
	if ($eil_employee_signature != "") {
		$eil_employee_signature_path = "upload/eilemp/sign/".getDirectorySeparatorPath()."$eil_id-" . time()  . ".".pathinfo($eil_employee_signature, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($eil_employee_signature), $app->sitePath($eil_employee_signature_path))){	
			$eil->update(array("eil_employee_signature" => $eil_employee_signature_path));
			unlinkFile($record['eil_employee_signature']);
		}
	}
	if ($eil_hr_signature != "") {
		$eil_hr_signature_path = "upload/eilhr/sign/".getDirectorySeparatorPath()."$eil_id-" . time()  . ".".pathinfo($eil_hr_signature, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($eil_hr_signature), $app->sitePath($eil_hr_signature_path))){
			$eil->update(array("eil_hr_signature" => $eil_hr_signature_path));
			unlinkFile($record['eil_hr_signature']);
		}
			
	}
	
	if(count($checkbox_item)){
		$eil->clearItem();
		foreach($checkbox_item as $eili_ci_id => $value){
			$eili_eil_id = $eil_id;
			$eili_checklist_item_id = $eili_ci_id;
			$eili_comment = isset($checkbox_comment[$eili_ci_id]) ? trim($checkbox_comment[$eili_ci_id]) : "";
			$eili_completed_date = isset($checkbox_date[$eili_ci_id]) ? trim($checkbox_date[$eili_ci_id]) : NULL;
			$eili_completed = isset($checkbox_completed[$eili_ci_id]) ? trim($checkbox_completed[$eili_ci_id]) : "";
			$eil->saveItem(compact('eili_eil_id', 'eili_checklist_item_id', 'eili_comment', 'eili_completed_date', 'eili_completed'));
		}
	}
	
	echo json_encode(array("200",  "success|Leaver record saved", $eil_id));
}

function completeempleaver(){
	$eil_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($eil_id != 0)
	{
		$eil = new EmployeeLeaver($eil_id);
		if($eil->isExist())
		{
			$eil->update(array("eil_submitted"=>1));
			echo json_encode(array("200",  "success|Leaver submitted. and submitted to department", $eil_id));
			Activity::add("Submitted an Leaver report to department.");
		}
		else
			echo json_encode(array("300", "danger|No Leaver found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Leaver."));
}

function saveempnotice(){
	global $app;
	$notice_id = $notice_emp_id = $notice_count = $notice_status = 0;
	$notice_reason = $notice_date = $notice_attachement = '';
	
	$data = sanitizePostData($_POST);
	extract($data);	
	$notice_date 	= date("Y-m-d H:i:s", strtotime($notice_date));
	$notice_array = compact('notice_emp_id', 'notice_reason', 'notice_date', 'notice_status');
	
	$notice = new EmployeeNotice($notice_id);
	if($notice_id == 0){
		$notice_array['notice_created_date'] = 'NOW()';
		$notice_id = $notice->insert($notice_array);		
	}
	else{
		$notice->update($notice_array);
	}
	$notice_count = $notice->getEmpNoticeCount($notice_emp_id);
	$notice->update(compact('notice_count'));
	
	$record = $notice->getDetails();
	
	if ($notice_attachement != "") {
		$notice_attachement_path = "upload/notice/".getDirectorySeparatorPath()."$notice_id-" . time()  . ".".pathinfo($notice_attachement, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($notice_attachement), $app->sitePath($notice_attachement_path))){	
			$notice->update(array("notice_attachement" => $notice_attachement_path));
			unlinkFile($record['notice_attachement']);
		}
	}	
	echo json_encode(array("200",  "success|Notice record saved", $eil_id));
}

function savemployeereleavingletter(){
	global $app;
	$releaving_id = $releaving_user_id = $releaving_created_by = $releaving_exp_letter_sent_date = 0;
	$releaving_date = $releaving_start_position = $releaving_last_position = $releaving_created_date = "";
	
	$data = sanitizePostData($_POST);
	extract($data);	
	$releavingArray = array(
		'releaving_user_id' => $releaving_user_id,
		'releaving_date' => $releaving_date,
		'releaving_start_position' => $releaving_start_position,
		'releaving_last_position' => $releaving_last_position
	);	
	$employeeReleaving = new EmployeeReleaving($releaving_id);
	
	if($releaving_id == 0){
		if(!EmployeeReleaving::isExpLetterExist($releaving_user_id)){
			$releavingArray['releaving_created_by'] = getLoginId();
			$releavingArray['releaving_created_date'] = 'NOW()';
			$releaving_id = $employeeReleaving->insert($releavingArray);
		}
		else{
			echo json_encode(array("300", "danger|Experience letter already generated for this user."));
			die;
		}		
	}
	else{
		$employeeReleaving->update($releavingArray);
	}		
	echo json_encode(array("200",  "success|Experience letter saved", $releaving_id, md5($releaving_id)));
}

function sendexperienceletter(){
	global $app;
	$releaving_id = 0;	
	$data = sanitizePostData($_POST);
	extract($data);		
	$employeeReleaving = new EmployeeReleaving($releaving_id);
	
	if($employeeReleaving->isExist()){
		$releavingData = $employeeReleaving->getDetails();		
		
		$dataArray = array(
			"user_name" => $releavingData['user_fname'].' '.$releavingData['user_lname']
		);   
        
        $email     = new Email("{$releavingData['user_fname']} {$releavingData['user_lname']} experience letter");
        $email->to($releavingData['user_email'], "{$releavingData['user_fname']} {$releavingData['user_lname']}", $app->imagePath($releavingData['user_image']));
		$email->addFile(DOC::EMPEXPLETTER($releavingData['releaving_id']), $app->siteName . " - {$releavingData['user_fname']} {$releavingData['user_lname']} experience_letter.pdf");
		
		$email->template('experience_letter_to_employee', $dataArray);
		$email->send();
		
		echo json_encode(array("200",  "success|Experience letter sent."));
	}
	else{
		echo json_encode(array("300",  "danger|Experience letter not found"));
	}		
	
}

function getbirthdayuser(){
	$employee = new Employee(0);
	echo json_encode(array("200",  "success|Today's Birthday user loaded", $employee->getTodayBirthDayUser()));
}


?>