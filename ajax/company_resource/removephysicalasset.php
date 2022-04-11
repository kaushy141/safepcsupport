<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$PhysicalAsset = new PhysicalAsset($id);
	if($PhysicalAsset->isExist())
	{
		$record = $PhysicalAsset->getDetails();
		$PhysicalAsset->Deactivate();
		Activity::add("Removed Physical Assets <b>$record[asset_desciption]</b>","", $id);
		echo json_encode(array("200",  "success|Physical assets record removed Successfully"));
	}
	else
		echo json_encode(array("300",  "warning|Physical assets record not found."));

?>