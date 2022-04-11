<?php

    $cont_ins_id =  $cont_ins_risk = $cont_ins_risk_prob = $cont_ins_risk_rating = 0;
	$cont_ins_description =	$cont_ins_owner = $cont_ins_risk_treatment = $cont_ins_file_path = "";
	$cont_ins_status = 1;
    $data           = sanitizePostData($_POST);
    $ContractInsurance = new ContractInsurance(0);
    extract($data);
	if($cont_ins_file_path !="" && file_exists(BP.$cont_ins_file_path))
	{
		$source = $cont_ins_file_path;
		$file_extension = pathinfo(BP.$cont_ins_file_path, PATHINFO_EXTENSION);
		$cont_ins_file_path = DOC_UPLOAD_DIR."contarct/".getDirectorySeparatorPath()."contarct-".time().".$file_extension";
		move_file($source, $cont_ins_file_path);	
	
		if($cont_ins_id = $ContractInsurance->add($cont_ins_description, $cont_ins_owner, $cont_ins_risk, $cont_ins_risk_prob, $cont_ins_risk_rating, $cont_ins_risk_treatment, $cont_ins_file_path, $cont_ins_status))
		{
			Activity::add("Added Contract Document <b>$cont_ins_description</b>","", $cont_ins_id);
			echo json_encode(array("200",  "success|Contract Insurance Added Successfully"));
		} 
		else{
			echo json_encode(array("300",  "warning|Contract Insurance couldn't added" ));
		}		
	}
	else
		echo json_encode(array("300",  "warning|Contarct Document not found" ));

?>
