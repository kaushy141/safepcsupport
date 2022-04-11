<?php

    $asset_id = $asset_desciption = $asset_manufaturer = $asset_model = $asset_serial_number = $asset_tag = $asset_processor = $asset_ram = $asset_size = $asset_owner = $asset_managed_by = $asset_used_by = $asset_location = $asset_overview = $asset_risk = $asset_cia = $asset_impact = $asset_risk_rating = $asset_suspect_vulnerabilities = $asset_action_plan = "";    
    $data           = sanitizePostData($_POST);
    extract($data);
    $PhysicalAsset = new PhysicalAsset($asset_id);
	
	if($PhysicalAsset->isExist())
	{
		$PhysicalAsset->update(array(
			"asset_desciption"=>$asset_desciption, 
			"asset_manufaturer"=>$asset_manufaturer, 
			"asset_model"=>$asset_model,
			"asset_serial_number"=>$asset_serial_number,
			"asset_processor"=>$asset_processor,
			"asset_ram"=>$asset_ram, 
			"asset_size"=>$asset_size, 
			"asset_owner"=>$asset_owner,
			"asset_managed_by"=>$asset_managed_by,
			"asset_used_by"=>$asset_used_by,			
			"asset_location"=>$asset_location, 
			"asset_overview"=>$asset_overview, 
			"asset_risk"=>$asset_risk,
			"asset_cia"=>$asset_cia,
			"asset_impact"=>$asset_impact,			
			"asset_risk_rating"=>$asset_risk_rating, 
			"asset_suspect_vulnerabilities"=>$asset_suspect_vulnerabilities, 
			"asset_action_plan"=>$asset_action_plan			
			)
		);
		Activity::add("Updated Physical Asset <b>$asset_desciption</b>","", $asset_id);
        echo json_encode(array("200",  "success|Physical Asset Updated Successfully"));
    } else
        echo json_encode(array("300",  "warning|Physical Asset not found."));

?>