<?php

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

?>