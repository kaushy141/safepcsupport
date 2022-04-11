<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$DestructionMethod = new DestructionMethod($id);
	if($DestructionMethod->isExist())
	{
		$record = $DestructionMethod->getDetails();
		$DestructionMethod->Deactivate();
		Activity::add("Removed Equipment Disposal <b>$record[eqipment_disposal_model] ($record[eqipment_disposal_serial_number])</b>","", $id);
		echo json_encode(array("200",  "success|Equipment Disposal record removed Successfully"));
	}
	else
		echo json_encode(array("300",  "warning|Equipment Disposal record not found."));

?>