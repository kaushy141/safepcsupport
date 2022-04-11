<?php
	Modal::load(array('Returns', 'Section'));    
	$return_module_code = null;
	$return_module_id = 0;
	$return_id = 0;
    $data = sanitizePostData($_POST);    
    extract($data);
	$return = new Returns(0);
	$moduleDetails = Section::getSectionDetails($return_module_code, $return_module_id);
	
	$returnData = array(
		'return_date' => $return_date,
		'return_remark' => $return_remark,
		'return_status' => $return_status,
	);
	
	
	if(!class_exists($moduleDetails['model_class'])){
		Modal::load(array($moduleDetails['model_class'])); 
	}
	$modalSection = new $moduleDetails['model_class']($return_module_id);	
	
	if($return_id = $return->isReturnExist($return_module_code, $return_module_id))
	{
		$return = new Returns($return_id);
		$return->update($returnData);
		echo json_encode(array("200",  "success|Return details saved", $return_id));
		$status_action = "updated";		
	}
	else{
		$returnData['return_code'] = $return->getReturnCode($return_module_code);
		$returnData['return_module_code'] = $return_module_code;
		$returnData['return_module_id'] = $return_module_id;
		$returnData['return_module_reference'] = $return_module_reference;
		$returnData['return_added_by'] = getLoginId();
		$returnData['return_created_date'] = 'NOW()';
		$return_id = $return->insert($returnData);		
		echo json_encode(array("200",  "success|Return added successfully.", $return_id));
		$status_action = "addedd";	
	}
	
	$return = new Returns($return_id);
	$returnInfo = $return->getDetails();
	Activity::add("$status_action {$moduleDetails['model_name']} <b>#{$returnInfo['return_module_reference']}</b>|^|{$returnInfo['return_code']}", "E", $return_id);
	
	if($return_status == 0){
		$modalSection->setReturn(0);
	}
	else{
		$modalSection->setReturn($return_id);
	}

?>