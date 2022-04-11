<?php

    $soft_asset_id = $soft_asset_type = $soft_asset_version = $soft_asset_reg_code = $soft_asset_allocated_install_to = $soft_asset_licence_number = $soft_asset_owner = $soft_asset_used_by = $soft_asset_managed_by = $soft_asset_location = $soft_asset_overview = $soft_asset_risk = $soft_asset_cia = $soft_asset_suspect_vulnerabilities = $soft_asset_action_plan = $soft_asset_key_security_control = $soft_asset_potential_action = "";
	$soft_asset_impact = $soft_asset_risk_rating = 0;
    $data           = sanitizePostData($_POST);
    extract($data);
    $SoftwareAssets = new SoftwareAssets($soft_asset_id);
	
	if($SoftwareAssets->isExist())
	{
		$SoftwareAssets->update(array(
			"soft_asset_type"=>$soft_asset_type, 
			"soft_asset_version"=>$soft_asset_version, 
			"soft_asset_reg_code"=>$soft_asset_reg_code,
			"soft_asset_allocated_install_to"=>$soft_asset_allocated_install_to,
			"soft_asset_licence_number"=>$soft_asset_licence_number,
			"soft_asset_owner"=>$soft_asset_owner, 
			"soft_asset_used_by"=>$soft_asset_used_by, 
			"soft_asset_managed_by"=>$soft_asset_managed_by,
			"soft_asset_location"=>$soft_asset_location,
			"soft_asset_overview"=>$soft_asset_overview,			
			"soft_asset_risk"=>$soft_asset_risk, 
			"soft_asset_cia"=>$soft_asset_cia, 
			"soft_asset_suspect_vulnerabilities"=>$soft_asset_suspect_vulnerabilities,
			"soft_asset_action_plan"=>$soft_asset_action_plan,
			"soft_asset_key_security_control"=>$soft_asset_key_security_control,			
			"soft_asset_potential_action"=>$soft_asset_potential_action, 
			"soft_asset_impact"=>$soft_asset_impact, 
			"soft_asset_risk_rating"=>$soft_asset_risk_rating			
			)
		);
		Activity::add("Updated Software Assets <b>$soft_asset_type</b>","", $soft_asset_id);
        echo json_encode(array("200",  "success|Software Asset Updated Successfully"));
    } else
        echo json_encode(array("300",  "warning|Software Asset not found."));

?>