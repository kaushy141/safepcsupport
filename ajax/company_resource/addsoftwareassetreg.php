<?php

    $soft_asset_id = $soft_asset_type = $soft_asset_version = $soft_asset_reg_code = $soft_asset_allocated_install_to = $soft_asset_licence_number = $soft_asset_owner = $soft_asset_used_by = $soft_asset_managed_by = $soft_asset_location = $soft_asset_overview = $soft_asset_risk = $soft_asset_cia = $soft_asset_suspect_vulnerabilities = $soft_asset_action_plan = $soft_asset_key_security_control = $soft_asset_potential_action = "";
	$soft_asset_impact = $soft_asset_risk_rating = 0;
    $soft_asset_status = 1;
	$asset_created_by = getLoginId();
    $data           = sanitizePostData($_POST);
    $SoftwareAssets = new SoftwareAssets(0);
	//$asset_tag = $SoftwareAssets->getAssetTag();
    extract($data);
	if($soft_asset_id = $SoftwareAssets->add($soft_asset_type, $soft_asset_version, $soft_asset_reg_code, $soft_asset_allocated_install_to, $soft_asset_licence_number, $soft_asset_owner, $soft_asset_used_by, $soft_asset_managed_by, $soft_asset_location, $soft_asset_overview, $soft_asset_risk, $soft_asset_cia, $soft_asset_impact, $soft_asset_risk_rating, $soft_asset_suspect_vulnerabilities, $soft_asset_action_plan, $soft_asset_key_security_control, $soft_asset_potential_action, $soft_asset_status))
	{
		Activity::add("Added Software Assets <b>$soft_asset_type</b>","", $soft_asset_id);
		echo json_encode(array("200",  "success|Software Asset Added Successfully"));
    } 
	else{
        echo json_encode(array("300",  "warning|Software Asset couldn't added" ));
	}

?>