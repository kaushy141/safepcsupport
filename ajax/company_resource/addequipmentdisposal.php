<?php

	$eqipment_disposal_hardware_id = 0;
    $eqipment_disposal_manufacturer= $eqipment_disposal_model= $eqipment_disposal_serial_number= $eqipment_disposal_log_no= $eqipment_disposal_disposed_to= $eqipment_disposal_reason= $eqipment_disposal_destruction_method= $eqipment_disposal_destroyed_date= $eqipment_disposal_destroyed_by = "";
    $eqipment_disposal_status = 1;
	$eqipment_disposal_created_user_id = getLoginId();
    $data           = sanitizePostData($_POST);
    $DestructionMethod = new DestructionMethod(0);
    extract($data);
	if($eqipment_disposal_destroyed_date !=""){
		$eqipment_disposal_destroyed_date = date("Y-m-d H:i:s", strtotime($eqipment_disposal_destroyed_date));
	}
	if($eqipment_disposal_id = $DestructionMethod->add($eqipment_disposal_hardware_id, $eqipment_disposal_manufacturer, $eqipment_disposal_model, $eqipment_disposal_serial_number, $eqipment_disposal_log_no, $eqipment_disposal_disposed_to, $eqipment_disposal_reason, $eqipment_disposal_destruction_method, $eqipment_disposal_destroyed_date, $eqipment_disposal_destroyed_by, $eqipment_disposal_created_user_id,  $eqipment_disposal_status))
	{
		Activity::add("Added new Equipment Disposal Log <b>$eqipment_disposal_model ($eqipment_disposal_serial_number)</b>","", $eqipment_disposal_id);
		echo json_encode(array("200",  "success|Equipment Disposal Log Added Successfully"));
    } 
	else{
        echo json_encode(array("300",  "warning|Equipment Disposal Log couldn't added" ));
	}

?>