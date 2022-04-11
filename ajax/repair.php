<?php
function addcomplaint()
{
    global $app;
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
}
function updatecomplaint()
{
    global $app;
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
}

function insertcomplaintlog()
{
    $logtext          = "";
    $id               = 0;
    $complaint_format = '';
	$privacy = 0;
    $data             = sanitizePostData($_POST);
    extract($data);
    if ($logtext != "" && intval($id) != 0 && $complaint_format != '') {
        $complaint_id          = $id;
        $complaint             = new Complaint($complaint_id);
        $complaint_customer_id = $complaint->get("complaint_customer_id");
        $complaint_log_text    = $logtext;
        $complaint_log_type    = "TEXT";
        $complaint_log_status  = 1;
		$complaint_log_privacy = $privacy ? 0 : 1;
        $log                   = new ComplaintLog();
        $complaint_log_id      = $log->add($complaint_id, $complaint_format, $complaint_log_text, $complaint_log_type, $complaint_log_status, $complaint_log_privacy);
        if ($complaint_log_id) {
            $ComplaintLogViewer = new ComplaintLogViewer();
            $ComplaintLogViewer->add($complaint_id, $complaint_format);
            $logdata = $log->getLog($complaint_id, $complaint_format, $complaint_log_id);
            echo json_encode(array("200",  "success|Repair Request Log Added successfully",
                $logdata
            ));
        } else
            echo json_encode(array("300", "warning|Unable to Insert Repair Request Log. try again"
            ));
    } else
        echo json_encode(array("300", "warning|Please write Log Text"
        ));
}


function loadcomplaintchatmessage()
{
    $customer_id      = $complaint_id = 0;
    $complaint_format = '';
    $data             = sanitizePostData($_POST);
    extract($data);
    if ($customer_id != 0 && $complaint_id != 0 && $complaint_format != '') {
        $ComplaintLog                   = new ComplaintLog();
        $ComplaintLog->customer_id      = $customer_id;
        $ComplaintLog->complaint_id     = $complaint_id;
        $ComplaintLog->complaint_format = $complaint_format;
        $customer_log                   = $ComplaintLog->getCustomerLog();
        echo json_encode(array("200",  "success|Log History loaded successfully",
            $customer_log
        ));
        $ComplaintLogViewer = new ComplaintLogViewer();
        $ComplaintLogViewer->add($complaint_id, $complaint_format);
    } else
        echo json_encode(array("300", "warning|Unable to Fetch Log Text"
        ));
}

function loadcomplaintchatmessageautoload()
{
    $customer_id      = $complaint_id = $offset_id = 0;
    $complaint_format = '';
    $data             = sanitizePostData($_POST);
    extract($data);
    if ($customer_id != 0 && $complaint_id != 0 && $complaint_format != '') {
        $ComplaintLog                   = new ComplaintLog();
        $ComplaintLog->customer_id      = $customer_id;
        $ComplaintLog->complaint_id     = $complaint_id;
        $ComplaintLog->complaint_format = $complaint_format;
        $customer_log                   = $ComplaintLog->getCustomerLog($offset_id);
        echo json_encode(array("200",  "success|Log History loaded successfully",
            $customer_log
        ));
    } else
        echo json_encode(array("300", "warning|Please write Log Text"
        ));
}
function loadcustomerchatcomplaint()
{
    $customer_id = 0;
    $data        = sanitizePostData($_POST);
    extract($data);
    if ($customer_id != 0) {
        $FcmManager        = new FcmManager();
        $fcmConId          = $FcmManager->setFCMCustomer($customer_id);
        $Complaint         = new Complaint($customer_id);
        $CustomerComplaint = $Complaint->getCustomerComplaint($customer_id);
        echo json_encode(array("200",  "success|User's Repair Request Loaded Successfully",
            $CustomerComplaint,
            $fcmConId
        ));
    } else
        echo json_encode(array("300", "warning|No Customer Found"
        ));
}
function getcomplaintlogautoloadchatcount()
{
    $complaint = new Complaint(0);
    $logCount  = $complaint->getUnreadMessage();
    echo json_encode(array("200",  "success|Customer Chat Count executed",
        $logCount
    ));
}
function getcomplaintlogglobalcount()
{
    $complaint = new Complaint(0);
    $logCount  = $complaint->getGlobalUnreadMessage();
    echo json_encode(array("200",  "success|Customer Chat Global Count executed",
        $logCount
    ));
}

