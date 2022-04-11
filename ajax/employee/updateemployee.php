<?php
    $user_code    = $user_fname = $user_lname = $user_password = $user_email = $user_phone = $user_address = $user_image = $user_created_by = $field_handler = $user_qualification = $user_skills = $user_experience = $user_responsibility = $user_cv_file = $user_experience_certificate = $user_education_doc = $user_driving_licence = $user_labour_card = $user_passport = "";
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
				$updatedDocuments = array();
                if ($user_signature != "") {
                    $signaturePath = "upload/user/sign/".getDirectorySeparatorPath()."$user_id-" . time() . ".png";
                    if (move_file($app->sitePath($user_signature), $app->sitePath($signaturePath))){
                        $employee->update(array("user_signature" => $signaturePath
                        ));
						array_push($updatedDocuments, 'Signature');
					}
                }
                if (isset($_SESSION['UPLOAD'][$field_handler]['PIC'])) {
                    $image_name = pathinfo($_SESSION['UPLOAD'][$field_handler]['PIC']);
                    $extension  = strtolower($image_name['extension']);
                    $user_image = "upload/user/pic/".getDirectorySeparatorPath()."user_" . time() . "_" . $user_id . ".$extension";
                    if(move_file($app->sitePath($_SESSION['UPLOAD'][$field_handler]['PIC']), $app->sitePath($user_image))) {
                        $employee->update(array("user_image" => $user_image
                        ));
						array_push($updatedDocuments, 'Profile Picture');
                    }
                    unset($_SESSION['UPLOAD'][$field_handler]['PIC']);
                }
				
				if ($user_cv_file != "") {
                    $user_cv_file_path = "upload/user/cv/".getDirectorySeparatorPath()."$user_id-" . time() . ".".pathinfo($user_cv_file, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_cv_file), $app->sitePath($user_cv_file_path))){
                        $employee->update(array("user_cv_file" => $user_cv_file_path));
						array_push($updatedDocuments, 'CV Document');
					}
                }
				
				if ($user_id_card != "") {
                    $user_id_card_path = "upload/user/ic/".getDirectorySeparatorPath()."$user_id-" . time() . ".".pathinfo($user_id_card, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_id_card), $app->sitePath($user_id_card_path))){
                        $employee->update(array("user_id_card" => $user_id_card_path));
						array_push($updatedDocuments, 'ID Card');
					}
                }
				
				if ($user_experience_certificate != "") {
                    $user_experience_certificate_path = "upload/user/exp/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_experience_certificate, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_experience_certificate), $app->sitePath($user_experience_certificate_path))){
                        $employee->update(array("user_experience_certificate" => $user_experience_certificate_path));
						array_push($updatedDocuments, 'Experience Letter');
					}
                }				
				
				if ($user_education_doc != "") {
                    $user_education_doc_path = "upload/user/edu/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_education_doc, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_education_doc), $app->sitePath($user_education_doc_path))){
                        $employee->update(array("user_education_doc" => $user_education_doc_path));
						array_push($updatedDocuments, 'Education Documents');
					}
                }
				
				if ($user_driving_licence != "") {
                    $user_driving_licence_path = "upload/user/dl/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_driving_licence, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_driving_licence), $app->sitePath($user_driving_licence_path))){
                        $employee->update(array("user_driving_licence" => $user_driving_licence_path));
						array_push($updatedDocuments, 'Driving Licence');
					}
                }				
				
				if ($user_labour_card != "") {
                    $user_labour_card_path = "upload/user/lc/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_labour_card, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_labour_card), $app->sitePath($user_labour_card_path))){
                        $employee->update(array("user_labour_card" => $user_labour_card_path));
						array_push($updatedDocuments, 'Labour Card');
					}
                }
				if ($user_passport != "") {
                    $user_passport_path = "upload/user/pp/".getDirectorySeparatorPath()."$user_id-" . time()  . ".".pathinfo($user_passport, PATHINFO_EXTENSION);
                    if (move_file($app->sitePath($user_passport), $app->sitePath($user_passport_path))){
                        $employee->update(array("user_passport" => $user_passport_path));
						array_push($updatedDocuments, 'Passport');
					}
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
				$updatedDocumentsString = "";
				if(count($updatedDocuments)){
					$updatedDocumentsString = "<b>".implode("</b>, <b>", $updatedDocuments)."</b>";
				}
                Activity::add("updated Employee <b>$user_fname $user_lname</b> Information $updatedDocumentsString");
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

?>