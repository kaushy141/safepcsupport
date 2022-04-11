<?php
    $asset_id = $asset_desciption = $asset_manufaturer = $asset_model = $asset_serial_number = $asset_tag = $asset_processor = $asset_ram = $asset_size = $asset_owner = $asset_managed_by = $asset_used_by = $asset_location = $asset_overview = $asset_risk = $asset_cia = $asset_impact = $asset_risk_rating = $asset_suspect_vulnerabilities = $asset_action_plan = "";
    $asset_status = 1;
	$asset_created_by = getLoginId();
    $data           = sanitizePostData($_POST);
    $PhysicalAsset = new PhysicalAsset(0);
	$asset_tag = $PhysicalAsset->getAssetTag();
    extract($data);
	if($asset_id = $PhysicalAsset->add($asset_desciption, $asset_manufaturer, $asset_model, $asset_serial_number, $asset_tag, $asset_processor, $asset_ram, $asset_size, $asset_owner, $asset_managed_by, $asset_used_by, $asset_location, $asset_overview, $asset_risk, $asset_cia, $asset_impact, $asset_risk_rating, $asset_suspect_vulnerabilities, $asset_action_plan, $asset_created_by, $asset_status))
	{
		Activity::add("Added new Physical Asset <b>$asset_desciption</b>","", $asset_id);
		echo json_encode(array("200",  "success|Physical Asset Added Successfully"));
    } 
	else{
        echo json_encode(array("300",  "warning|Physical Asset couldn't added" ));
	}

?>