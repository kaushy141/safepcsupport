<?php

    $utility_description = $utility_used_by = $utility_managed_by = $utility_owner = $utility_location = $utility_risk = $utility_cia = $utility_contract_number = $utility_person_to_contact = $utility_contact_number = $utility_overview = $utility_key_security_tool = $utility_potential_action = $utility_action_plan =  $utility_doc_file_path = ""; 
	$utility_status = 1;
	$utility_id = $utility_impact = $utility_risk_rating = 0;
    $data           = sanitizePostData($_POST);
    extract($data);
    $Utilities = new Utilities($utility_id);
	
	if($Utilities->isExist())
	{
		$Utilities->update(array(
			"utility_description"=>$utility_description, 
			"utility_used_by"=>$utility_used_by, 
			"utility_managed_by"=>$utility_managed_by,
			"utility_owner"=>$utility_owner,
			"utility_location"=>$utility_location,
			"utility_risk"=>$utility_risk, 
			"utility_cia"=>$utility_cia, 
			"utility_contract_number"=>$utility_contract_number,
			"utility_person_to_contact"=>$utility_person_to_contact,
			"utility_contact_number"=>$utility_contact_number,			
			"utility_overview"=>$utility_overview, 
			"utility_key_security_tool"=>$utility_key_security_tool, 
			"utility_potential_action"=>$utility_potential_action,
			"utility_action_plan"=>$utility_action_plan,
			"utility_impact"=>$utility_impact,			
			"utility_risk_rating"=>$utility_risk_rating		
			)
		);
		if ($utility_doc_file_path != "") {
				$file_name = pathinfo(BP.$utility_doc_file_path);
                $extension  = strtolower($file_name['extension']);
				$utility_doc_file = "upload/doc/utility/".getDirectorySeparatorPath()."utility-$utility_id-" . time() . ".$extension";
				if (move_file(BP.$utility_doc_file_path, BP.$utility_doc_file))
					$Utilities->update(array("utility_doc_file" => $utility_doc_file));
		}
		Activity::add("Updated Utility <b>$utility_description</b>","", $utility_id);
        echo json_encode(array("200",  "success|Utility Updated Successfully"));
    } else
        echo json_encode(array("300",  "warning|Utility not found."));

?>