<?php
 
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

?>