function addcomplaintcustomer()
{
    global $app;
    $customer_fname               = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $complaint_description = $complaint_problem_details = $complaint_product_serial = $complaint_product_model = $complaint_product_password = $complaint_product_operating_system = $complaint_product_antivirus = $complaint_due_date = $complaint_sales_record_number = $complaint_priority = $complaint_product_condition_at_receiving = "";
    $app_not_working_problem_mark = $complaint_product_hardware_not_working = array();
    $customer_type_id             = $complaint_estimated_cost = $complaint_is_backup = $complaint_is_disk_provided = $complaint_product_is_under_waranty = $complaint_technician_id = $complaint_store_id = 0;
    $complaint_status             = 7;
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
    $complaint_created_by                          = $customer_created_by = 1;
    $ComplaintTax                                  = new ComplaintTax();
    $complaint_tax_id                              = $ComplaintTax->getCurrentId();
    if (!$complaint_tax_id) {
        echo json_encode(array("300", "Warning|Repair Request Tax Information Not found Please Check Complaint Tax Information. Try again"
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
			"customer_company" => $customer_company,
			"customer_tax_number" => $customer_tax_number
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
    $complaint_id     = $Complaint->add($customer_type_id, $complaint_store_id, $complaint_customer_id, $customer_address_id, $complaint_ticket_number, $complaint_product_serial, $complaint_product_model, $complaint_is_backup, $complaint_product_password, $complaint_is_disk_provided, $complaint_product_is_under_waranty, $complaint_product_operating_system, $complaint_product_antivirus, $complaint_description, $complaint_problem_details, $complaint_product_hardware_not_working_string, $complaint_due_date, $complaint_priority, $complaint_sales_record_number, $complaint_estimated_cost, $complaint_product_condition_at_receiving, $complaint_technician_id, $complaint_tax_id, $complaint_created_by, $complaint_status);
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
    $email->to($customer_email, $customer_fname)->addFile(DOC::CINV($complaint_id), $app->siteName . " Repair Request - $complaint_ticket_number.pdf")->template('newcomplaint', $dataArray)->send();
    Activity::add("Added|^|$complaint_ticket_number", "C", $complaint_id);
    echo json_encode(array("200",  "success|New Repair Request Added Successfully",
        $complaint_id,
        $complaint_customer_id,
        $customer_address_id
    ));
}

function removecomplaintmedia(){
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$wopm = new ComplaintMedia($record_id);
		$wopm->update(array("repair_image_status"=>0));
		echo json_encode(array("200",  "success|Repair request image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No Repair request image found."));	
}
function complaintaction(){
	global $app;
	$order_action = '';
	$complaint_id = 0;
	$user_id = 0;
	$user_reset = 0;
	$complaint_is_outsourced = 0;
	$same_user_to_pack_order = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($complaint_id){
		$complaint = new Complaint($complaint_id);
		if($complaint->isExist())
		{
			$action_user_id = $user_id ?  $user_id : getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $complaint->getDetails();
			if($details['complaint_cancel_user'] == 0 || $details['complaint_status'] != 8 )
			{
				if($order_action == 'pick')
				{
					$updateArry = array(
					"complaint_picking_user"=> $action_user_id,
					"complaint_picking_time" => 'NOW()');
					if($same_user_to_pack_order == 1){
						$updateArry["complaint_packing_user"] = $action_user_id;
						$updateArry["complaint_packing_time"] = 'NOW()';
						$updateArry["complaint_is_outsourced"]= 0;
					}
					$complaint->update($updateArry);
					
					if($action_user_id != getLoginId())
					{
						$userTag = new UserTag();
						$userTag->insert(array(
								"tag_mentioner_id" 	=> getLoginId(), 
								"tag_user_id" 		=> $action_user_id, 
								"tag_module_code"	=> 'C', 
								"tag_module_id"		=> $complaint_id, 
								"tag_time"			=> 'NOW()', 
								"tag_is_readed"		=> 0, 
								"tag_type"			=> 'assigned picking by', 
								"tag_text"			=> $details['complaint_ticket_number'],
								"tag_log_id"		=> 0
							)
						);
					}
					
					Activity::add("marked <b>Picked</b> RMA Repair by <b>$actionUserData[user_name]</b>|^|{$details['complaint_ticket_number']}", "C", $complaint_id);
					if($same_user_to_pack_order == 1){
						if($action_user_id != getLoginId())
						{
							$userTag = new UserTag();
							$userTag->insert(array(
									"tag_mentioner_id" 	=> getLoginId(), 
									"tag_user_id" 		=> $action_user_id, 
									"tag_module_code"	=> 'C', 
									"tag_module_id"		=> $complaint_id, 
									"tag_time"			=> 'NOW()', 
									"tag_is_readed"		=> 0, 
									"tag_type"			=> 'assigned packing by', 
									"tag_text"			=> $details['complaint_ticket_number'],
									"tag_log_id"		=> 0
								)
							);
						}
						Activity::add("marked <b>Packed</b> RMA Repair by <b>$actionUserData[user_name]</b>", "C", $complaint_id);
					}
					echo json_encode(array("200",  "success|RMA Reapir marked as Picked".($same_user_to_pack_order ? " and Packed":"")));
				}
				elseif($order_action == 'pack')
				{
					if($details['complaint_picking_user'] != 0)
					{
						if($details['complaint_packing_user'] == 0 || $user_reset == md5($details['complaint_packing_user'])){
							$complaint->update(array(
							"complaint_packing_user"=> $action_user_id,
							"complaint_packing_time" => 'NOW()',
							"complaint_is_outsourced" => $complaint_is_outsourced
							));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'C', 
										"tag_module_id"		=> $complaint_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned packing by', 
										"tag_text"			=> $details['complaint_ticket_number'],
										"tag_log_id"		=> 0
									)
								);
							}
						
							Activity::add(($user_reset ? "Remarked":"marked")." <b>Packed</b> RMA Repair by <b>$actionUserData[user_name]</b>|^|{$details['complaint_ticket_number']}", "C", $complaint_id);
							echo json_encode(array("200",  "success|RMA Reapir ".($user_reset ? "Remarked":"marked")." as Packed"));
						}
						else{
							$employee = new Employee($details['complaint_packing_user']);
							echo json_encode(array("300",  "warning|RMA Reapir already Packed by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|RMA Reapir should be Picked first"));
					}
				}
				elseif($order_action == 'process')
				{
					if($details['complaint_packing_user'] != 0)
					{
						if($details['complaint_process_user'] == 0){
							$complaint->update(array(
							"complaint_process_user"=> $action_user_id,
							"complaint_process_time" => 'NOW()'));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'C', 
										"tag_module_id"		=> $complaint_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned processing by', 
										"tag_text"			=> $details['complaint_ticket_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add("marked <b>Processed</b> RMA Repair by <b>$actionUserData[user_name]</b>|^|{$details['complaint_ticket_number']}", "C", $complaint_id);
							echo json_encode(array("200",  "success|RMA Reapir marked as Process"));
						}
						else{
							$employee = new Employee($details['complaint_process_user']);
							echo json_encode(array("300",  "warning|RMA Reapir already Process by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|RMA Reapir should be Packed first"));
					}
				}
				elseif($order_action == 'cancel')
				{				
					$complaint->update(array(
					"complaint_cancel_user"=> $action_user_id,
					"complaint_cancel_time" => 'NOW()',
					"complaint_status" => 8
					));
					Activity::add("marked <b>Cancelled</b> RMA Repair by <b>$actionUserData[user_name]</b>|^|{$details['complaint_ticket_number']}", "C", $complaint_id);
					echo json_encode(array("200",  "success|RMA Reapir marked as Cancel"));
				}
				else
				{
					echo json_encode(array("300",  "warning|Invalid Action Found"));
				}				
			}
			else
			{				
				$employee = new Employee($details['complaint_cancel_user']);
				echo json_encode(array("300",  "warning|RMA Reapir already Cancel by ".$employee->get('user_fname')));
			}
		}
		else
		{
			echo json_encode(array("300",  "warning|RMA Reapir not exist"));
		}
	}
	else
	{
		echo json_encode(array("300",  "warning|RMA Reapir not found"));
	}
}

function downloadcomplaintinvoicelabel(){
	global $app;
	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('ComplaintLabels'));
		$complaintLabels = new ComplaintLabels();		
		if($labelData = $complaintLabels->loadByMd5($label_id)){
			$complaintLabels->updateDownloadCount();	
			echo json_encode(array("200", "success|Label Downloaded", $app->basePath($labelData['label_path']), $labelData['label_downloads']+1));
		}
		else{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Label not found"));
	}
}

function repairordercancellabel(){
	global $app;
	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('ComplaintLabels'));
		$ComplaintLabels = new ComplaintLabels();		
		if($labelData = $ComplaintLabels->loadByMd5($label_id)){
			$ComplaintLabels->update(array("label_status" => 0));
			Activity::add("Cancelled Label for", "C", $labelData['label_complaint_id']);
			unlinkFile($labelData['label_path']);
			echo json_encode(array("200", "success|Label Cancelled successfully"));
		}
		else{
			echo json_encode(array("300",  "warning|Label not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Label not found"));
	}
}
?>