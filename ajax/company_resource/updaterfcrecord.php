<?php

	$rfc_id = 0;
	$rfc_require_by_date = $rfc_date_of_request = $rfc_serial_number = $rfc_requester = $rfc_circulation_list =  $rfc_request_details = $rfc_request_reason = $rfc_service_owner_approval = $rfc_back_out_paln = $rfc_not_approved_reason = $rfc_completion_acceptance = $rfc_signature = $rfc_name = $rfc_date = "";
    $rfc_status = 1;
    $data           = sanitizePostData($_POST);
    extract($data);
    $Rfc = new Rfc($rfc_id);    
	
	if($Rfc->isExist())
	{
		$prevData = $Rfc->load();
		if ($rfc_signature != "") {
			$file_extension = pathinfo(BP.$rfc_signature, PATHINFO_EXTENSION);
			$new_rfc_signature = "upload/rfc/".getDirectorySeparatorPath()."sign/$prevData[rfc_code]-" . time() . ".$file_extension";
			move_file(BP.$rfc_signature, BP.$new_rfc_signature);
		} else
			$new_rfc_signature = $prevData['rfc_signature'];
		$Rfc->update(array(
			"rfc_require_by_date"=>$rfc_require_by_date, 
			"rfc_date_of_request"=>$rfc_date_of_request, 
			"rfc_serial_number"=>$rfc_serial_number,
			"rfc_requester"=>$rfc_requester,
			"rfc_circulation_list"=>$rfc_circulation_list,
			"rfc_request_details"=>$rfc_request_details, 
			"rfc_request_reason"=>$rfc_request_reason, 
			"rfc_service_owner_approval"=>$rfc_service_owner_approval,
			"rfc_back_out_paln"=>$rfc_back_out_paln,
			"rfc_not_approved_reason"=>$rfc_not_approved_reason,
			"rfc_completion_acceptance"=>$rfc_completion_acceptance,
			"rfc_signature"=>$new_rfc_signature,
			"rfc_name"=>$rfc_name,
			"rfc_date"=>$rfc_date			
			)
		);
		Activity::add("Updated Change Management Request <b>SR.No. $rfc_serial_number ($rfc_code)</b>","", $rfc_id);
        echo json_encode(array("200",  "success|Change Management Request Updated Successfully"));
    } else
        echo json_encode(array("300",  "warning|Change Management Request not found."));


?>