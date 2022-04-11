<?php

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

?>