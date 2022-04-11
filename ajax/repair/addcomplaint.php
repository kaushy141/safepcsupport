<?php

    $customer_fname               = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $complaint_description = $complaint_problem_details = $complaint_product_serial = $complaint_product_sku = $complaint_product_model = $complaint_product_password = $complaint_product_operating_system = $complaint_product_antivirus = $complaint_due_date = $complaint_sales_record_number = $complaint_priority = $complaint_product_condition_at_receiving = $complaint_refund_status = $complaint_order_number = $complaint_dispatched_store = "";
    $app_not_working_problem_mark = $complaint_product_hardware_not_working = array();
    $customer_type_id             = $complaint_estimated_cost = $complaint_is_backup = $complaint_is_disk_provided = $complaint_product_is_under_waranty = $complaint_technician_id = $complaint_status = $complaint_store_id = 0;
    $customer_status              = 1;
    $customer_password            = gePassword();
    $customer_is_mobile_verified  = 0;
    $customer_is_email_verified   = 0;
    $customer_address_status      = 1;
    $data                         = sanitizePostData($_POST);
    extract($data);
    $customer_email                                = strtolower($customer_email);
    $complaint_product_hardware_not_working_string = implode(",", $complaint_product_hardware_not_working);
    //echo implode("= $",array_keys($data));    
    $complaint_created_by                          = $customer_created_by = $_SESSION['user_id'];
    $ComplaintTax                                  = new ComplaintTax();
    $complaint_tax_id                              = $ComplaintTax->getCurrentId();
    if (!$complaint_tax_id) {
        echo json_encode(array("300", "Warning|Complaint Tax Information Not found Please Check Complaint Tax Information. Try again"
        ));
        exit();
    }
    $is_new_customer       = false;
    $Customer              = new Customer(0);
    $complaint_customer_id = $Customer->isEmailExists($customer_email);
    if (!$complaint_customer_id) {
        $customer_image        = DEFAULT_USER_IMAGE;
        $customer_code         = $Customer->getNewCode($customer_email, $customer_fname . $customer_lname);
        $complaint_customer_id = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
        $is_new_customer       = true;
    } else {
        $Customer = new Customer($complaint_customer_id);
        $Customer->update(array("customer_fname" => $customer_fname,
            "customer_lname" => $customer_lname,
            "customer_phone" => $customer_phone,
            "customer_type_id" => $customer_type_id,
			"customer_company" => $customer_company
        ));
    }
    if (!$complaint_customer_id) {
        echo json_encode(array("300", "Warning|Customer Details Not Full filled. Try again"
        ));
        exit();
    }
    $CustomerAddress     = new CustomerAddress(0);
    $customer_address_id = $CustomerAddress->isCustomerAddressExists($complaint_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
    if (!$customer_address_id)
        $customer_address_id = $CustomerAddress->add($complaint_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status);
    $Complaint = new Complaint(0);
    if (count($complaint_product_hardware_not_working) > 0) {
        $ht                      = new HardwareType($complaint_product_hardware_not_working[0]);
        $complaint_ticket_number = $Complaint->getComplaintTicketNumber($ht->get("hardware_code"));
    } else
        $complaint_ticket_number = $Complaint->getComplaintTicketNumber();
	//$repairValues = compact('customer_type_id', 'complaint_store_id', 'complaint_customer_id', 'customer_address_id', 'complaint_ticket_number', 'complaint_product_serial', 'complaint_product_sku', 'complaint_product_model', 'complaint_is_backup', 'complaint_product_password', 'complaint_is_disk_provided', 'complaint_product_is_under_waranty', 'complaint_product_operating_system', 'complaint_product_antivirus', 'complaint_description', 'complaint_problem_details', 'complaint_product_hardware_not_working_string', 'complaint_due_date', 'complaint_priority', 'complaint_sales_record_number', 'complaint_estimated_cost', 'complaint_product_condition_at_receiving', 'complaint_technician_id', 'complaint_tax_id', 'complaint_created_by', 'complaint_status');
	
	$complaint_id = $Complaint->insert(array("complaint_origin_type_id" => $customer_type_id,
        "complaint_store_id" => $complaint_store_id,
		"complaint_customer_id" => $complaint_customer_id,
        "customer_address_id" => $customer_address_id,
		"complaint_ticket_number" => $complaint_ticket_number,
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
		"complaint_tax_id" => $complaint_tax_id,
		"complaint_created_date" => "NOW()",
		"complaint_created_by" => getLoginId(),
        "complaint_status" => $complaint_status,
		"complaint_dispatched_store" =>$complaint_dispatched_store,
		"complaint_refund_status" => $complaint_refund_status
    ));
    //$complaint_id     = $Complaint->add($customer_type_id, $complaint_store_id, $complaint_customer_id, $customer_address_id, $complaint_ticket_number, $complaint_product_serial, $complaint_product_sku, $complaint_product_model, $complaint_is_backup, $complaint_product_password, $complaint_is_disk_provided, $complaint_product_is_under_waranty, $complaint_product_operating_system, $complaint_product_antivirus, $complaint_description, $complaint_problem_details, $complaint_product_hardware_not_working_string, $complaint_due_date, $complaint_priority, $complaint_sales_record_number, $complaint_order_number, $complaint_estimated_cost, $complaint_product_condition_at_receiving, $complaint_technician_id, $complaint_tax_id, $complaint_created_by, $complaint_status, $complaint_dispatched_store, $complaint_refund_status);
	//$complaint_id     = $Complaint->insert($repairValues);
    $CompProbRecord   = new ComplaintProblemRecord(0);
    $is_problem_fixed = 0;
    if (count($app_not_working_problem_mark) > 0) {
        foreach ($app_not_working_problem_mark as $problem_id)
            $CompProbRecord->add($complaint_id, $problem_id, $is_problem_fixed);
    }
    if ($is_new_customer) {
        new SMS($customer_phone, "Hi $customer_fname, Welcome to " . $app->siteName . ". Your account had been created with us successfully. Your username is \"$customer_email\" and Password is $customer_password");
        #==============================trig Email============= 
        $activation_link = $app->basePath("activation.php?r=cst&u=$customer_email&l=" . md5($customer_email . $complaint_customer_id) . "&i=" . md5($complaint_customer_id . $customer_email));
        $dataArray       = array(
            "customer_name" => $customer_fname,
            "customer_email" => $customer_email,
            "customer_password" => $customer_password,
            "login_page" => $app->basePath("customer-login.php"),
            "activation_link" => $activation_link
        );
        $email           = new Email("New Customer Account on " . $app->siteName);
        $email->to("$customer_email", $customer_fname)->template('customer_registration', $dataArray)->send();
    }
    new SMS($customer_phone, "Your Repair Request registered successfully. New Ticket ID #$complaint_ticket_number is generated. Your registered email id is $customer_email .");
    $dataArray = array(
        "customer_name" => $customer_fname,
        "complaint_ticket_number" => $complaint_ticket_number,
        "login_page" => $app->basePath("customer-login.php"),
        "complaint_link" => $app->basePath("viewcomplaint/$complaint_id")
    );
    //print_r($dataArray); 
    $email     = new Email("Repair Request Ticket #$complaint_ticket_number has been logged");
    $email->to($customer_email, $customer_fname)->addFile(DOC::CINV($complaint_id), $app->siteName . " Complaint - $complaint_ticket_number.pdf")->template('newcomplaint', $dataArray)->send();
    Activity::add("Added|^|$complaint_ticket_number", "C", $complaint_id);
    echo json_encode(array("200",  "success|New Repair Request Added Successfully",
        $complaint_id,
        $complaint_customer_id,
        $customer_address_id
    ));

?>