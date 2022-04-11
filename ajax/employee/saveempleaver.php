<?php

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

?>