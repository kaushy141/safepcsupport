<?php

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

?>