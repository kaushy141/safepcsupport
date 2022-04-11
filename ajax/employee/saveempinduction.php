<?php

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

?>