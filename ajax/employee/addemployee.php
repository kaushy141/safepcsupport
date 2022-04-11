<?php

    $user_code    = $user_fname = $user_lname = $user_password = $user_email = $user_phone = $user_address = $user_image = $user_created_by = $field_handler = $user_signature = $user_qualification = $user_skills = $user_experience = $user_responsibility = $user_cv_file = $user_experience_certificate = $user_education_doc = $user_driving_licence = $user_gender = $user_joining_date = $user_work_positions = $user_labour_card = $user_passport = "";
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
				
				if ($user_labour_card != "") {
                    $user_labour_card_path = "upload/user/lc/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_labour_card, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_labour_card), $app->sitePath($user_labour_card_path)))
                        $emp->update(array("user_labour_card" => $user_labour_card_path));
                }
				if ($user_passport != "") {
                    $user_passport_path = "upload/user/pp/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_passport, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_passport), $app->sitePath($user_passport_path)))
                        $emp->update(array("user_passport" => $user_passport_path));
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

?>