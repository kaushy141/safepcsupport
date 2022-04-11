<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$SoftwareAssets = new SoftwareAssets($id);
	if($SoftwareAssets->isExist())
	{
		$record = $SoftwareAssets->getDetails();
		$SoftwareAssets->Deactivate();
		Activity::add("Removed Physical Assets <b>$record[soft_asset_type]</b>","", $id);
		echo json_encode(array("200",  "success|Software assets record removed Successfully"));
	}
	else
		echo json_encode(array("300",  "warning|Software assets record not found."));

?>