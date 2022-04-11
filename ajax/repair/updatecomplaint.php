<?php

    $customer_fname               = $customer_lname = $customer_email = $customer_phone =  $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $complaint_product_sku = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $complaint_description = $complaint_problem_details = $complaint_product_serial = $complaint_product_model = $complaint_product_password = $complaint_product_operating_system = $complaint_product_antivirus = $complaint_due_date = $complaint_sales_record_number = $complaint_priority = $complaint_product_condition_at_receiving = $complaint_refund_status = $complaint_order_number = $complaint_dispatched_store = "";
    $app_not_working_problem_mark = $complaint_product_hardware_not_working = array();
    $complaint_id                 = $customer_id = $customer_type_id = $complaint_estimated_cost = $complaint_is_backup = $complaint_is_disk_provided = $complaint_product_is_under_waranty = $complaint_technician_id = $complaint_status = $complaint_store_id = 0;
    $data                         = sanitizePostData($_POST);
    extract($data);
    $Complaint = new Complaint($complaint_id);
    $prevData  = $Complaint->load();
    if (!$prevData) {
        echo json_encode(array("300", "Error|Repair Request not Exists."
        ));
        exit();
    }
    $customer_email                                = strtolower($customer_email);
    $complaint_product_hardware_not_working_string = implode(",", $complaint_product_hardware_not_working);
    /*if ($prevData['customer_email'] != $customer_email) {
        if ($Customer->isEmailExists($customer_email, $customer_id)) {
            echo json_encode(array("300", "Warning|Email Id \"$customer_email\" allready exist with another User."
            ));
            exit();
        }
    }*/
    $Customer = new Customer($customer_id);
    $Customer->update(array("customer_fname" => $customer_fname,
        "customer_lname" => $customer_lname,
        "customer_email" => $customer_email,
        "customer_phone" => $customer_phone,
        "customer_type_id" => $customer_type_id,
		"customer_company" => $customer_company,
		"customer_tax_number" => $customer_tax_number
    ));
    $CustomerAddress = new CustomerAddress($customer_address_id);
    /*if (!$CustomerAddress->isCustomerAddressExists($customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country))
        $CustomerAddress->update(array("customer_id" => $customer_id,
            "customer_address_street_number" => $customer_address_street_number,
            "customer_address_route" => $customer_address_route,
            "customer_address_locality" => $customer_address_locality,
            "customer_address_administrative_area" => $customer_address_administrative_area,
            "customer_address_country" => $customer_address_country,
            "customer_address_postcode" => $customer_address_postcode,
            "customer_address_geo_location" => $customer_address_geo_location,
            "customer_address_status" => 1
        ));*/
		
	$CustomerExistedAddres = $CustomerAddress->isCustomerAddressExists($customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
    if (!$CustomerExistedAddres)
        $CustomerAddress->update(array("customer_id" => $customer_id,
            "customer_address_street_number" => $customer_address_street_number,
            "customer_address_route" => $customer_address_route,
            "customer_address_locality" => $customer_address_locality,
            "customer_address_administrative_area" => $customer_address_administrative_area,
            "customer_address_country" => $customer_address_country,
            "customer_address_postcode" => $customer_address_postcode,
            "customer_address_geo_location" => $customer_address_geo_location,
            "customer_address_status" => 1
        ));
	else
		$customer_address_id = $CustomerExistedAddres;
		
    $Complaint->update(array("complaint_origin_type_id" => $customer_type_id,
        "complaint_store_id" => $complaint_store_id,
        "customer_address_id" => $customer_address_id,
        "complaint_product_serial" => $complaint_product_serial,
		"complaint_product_sku" => $complaint_product_sku,
        "complaint_product_model" => $complaint_product_model,
        "complaint_is_backup" => $complaint_is_backup,
        "complaint_product_password" => $complaint_product_password,
        "complaint_is_disk_provided" => $complaint_is_disk_provided,
        "complaint_product_is_under_waranty" => $complaint_product_is_under_waranty,
        "complaint_product_operating_system" => $complaint_product_operating_system,
        "complaint_product_antivirus" => $complaint_product_antivirus,
        "complaint_description" => $complaint_description,
        "complaint_problem_details" => $complaint_problem_details,
        "complaint_product_hardware_not_working" => $complaint_product_hardware_not_working_string,
        "complaint_due_date" => $complaint_due_date,
        "complaint_priority" => $complaint_priority,
        "complaint_sales_record_number" => $complaint_sales_record_number,
		"complaint_order_number" => $complaint_order_number,
        "complaint_estimated_cost" => $complaint_estimated_cost,
        "complaint_product_condition_at_receiving" => $complaint_product_condition_at_receiving,
        "complaint_technician_id" => $complaint_technician_id,
        "complaint_status" => $complaint_status,
		"complaint_dispatched_store" =>$complaint_dispatched_store,
		"complaint_refund_status" => $complaint_refund_status
    ));
    $CompProbRecord    = new ComplaintProblemRecord(0);
    $existing_problems = explode(",", $prevData['app_not_working_problem_mark']);
    $new_problems      = array_diff($app_not_working_problem_mark, $existing_problems);
    if (count($new_problems) > 0) {
        foreach ($new_problems as $problem_id)
            $CompProbRecord->add($complaint_id, $problem_id, 0);
    }
    $del_problems = array_diff($existing_problems, $app_not_working_problem_mark);
    if (count($del_problems) > 0) {
        foreach ($del_problems as $problem_id)
            $CompProbRecord->delete($complaint_id, $problem_id);
    }
    new SMS($customer_phone, "Your Repair Request #$prevData[complaint_ticket_number] Updated successfully. Your registered email id is $customer_email .");
    $complaint_ticket_number = $Complaint->get("complaint_ticket_number");
    $dataArray               = array(
        "customer_name" => $customer_fname,
        "complaint_ticket_number" => $complaint_ticket_number,
        "login_page" => $app->basePath("customer-login.php"),
        "complaint_link" => $app->basePath("viewcomplaint/$complaint_id")
    );
    $email                   = new Email("Repair Request Ticket #$complaint_ticket_number Updated");
    $email->to($customer_email, $customer_fname, $app->imagePath($Customer->get("customer_image")))->addFile(DOC::CINV($complaint_id), $app->siteName . " Repair Request - $complaint_ticket_number.pdf")->template('updatecomplaint', $dataArray)->send();
    Activity::add("Updated|^|{$prevData['complaint_ticket_number']}", "C", $complaint_id);
    echo json_encode(array("200",  "success|Repair Request Updated Successfully",
        $complaint_id,
        $customer_id,
        $customer_address_id
    ));

?>