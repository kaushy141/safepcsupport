<?php
	Modal::load(array('VerifyToken'));
    $user_id = 0;
    $data         = sanitizePostData($_POST);
    extract($data);
	if($user_id){
		$employee = new Employee($user_id);
		if($employee->isExist()){
			$empData = $employee->getDetails();
			if($empData['user_status'] == 1){
				$activation_link = $employee->sendAccountActivationLink();
				echo json_encode(array("200",  "success|Account activation link sent successfully", $activation_link));
			}
			else{
				echo json_encode(array("300",  "warning|Account is no longer active. Activate, and try again"));
			}
		}
		else{
			echo json_encode(array("300",  "warning|Account doesn't exist."));
		}
	}
?>