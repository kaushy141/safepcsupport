<?php
function addphysicalassetreg()
{
    global $app;
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
}
function updatephysicalasset()
{
    global $app;
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
}

function getlicencefoldercontent(){
	global $app;
	$licence_folder = '';
	$data           = sanitizePostData($_POST);
    extract($data);
	$licences = new Licences();
	echo json_encode(array("200",  "success|Licence folder conent loaded", $licences->getItemsByFolder($licence_folder)));
}

function removelicence(){
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$licences = new Licences($id);
	if($licences->isActive())
	{
		$record = $licences->getDetails();
		$file_extension = pathinfo(BP.$record['licence_file_path'], PATHINFO_EXTENSION);	
		$licence_file_path = DOC_UPLOAD_DIR."licence/del/".getDirectorySeparatorPath()."licence-".time().".$file_extension";
		if(move_file(BP.$record['licence_file_path'], BP.$licence_file_path))
		{
			unlink(BP.$record['licence_file_path']);
			$licences->update(array("licence_file_path" => $licence_file_path));
			$licences->Deactivate();
			Activity::add("Removed Licence <b>$record[licence_name]</b>","", $id);
			echo json_encode(array("200",  "success|Licence file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete licence file."));
	}
	else
		echo json_encode(array("300",  "warning|Licence not found."));
}
function removetemplates(){
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$templates = new Templates($id);
	if($templates->isActive())
	{
		$record = $templates->getDetails();
		$file_extension = pathinfo(BP.$record['templates_file_path'], PATHINFO_EXTENSION);	
		$templates_file_path = DOC_UPLOAD_DIR."templates/del/templates-".time().".$file_extension";
		if(move_file(BP.$record['templates_file_path'], BP.$templates_file_path))
		{
			unlink(BP.$record['templates_file_path']);
			$templates->update(array("templates_file_path" => $templates_file_path));
			$templates->Deactivate();
			Activity::add("Removed Templates <b>$record[templates_name]</b>","", $id);
			echo json_encode(array("200",  "success|Templates file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete Templates file."));
	}
	else
		echo json_encode(array("300",  "warning|Templates not found."));
}
function removepolicies(){
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$policies = new Policies($id);
	if($policies->isActive())
	{
		$record = $policies->getDetails();
		$file_extension = pathinfo(BP.$record['policies_file_path'], PATHINFO_EXTENSION);	
		$policies_file_path = DOC_UPLOAD_DIR."policies/del/policies-".time().".$file_extension";
		if(move_file(BP.$record['policies_file_path'], BP.$policies_file_path))
		{
			unlink(BP.$record['policies_file_path']);
			$policies->update(array("policies_file_path" => $policies_file_path));
			$policies->Deactivate();
			Activity::add("Removed Policy <b>$record[policies_name]</b>","", $id);
			echo json_encode(array("200",  "success|Policies file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete policies file."));
	}
	else
		echo json_encode(array("300",  "warning|Policies not found."));
}

function removecompanyinvoice(){
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$CompanyInvoice = new CompanyInvoice($id);
	if($CompanyInvoice->isActive())
	{
		$record = $CompanyInvoice->getDetails();
		$file_extension = pathinfo(BP.$record['company_invoice_file_path'], PATHINFO_EXTENSION);	
		$company_invoice_file_path = DOC_UPLOAD_DIR."company/invoice/del/invoice-".time().".$file_extension";
		if(move_file(BP.$record['company_invoice_file_path'], BP.$company_invoice_file_path))
		{
			unlink(BP.$record['company_invoice_file_path']);
			$CompanyInvoice->update(array("company_invoice_file_path" => $company_invoice_file_path));
			$CompanyInvoice->Deactivate();
			Activity::add("Removed Company Invoice <b>$record[company_invoice_name]</b>","", $id);
			echo json_encode(array("200",  "success|Company Invoice file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete Company Invoice file."));
	}
	else
		echo json_encode(array("300",  "warning|Company Invoice not found."));
}

function removeisms(){
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$isms = new Isms($id);
	if($isms->isActive())
	{
		$record = $isms->getDetails();
		$file_extension = pathinfo(BP.$record['isms_file_path'], PATHINFO_EXTENSION);	
		$isms_file_path = DOC_UPLOAD_DIR."isms/del/isms-".time().".$file_extension";
		if(move_file(BP.$record['isms_file_path'], BP.$isms_file_path))
		{
			unlink(BP.$record['isms_file_path']);
			$isms->update(array("isms_file_path" => $isms_file_path));
			$isms->Deactivate();
			Activity::add("Removed isms register <b>$record[isms_name]</b>","", $id);
			echo json_encode(array("200",  "success|ISMS Register file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete isms register file."));
	}
	else
		echo json_encode(array("300",  "warning|ISMS Register not found."));
}

function removecontract(){
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$ContractInsurance = new ContractInsurance($id);
	if($ContractInsurance->isActive())
	{
		$record = $ContractInsurance->getDetails();
		$file_extension = pathinfo(BP.$record['cont_ins_file_path'], PATHINFO_EXTENSION);
		$cont_ins_file_path = DOC_UPLOAD_DIR."contract/del/contract-".time().".$file_extension";
		if(move_file(BP.$record['cont_ins_file_path'], BP.$cont_ins_file_path))
		{
			unlink(BP.$record['cont_ins_file_path']);
			$ContractInsurance->update(array("cont_ins_file_path" => $cont_ins_file_path));
			$ContractInsurance->Deactivate();
			Activity::add("Removed Contract Document <b>$record[licence_name]</b>","", $id);
			echo json_encode(array("200",  "success|Contarct record removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete Contarct record."));
	}
	else
		echo json_encode(array("300",  "warning|Contarct record not found."));
}

function addcontractinsurance(){
	global $app;
    $cont_ins_id =  $cont_ins_risk = $cont_ins_risk_prob = $cont_ins_risk_rating = 0;
	$cont_ins_description =	$cont_ins_owner = $cont_ins_risk_treatment = $cont_ins_file_path = "";
	$cont_ins_status = 1;
    $data           = sanitizePostData($_POST);
    $ContractInsurance = new ContractInsurance(0);
    extract($data);
	if($cont_ins_file_path !="" && file_exists(BP.$cont_ins_file_path))
	{
		$source = $cont_ins_file_path;
		$file_extension = pathinfo(BP.$cont_ins_file_path, PATHINFO_EXTENSION);
		$cont_ins_file_path = DOC_UPLOAD_DIR."contarct/".getDirectorySeparatorPath()."contarct-".time().".$file_extension";
		move_file($source, $cont_ins_file_path);	
	
		if($cont_ins_id = $ContractInsurance->add($cont_ins_description, $cont_ins_owner, $cont_ins_risk, $cont_ins_risk_prob, $cont_ins_risk_rating, $cont_ins_risk_treatment, $cont_ins_file_path, $cont_ins_status))
		{
			Activity::add("Added Contract Document <b>$cont_ins_description</b>","", $cont_ins_id);
			echo json_encode(array("200",  "success|Contract Insurance Added Successfully"));
		} 
		else{
			echo json_encode(array("300",  "warning|Contract Insurance couldn't added" ));
		}		
	}
	else
		echo json_encode(array("300",  "warning|Contarct Document not found" ));
}

function addsoftwareassetreg(){
	global $app;
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
}

function updatesoftwareasset()
{
    global $app;
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
}

function addutilities(){
	global $app;
    $utility_description = $utility_used_by = $utility_managed_by = $utility_owner = $utility_location = $utility_risk = $utility_cia = $utility_contract_number = $utility_person_to_contact = $utility_contact_number = $utility_overview = $utility_key_security_tool = $utility_potential_action = $utility_action_plan = $utility_doc_file_path = ""; 
	$utility_status = 1;
	$utility_id = $utility_impact = $utility_risk_rating = 0;
    $data           = sanitizePostData($_POST);
    $Utilities = new Utilities(0);
    extract($data);
	if($utility_id = $Utilities->add($utility_description, $utility_used_by, $utility_managed_by, $utility_owner, $utility_location, $utility_risk, $utility_cia, $utility_impact, $utility_risk_rating, $utility_contract_number, $utility_person_to_contact, $utility_contact_number, $utility_overview, $utility_key_security_tool, $utility_potential_action, $utility_action_plan,  $utility_status))
	{
		if ($utility_doc_file_path != "") {
				$file_name = pathinfo(BP.$utility_doc_file_path);
                $extension  = strtolower($file_name['extension']);
				$utility_doc_file = "upload/doc/utility/".getDirectorySeparatorPath()."utility-$utility_id-" . time() . ".$extension";
				if (move_file(BP.$utility_doc_file_path, BP.$utility_doc_file))
					$Utilities->update(array("utility_doc_file" => $utility_doc_file));
		}
		Activity::add("Added Utility <b>$utility_description</b>","", $utility_id);
		echo json_encode(array("200",  "success|Utilities Added Successfully"));
    } 
	else{
        echo json_encode(array("300",  "warning|Utilities couldn't added" ));
	}
}

function updateutilities(){
	global $app;
    $utility_description = $utility_used_by = $utility_managed_by = $utility_owner = $utility_location = $utility_risk = $utility_cia = $utility_contract_number = $utility_person_to_contact = $utility_contact_number = $utility_overview = $utility_key_security_tool = $utility_potential_action = $utility_action_plan =  $utility_doc_file_path = ""; 
	$utility_status = 1;
	$utility_id = $utility_impact = $utility_risk_rating = 0;
    $data           = sanitizePostData($_POST);
    extract($data);
    $Utilities = new Utilities($utility_id);
	
	if($Utilities->isExist())
	{
		$Utilities->update(array(
			"utility_description"=>$utility_description, 
			"utility_used_by"=>$utility_used_by, 
			"utility_managed_by"=>$utility_managed_by,
			"utility_owner"=>$utility_owner,
			"utility_location"=>$utility_location,
			"utility_risk"=>$utility_risk, 
			"utility_cia"=>$utility_cia, 
			"utility_contract_number"=>$utility_contract_number,
			"utility_person_to_contact"=>$utility_person_to_contact,
			"utility_contact_number"=>$utility_contact_number,			
			"utility_overview"=>$utility_overview, 
			"utility_key_security_tool"=>$utility_key_security_tool, 
			"utility_potential_action"=>$utility_potential_action,
			"utility_action_plan"=>$utility_action_plan,
			"utility_impact"=>$utility_impact,			
			"utility_risk_rating"=>$utility_risk_rating		
			)
		);
		if ($utility_doc_file_path != "") {
				$file_name = pathinfo(BP.$utility_doc_file_path);
                $extension  = strtolower($file_name['extension']);
				$utility_doc_file = "upload/doc/utility/".getDirectorySeparatorPath()."utility-$utility_id-" . time() . ".$extension";
				if (move_file(BP.$utility_doc_file_path, BP.$utility_doc_file))
					$Utilities->update(array("utility_doc_file" => $utility_doc_file));
		}
		Activity::add("Updated Utility <b>$utility_description</b>","", $utility_id);
        echo json_encode(array("200",  "success|Utility Updated Successfully"));
    } else
        echo json_encode(array("300",  "warning|Utility not found."));
}

function removemanual(){
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$ManualTrainingDoc = new ManualTrainingDoc($id);
	if($ManualTrainingDoc->isActive())
	{
		$record = $ManualTrainingDoc->getDetails();
		$file_extension = pathinfo(BP.$record['manual_file_path'], PATHINFO_EXTENSION);
		$manual_file_path = DOC_UPLOAD_DIR."manual/del/manual-".time().".$file_extension";
		if(move_file(BP.$record['manual_file_path'], BP.$manual_file_path))
		{
			unlink(BP.$record['manual_file_path']);
			$ManualTrainingDoc->update(array("manual_file_path" => $manual_file_path));
			$ManualTrainingDoc->Deactivate();
			echo json_encode(array("200",  "success|Manual & Training Document file removed Successfully"));
			Activity::add("Removed manual <b>$record[manual_name]</b>","", $id);
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete Manual & Training Document file."));
	}
	else
		echo json_encode(array("300",  "warning|Manual & Training Document not found."));
}

function removephysicalasset(){
	global $app;
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
}


function removesoftwareasset(){
	global $app;
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
}

function addequipmentdisposal()
{
    global $app;
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
}

function updateequipmentdisposal(){
	global $app;
	$eqipment_disposal_id = 0;
	$eqipment_disposal_hardware_id = 0;
    $eqipment_disposal_manufacturer= $eqipment_disposal_model= $eqipment_disposal_serial_number= $eqipment_disposal_log_no= $eqipment_disposal_disposed_to= $eqipment_disposal_reason= $eqipment_disposal_destruction_method= $eqipment_disposal_destroyed_date= $eqipment_disposal_destroyed_by = "";
    $eqipment_disposal_status = 1;
	$eqipment_disposal_created_user_id = getLoginId();
    $data           = sanitizePostData($_POST);
    extract($data);
    $DestructionMethod = new DestructionMethod($eqipment_disposal_id);
	if($eqipment_disposal_destroyed_date !=""){
		$eqipment_disposal_destroyed_date = date("Y-m-d H:i:s", strtotime($eqipment_disposal_destroyed_date));
	}
	if($DestructionMethod->isExist())
	{
		$DestructionMethod->update(array(
			"eqipment_disposal_hardware_id"=>$eqipment_disposal_hardware_id, 
			"eqipment_disposal_manufacturer"=>$eqipment_disposal_manufacturer, 
			"eqipment_disposal_model"=>$eqipment_disposal_model,
			"eqipment_disposal_serial_number"=>$eqipment_disposal_serial_number,
			"eqipment_disposal_log_no"=>$eqipment_disposal_log_no,
			"eqipment_disposal_disposed_to"=>$eqipment_disposal_disposed_to, 
			"eqipment_disposal_reason"=>$eqipment_disposal_reason, 
			"eqipment_disposal_destruction_method"=>$eqipment_disposal_destruction_method,
			"eqipment_disposal_destroyed_date"=>$eqipment_disposal_destroyed_date,
			"eqipment_disposal_destroyed_by"=>$eqipment_disposal_destroyed_by
			)
		);
		Activity::add("Updated Equipment Disposal Log <b>$eqipment_disposal_model ($eqipment_disposal_serial_number)</b>","", $eqipment_disposal_id);
        echo json_encode(array("200",  "success|Equipment Disposal Log Updated Successfully"));
    } else
        echo json_encode(array("300",  "warning|Equipment Disposal Log not found."));
}

function removedisposaldestructionlog(){
	global $app;
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
}

function addrfcrecord()
{
    global $app; 
	$rfc_require_by_date = $rfc_date_of_request = $rfc_serial_number = $rfc_requester = $rfc_circulation_list =  $rfc_request_details = $rfc_request_reason = $rfc_service_owner_approval = $rfc_back_out_paln = $rfc_not_approved_reason = $rfc_completion_acceptance = $rfc_signature = $rfc_name = $rfc_date = "";
    $rfc_status = 1;
    $data           = sanitizePostData($_POST);
    $Rfc = new Rfc(0);
    extract($data);	
	$rfc_code = Rfc::getRfcNumber();	
	if($rfc_id = $Rfc->add($rfc_code, $rfc_require_by_date, $rfc_date_of_request, $rfc_serial_number, $rfc_requester, $rfc_circulation_list, $rfc_request_details, $rfc_request_reason, $rfc_service_owner_approval, $rfc_back_out_paln, $rfc_not_approved_reason, $rfc_completion_acceptance, $rfc_signature, $rfc_name, $rfc_date, $rfc_status))
	{
		$prevData = $Rfc->load();
		if ($rfc_signature != "") {
			$file_extension = pathinfo(BP.$rfc_signature, PATHINFO_EXTENSION);
			$new_rfc_signature = "upload/rfc/".getDirectorySeparatorPath()."sign/$rfc_code-" . time() . ".$file_extension";
			move_file(BP.$rfc_signature, BP.$new_rfc_signature);
		} else
			$new_rfc_signature = "";
			
		$rfc1 = new Rfc($rfc_id);
		$rfc1->update(array("rfc_signature"=>$new_rfc_signature));
			
		Activity::add("Added Change Management Request <b>SR.No. $rfc_serial_number ($rfc_code)</b>","", $rfc_id);
		echo json_encode(array("200",  "success|Change Management Request Added Successfully"));
    } 
	else{
        echo json_encode(array("300",  "warning|Change Management Request couldn't added" ));
	}
}

function updaterfcrecord(){
	global $app; 
	$rfc_id = 0;
	$rfc_require_by_date = $rfc_date_of_request = $rfc_serial_number = $rfc_requester = $rfc_circulation_list =  $rfc_request_details = $rfc_request_reason = $rfc_service_owner_approval = $rfc_back_out_paln = $rfc_not_approved_reason = $rfc_completion_acceptance = $rfc_signature = $rfc_name = $rfc_date = "";
    $rfc_status = 1;
    $data           = sanitizePostData($_POST);
    extract($data);
    $Rfc = new Rfc($rfc_id);    
	
	if($Rfc->isExist())
	{
		$prevData = $Rfc->load();
		if ($rfc_signature != "") {
			$file_extension = pathinfo(BP.$rfc_signature, PATHINFO_EXTENSION);
			$new_rfc_signature = "upload/rfc/".getDirectorySeparatorPath()."sign/$prevData[rfc_code]-" . time() . ".$file_extension";
			move_file(BP.$rfc_signature, BP.$new_rfc_signature);
		} else
			$new_rfc_signature = $prevData['rfc_signature'];
		$Rfc->update(array(
			"rfc_require_by_date"=>$rfc_require_by_date, 
			"rfc_date_of_request"=>$rfc_date_of_request, 
			"rfc_serial_number"=>$rfc_serial_number,
			"rfc_requester"=>$rfc_requester,
			"rfc_circulation_list"=>$rfc_circulation_list,
			"rfc_request_details"=>$rfc_request_details, 
			"rfc_request_reason"=>$rfc_request_reason, 
			"rfc_service_owner_approval"=>$rfc_service_owner_approval,
			"rfc_back_out_paln"=>$rfc_back_out_paln,
			"rfc_not_approved_reason"=>$rfc_not_approved_reason,
			"rfc_completion_acceptance"=>$rfc_completion_acceptance,
			"rfc_signature"=>$new_rfc_signature,
			"rfc_name"=>$rfc_name,
			"rfc_date"=>$rfc_date			
			)
		);
		Activity::add("Updated Change Management Request <b>SR.No. $rfc_serial_number ($rfc_code)</b>","", $rfc_id);
        echo json_encode(array("200",  "success|Change Management Request Updated Successfully"));
    } else
        echo json_encode(array("300",  "warning|Change Management Request not found."));
}

function removerfcrecord(){
	global $app;
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
}

function addchecklistreport()
{
    global $app; 
	$checklist_employee_name = $checklist_name = $checklist_file_path = "";
	$checklist_status = 1;
    $data           = sanitizePostData($_POST);
    $ChecklistReport = new ChecklistReport(0);
    extract($data);
	
	if($checklist_file_path !="" && file_exists(BP.$checklist_file_path))
	{
		$file_extension = pathinfo($checklist_file_path, PATHINFO_EXTENSION);	
		$checklist_file = DOC_UPLOAD_DIR."checklist/".getDirectorySeparatorPath()."".getDirectorySeparatorPath()."checklist-".time().".$file_extension";
		move_file($checklist_file_path, $checklist_file);	
	
		$checklist_origin = $ChecklistReport->getCheckListTypeCount($checklist_name) ? 1 : 0;
		if($checklist_id = $ChecklistReport->add($checklist_employee_name, $checklist_name, $checklist_origin, $checklist_file, $checklist_status))
		{			
			Activity::add("Added Checklist report <b>$checklist_name</b>","", $checklist_id);
			echo json_encode(array("200",  "success|Checklist report Added Successfully"));
		} 
		else{
			echo json_encode(array("300",  "warning|Checklist report couldn't added" ));
		}	
	}
}

function updatechecklistreport(){
	global $app;
	$checklist_id = 0; 
	$checklist_employee_name = $checklist_name = $checklist_file_path = "";
    $data           = sanitizePostData($_POST);
    extract($data);
    $ChecklistReport = new ChecklistReport($checklist_id);    
	
	if($ChecklistReport->isExist())
	{
		$checkList_update_array = array(
			"checklist_employee_name"=>$checklist_employee_name
			);
		if($checklist_file_path !="" && file_exists(BP.$checklist_file_path))
		{
			$file_extension = pathinfo($checklist_file_path, PATHINFO_EXTENSION);	
			$checklist_file = DOC_UPLOAD_DIR."checklist/".getDirectorySeparatorPath()."checklist-".time().".$file_extension";
			move_file($checklist_file_path, $checklist_file);
			$checkList_update_array['checklist_file'] = $checklist_file;
		}
		$ChecklistReport->update($checkList_update_array);
		Activity::add("Updated Checklist report <b>$checklist_name</b>","", $checklist_id);
        echo json_encode(array("200",  "success|Checklist report Updated Successfully"));
    } else
        echo json_encode(array("300",  "warning|Checklist report not found."));
}

function removechecklistreport(){
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$ChecklistReport = new ChecklistReport($id);
	if($ChecklistReport->isActive())
	{
		$record = $ChecklistReport->getDetails();	
		$ChecklistReport->Deactivate();
		Activity::add("Removed Checklist report <b>$record[checklist_name]</b>","", $id);
		echo json_encode(array("200",  "success|Checklist report removed Successfully"));
		
	}
	else
		echo json_encode(array("300",  "warning|Checklist report not found."));
}

function addcompanyrecord()
{
    global $app; 
	 $company_name = $company_number = $company_address = $company_contact = $company_carrier_licence_number = $company_environment_permit_number = $company_hazardous_waste_licence_number = $company_bank_details = $company_vehicle_reg_number = $company_registered_trademark = $company_ico_registration_number = $company_iso_9001 = $company_iso_14001 = $company_registered_in_england_and_wales = $company_vat_registration_number = $company_trademark = $company_trademark_file_path = "";
	$company_status = 1;
	$company_id = 0;
    $data           = sanitizePostData($_POST);    
    extract($data);
	$company = new Company(0);
	
	$company_id = $company->insert(array(
				"company_name" => $company_name,
				"company_number" => $company_number,
				"company_address" => $company_address, 
				"company_contact" => $company_contact, 
				"company_carrier_licence_number" => $company_carrier_licence_number, 
				"company_environment_permit_number" => $company_environment_permit_number, 
				"company_hazardous_waste_licence_number" => $company_hazardous_waste_licence_number, 
				"company_bank_details" => $company_bank_details, 
				"company_vehicle_reg_number" => $company_vehicle_reg_number, 
				"company_registered_trademark" => $company_registered_trademark, 
				"company_ico_registration_number" => $company_ico_registration_number, 
				"company_iso_9001" => $company_iso_9001, 
				"company_iso_14001" => $company_iso_14001, 
				"company_registered_in_england_and_wales" => $company_registered_in_england_and_wales, 
				"company_vat_registration_number" => $company_vat_registration_number,
				"company_status" => $company_status,
	));
	if($company_id)
	{
		if (isset($_SESSION['UPLOAD'][$field_handler]['PIC'])) {
			$image_name = pathinfo($_SESSION['UPLOAD'][$field_handler]['PIC']);
			$extension  = strtolower($image_name['extension']);
			$company_trademark = "upload/company/".getDirectorySeparatorPath()."company-".time().".$extension";
			$company_trademark_file_path = BP.$company_trademark;
			if (rename($_SESSION['UPLOAD'][$field_handler]['PIC'], $company_trademark_file_path)) {
				$company->update(array("company_trademark" => $company_trademark
				));
			}
			unset($_SESSION['UPLOAD'][$field_handler]['PIC']);
		}			
		Activity::add("Added Company record info <b>$company_name</b>","", $company_id);
		echo json_encode(array("200",  "success|Company record Added Successfully"));
	} 
	else{
		echo json_encode(array("300",  "warning|Company record couldn't added" ));
	}	
	
}

function updatecompanyrecord()
{
    global $app; 
	 $company_name = $company_address = $company_contact = $company_carrier_licence_number = $company_environment_permit_number = $company_hazardous_waste_licence_number = $company_bank_details = $company_vehicle_reg_number = $company_registered_trademark = $company_ico_registration_number = $company_iso_9001 = $company_iso_14001 = $company_registered_in_england_and_wales = $company_vat_registration_number = $company_trademark = $company_trademark_file_path = "";
	$company_status = 1;
	$company_id = 0;
    $data           = sanitizePostData($_POST);    
    extract($data);
	$company = new Company($company_id);
	
	
	if($company->isExist())
	{
		$companyData = $company->load();
		$company_trademark = $companyData['company_trademark'];		
		$company->update(array(
					"company_name" => $company_name,
					"company_address" => $company_address, 
					"company_contact" => $company_contact, 
					"company_carrier_licence_number" => $company_carrier_licence_number, 
					"company_environment_permit_number" => $company_environment_permit_number, 
					"company_hazardous_waste_licence_number" => $company_hazardous_waste_licence_number, 
					"company_bank_details" => $company_bank_details, 
					"company_vehicle_reg_number" => $company_vehicle_reg_number, 
					"company_registered_trademark" => $company_registered_trademark, 
					"company_ico_registration_number" => $company_ico_registration_number, 
					"company_iso_9001" => $company_iso_9001, 
					"company_iso_14001" => $company_iso_14001, 
					"company_registered_in_england_and_wales" => $company_registered_in_england_and_wales, 
					"company_vat_registration_number" => $company_vat_registration_number
		));
		
		if (isset($_SESSION['UPLOAD'][$field_handler]['PIC'])) {
			$image_name = pathinfo($_SESSION['UPLOAD'][$field_handler]['PIC']);
			$extension  = strtolower($image_name['extension']);
			$company_trademark = "upload/company/".getDirectorySeparatorPath()."company-".time().".$extension";
			$company_trademark_file_path = BP.$company_trademark;
			if (rename($_SESSION['UPLOAD'][$field_handler]['PIC'], $company_trademark_file_path)) {
				$company->update(array("company_trademark" => $company_trademark
				));
			}
			unset($_SESSION['UPLOAD'][$field_handler]['PIC']);
		}		
		Activity::add("Updated Company record info <b>$company_name</b>","", $company_id);
		echo json_encode(array("200",  "success|Company record updated Successfully"));
	} 
	else{
		echo json_encode(array("300",  "warning|Company record couldn't updated" ));
	}
}
	
function updatecompanyrecordstatus(){
	global $app;
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Company = new Company($idvalue);
        $status ? $Company->Activate() : $Company->Deactivate();
        Activity::add(status($status) . " Company <b>" . $Company->get('company_name') . "</b> staus");
        echo json_encode(array("200",  "success|Company " . status($status) . " Successfully"
        ));
    } else
        echo json_encode(array("300", "warning|Company Status not found."
        ));
}

function addsupplierrecord()
{
    global $app; 
	$supplier_name = $supplier_email = $supplier_contact = $supplier_address = $supplier_description = $supplier_doc_file_path = $supplier_company_name =  $supplier_company_number = $supplier_website = $supplier_vat_no = $supplier_bank_details = $supplier_skype_id = $supplier_supply_process = "";
	$supplier_status = 1;
	$supplier_id = $supplier_type_id = 0;
    $data           = sanitizePostData($_POST);    
    extract($data);
	$supplier = new Supplier(0);
	
	$supplier_id = $supplier->insert(array(
				"supplier_name" => $supplier_name,
				"supplier_email" => $supplier_email, 
				"supplier_contact" => $supplier_contact, 
				"supplier_address" => $supplier_address, 
				"supplier_address" => $supplier_address, 
				"supplier_description" => $supplier_description, 
				"supplier_status" => $supplier_status,
				"supplier_type_id" => $supplier_type_id,
				"supplier_company_name" => $supplier_company_name,
				"supplier_company_number" => $supplier_company_number,
				"supplier_website" => $supplier_website,
				"supplier_vat_no" => $supplier_vat_no,
				"supplier_bank_details" => $supplier_bank_details,
				"supplier_skype_id" => $supplier_skype_id,
				"supplier_supply_process" =>$supplier_supply_process
	));
	if($supplier_id)
	{
		if ($supplier_doc_file_path != "") {
			$file_name = pathinfo(BP.$supplier_doc_file_path);
			$extension  = strtolower($file_name['extension']);
			$supplier_doc_file = "upload/doc/supplier/".getDirectorySeparatorPath()."supplier-$supplier_id-" . time() . ".$extension";
			if (move_file(BP.$supplier_doc_file_path, BP.$supplier_doc_file))
				$supplier->update(array("supplier_doc_file" => $supplier_doc_file));
		}		
		Activity::add("Added Supplier record info <b>$supplier_name</b>","", $supplier_id);
		echo json_encode(array("200",  "success|Supplier record Added Successfully"));
	} 
	else{
		echo json_encode(array("300",  "warning|Supplier record couldn't added" ));
	}	
}

function updatesupplierrecord()
{
    global $app; 
	$supplier_name = $supplier_email = $supplier_contact = $supplier_address = $supplier_description = $supplier_doc_file_path = $supplier_company_name =  $supplier_company_number = $supplier_website = $supplier_vat_no = $supplier_bank_details = $supplier_skype_id = $supplier_supply_process = "";
	$supplier_status = 1;
	$supplier_id = $supplier_type_id = 0;
    $data           = sanitizePostData($_POST);    
    extract($data);
	$supplier = new Supplier($supplier_id);
	if($supplier->isExist())
	{
		$supplier->update(array(
					"supplier_name" => $supplier_name,
					"supplier_email" => $supplier_email, 
					"supplier_contact" => $supplier_contact, 
					"supplier_address" => $supplier_address, 
					"supplier_address" => $supplier_address, 
					"supplier_description" => $supplier_description,
					"supplier_type_id" => $supplier_type_id,
					"supplier_company_name" => $supplier_company_name,
					"supplier_company_number" => $supplier_company_number,
					"supplier_website" => $supplier_website,
					"supplier_vat_no" => $supplier_vat_no,
					"supplier_bank_details" => $supplier_bank_details,
					"supplier_skype_id" => $supplier_skype_id,
					"supplier_supply_process" =>$supplier_supply_process
		));
		
		if ($supplier_doc_file_path != "") {
			$file_name = pathinfo(BP.$supplier_doc_file_path);
			$extension  = strtolower($file_name['extension']);
			$supplier_doc_file = "upload/doc/supplier/".getDirectorySeparatorPath()."supplier-$supplier_id-" . time() . ".$extension";
			if (move_file(BP.$supplier_doc_file_path, BP.$supplier_doc_file))
				$supplier->update(array("supplier_doc_file" => $supplier_doc_file));
		}		
						
		Activity::add("Updated Supplier record info <b>$supplier_name</b>","", $supplier_id);
		echo json_encode(array("200",  "success|Supplier record Updated Successfully"));
	}
	else
	{
		echo json_encode(array("300",  "warning|Supplier record not found" ));
	}
}

function updatesupplierrecordstatus(){
	global $app;
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Supplier = new Supplier($idvalue);
        $status ? $Supplier->Activate() : $Supplier->Deactivate();
        Activity::add(status($status) . " Supplier <b>" . $Supplier->get('supplier_name') . "</b> staus");
        echo json_encode(array("200",  "success|Supplier " . status($status) . " Successfully"
        ));
    } else
        echo json_encode(array("300", "warning|Supplier not found."
        ));
}
	
?>