<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$Rfc = new Rfc($id);
	if($Rfc->isActive())
	{
		$record = $Rfc->getDetails();
		$Rfc->Deactivate();
		Activity::add("Change Management Request <b>$record[rfc_code]</b>","", $id);
		echo json_encode(array("200",  "success|Change Management Request removed Successfully"));		
	}
	else
		echo json_encode(array("300",  "warning|Change Management Request not found."));

?>