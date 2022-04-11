<?php
 
    $customer_fname = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_tax_number =  $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $wc_consignment_note_code = $wc_on_behalf_of_user = $wc_drop_off_vehicle = $wc_drop_off_driver = "";
    $wc_id = $wc_manager_id = $wc_driver_id = $wc_status_id = $wc_due_date = $wc_is_local_authority = $wc_mail_to_customer = $wc_mail_to_collector = $wc_collection_report = $wc_transfer_note = $wc_consignment_note = $wc_id = $customer_id = $customer_address_id = $wc_carrier_id = $wc_vehicle_id = $wc_is_drop_off = 0;
    $wci_item_id = $wci_qtiy_id = $wci_weit_id = $wci_chamount_id = $wci_chformat_id = $wci_itmdesc_id = $wci_pdamount_id = array();
	
	$wc_mail_hwcn_to_customer = $wc_mail_wcnn_to_customer = $wc_mail_docn_to_customer = 0;
	
    $data           = sanitizePostData($_POST);
    extract($data);
    $collection = new Collection($wc_id);
    $prevData   = $collection->load();
    $wc_code    = $prevData['wc_code'];
    if (!$prevData) {
        echo json_encode(array("300", "Error|Collection not Exists."
        ));
        exit();
    }
    $customer_email = strtolower($customer_email);
    if ($prevData['customer_email'] != $customer_email) {
		$Customer = new Customer();
        if ($Customer->isEmailExists($customer_email, $customer_id)) {
            echo json_encode(array("300", "Warning|Email Id \"$customer_email\" allready exist with another User."
            ));
            exit();
        }
    }
	if($wc_due_date !=""){
		$wc_due_date = date("Y-m-d H:i:s", strtotime($wc_due_date));
	}
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
    $wc_customer_id         = $customer_id;
    $wc_customer_address_id = $customer_address_id;
    $collection->update(array("wc_customer_id" => $wc_customer_id,
        "wc_customer_address_id" => $wc_customer_address_id,
        "wc_manager_id" => $wc_manager_id,
		"wc_driver_id" => $wc_driver_id,
        "wc_carrier_id" => $wc_carrier_id,
        "wc_vehicle_id" => $wc_vehicle_id,
        "wc_status_id" => $wc_status_id,
        "wc_due_date" => $wc_due_date,
		"wc_loading_time" => $wc_loading_time,
		"wc_help_member" => $wc_help_member,
        "wc_is_local_authority" => $wc_is_local_authority,
        "wc_mail_to_customer" => $wc_mail_to_customer,
        "wc_mail_to_collector" => $wc_mail_to_collector,
        "wc_collection_report" => $wc_collection_report,
        "wc_transfer_note" => $wc_transfer_note,
        "wc_consignment_note" => $wc_consignment_note,
        "wc_consignment_note_code" => $wc_consignment_note_code,
        "wc_on_behalf_of_user" => $wc_on_behalf_of_user,
		"wc_is_drop_off" => $wc_is_drop_off,
        "wc_drop_off_driver" => $wc_drop_off_driver,
        "wc_drop_off_vehicle" => $wc_drop_off_vehicle,
		
    ));
    
    new SMS($customer_phone, "Hi $customer_fname, Your Collection ".($wc_is_drop_off ? "(DropOff)" :"")." Request #$wc_code Updated on " . $app->siteName . " successfully. Collection due date is " . date("d M Y", strtotime($wc_due_date)));
    //Email to Uploader 
    /*$email = new Email($app->siteName . " : " . "Collection Item #$wc_code Updated");
    $email->send("You have successfully Updated Collection Request \"$wc_code\" on " . $app->siteName);*/
    //Email to Customer
	
	if ($wc_driver_id != 0) {
		$driver    	= new Employee($wc_driver_id);
		$driverUser = $driver->getDetails();
	}
	else
		$driverUser['user_name'] = "N/A";
	
	if ($wc_manager_id != 0) {
		$comMgr    = new Employee($wc_manager_id);
		$mger      = $comMgr->getDetails();
	}
	else
		$mger['user_name'] = "N/A";
	
	$dataArray = array(
		"customer_name" => $customer_fname,
		"customer_email" => $customer_email,
		"driver_name"	=> $driverUser['user_name'],
		"wc_loading_time" => $wc_loading_time,
		"wc_help_member" => $wc_help_member,
		"manager_name" => $mger['user_name'],
		"wc_code" => $wc_code,
		"wc_due_date" => date("D, d M Y h:i A", strtotime($wc_due_date)),
		"login_page" => $app->basePath("customer-login.php"),
		"collection_inventory_table" => $collection->getInventoryChartTable()
	);
	
	 
    if ($wc_mail_to_customer == 1) {
        
        $email     = new Email("Your Collection ".($wc_is_drop_off ? "(DropOff)" :"")." Request #$wc_code Updated");
        $email->to($customer_email, $customer_fname, $app->imagePath($Customer->get("customer_image")));
		
		
		if($wc_mail_hwcn_to_customer)
		$email->addFile(DOC::HWCN($wc_id), $app->siteName . " Collection - $wc_code.pdf");
		if($wc_mail_wcnn_to_customer)
		$email->addFile(DOC::WCNN($wc_id), $app->siteName . " Collection - $wc_code.pdf");
		if($wc_mail_docn_to_customer)
		$email->addFile(DOC::DOCN($wc_id), $app->siteName . " Collection - $wc_code.pdf");
		
		$email->template('collection_request_customer_new', $dataArray);
		$email->send();
    }
    //Email to Collector Manager 
    if ($wc_manager_id != 0 && $wc_mail_to_collector == 1) {
		$dataArray["login_page"] = $app->basePath("login.php");
        $comMgr    = new Employee($wc_manager_id);
        $mger      = $comMgr->getDetails();
        
        $email     = new Email("Collection ".($wc_is_drop_off ? "(DropOff)" :"")." Request #$wc_code Updated");
        $email->to($mger['user_email'], $mger['user_name'], $mger['user_image'])
		->addFile(DOC::HWCN($wc_id), $app->siteName . " Collection - $wc_code.pdf")
		->template('collection_request_collector_new', $dataArray)
		->send();
    }
    $hwc_link = DOC::HWCN($wc_id);
    $wcn_link = DOC::WCNN($wc_id);
    $doc_link = DOC::DOCN($wc_id);
    $cer_link = DOC::CERT($wc_id);
    Activity::add("updated|^|$wc_code", "W", $wc_id);
    echo json_encode(array("200",  "success|Collection ".($wc_is_drop_off ? "(DropOff)" :"")." Request updated Successfully",
        $wc_id,
        $wc_code,
        "hwc_link" => "javascript:newWindow('$hwc_link')",
        "wcn_link" => "javascript:newWindow('$wcn_link')",
        "doc_link" => "javascript:newWindow('$doc_link')",
        "cer_link" => "javascript:newWindow('$cer_link')"
    ));

?>