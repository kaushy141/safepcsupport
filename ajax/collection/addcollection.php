<?php
    $customer_fname              = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $wc_consignment_note_code = $wc_on_behalf_of_user = $wc_loading_time = $wc_help_member = $wc_drop_off_vehicle = $wc_drop_off_driver = "";
    $wc_manager_id   =   $wc_driver_id         = $wc_status_id = $wc_due_date = $wc_is_local_authority = $wc_mail_to_customer = $wc_mail_to_collector = $wc_collection_report = $wc_transfer_note = $wc_consignment_note = $wc_id = $customer_id = $customer_address_id = $wc_carrier_id = $wc_vehicle_id = $wc_is_drop_off = 0;
    $wci_item_id = $wci_qtiy_id = $wci_weit_id = $wci_chamount_id = $wci_chformat_id = $wci_itmdesc_id = $wci_pdamount_id = array();
    $customer_status             = 1;
    $customer_password           = gePassword();
    $customer_is_mobile_verified = 0;
    $customer_is_email_verified  = 0;
    $customer_address_status     = 1;
	$is_back_date_collection = 0;
	$back_date_collection_date = "";
	$wc_mail_hwcn_to_customer = $wc_mail_wcnn_to_customer = $wc_mail_docn_to_customer = 0;
    $data                        = sanitizePostData($_POST);
    extract($data);
	
	if($is_back_date_collection == 1 && $back_date_collection_date == ""){
		echo json_encode(array("300", "danger|Collection Back date not found."));
		exit();
	}
	if($is_back_date_collection == 0)
	$back_date_collection_date = "";
	
	if($wc_due_date !=""){
		$wc_due_date = date("Y-m-d H:i:s", strtotime($wc_due_date));
	}
    $customer_email  = strtolower($customer_email);
    $wc_created_by   = $customer_created_by = $_SESSION['user_id'];
    $is_new_customer = false;
    $Customer        = new Customer(0);
    $wc_customer_id  = $Customer->isEmailExists($customer_email);
    if (!$wc_customer_id) {
        $customer_image  = DEFAULT_USER_IMAGE;
        $customer_code   = $Customer->getNewCode($customer_email, $customer_fname . $customer_lname);
        $wc_customer_id  = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
        $is_new_customer = true;
    } else {
        $Customer = new Customer($wc_customer_id);
        $Customer->update(array("customer_fname" => $customer_fname,
            "customer_lname" => $customer_lname,
            "customer_phone" => $customer_phone,
            "customer_type_id" => $customer_type_id,
			"customer_company" => $customer_company,
			"customer_tax_number" => $customer_tax_number
        ));
    }
    if (!$wc_customer_id) {
        echo json_encode(array("300", "Warning|Customer Details Not Full filled. Try again"
        ));
        exit();
    }
    $CustomerAddress        = new CustomerAddress(0);
    $wc_customer_address_id = $CustomerAddress->isCustomerAddressExists($wc_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
    if (!$wc_customer_address_id)
        $wc_customer_address_id = $CustomerAddress->add($wc_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status);
    $collection    = new Collection();
    $wc_code       = $collection->getWcCode($back_date_collection_date);
    $wc_ip_address = IP_ADDRESS;
    $wc_status     = 1;
    $wc_id         = $collection->add($wc_code, $wc_customer_id, $wc_customer_address_id, $wc_manager_id, $wc_driver_id, $wc_carrier_id, $wc_vehicle_id, $wc_status_id, $wc_due_date, $wc_loading_time, $wc_help_member,  $wc_is_local_authority, $wc_mail_to_customer, $wc_mail_to_collector, $wc_collection_report, $wc_transfer_note, $wc_consignment_note, $wc_consignment_note_code, $wc_on_behalf_of_user, $wc_created_by, $wc_ip_address, $wc_status, $wc_is_drop_off, $wc_drop_off_driver, $wc_drop_off_vehicle);
    if (!empty($wci_item_id)) {
        $WcrItem = new WcrItem();
        $i       = 0;
        foreach ($wci_item_id as $wcr_item_id) {
            $WcrItem->add($wc_id, $wcr_item_id, $wci_qtiy_id[$i], $wci_weit_id[$i], 1, $wci_chamount_id[$i], $wci_pdamount_id[$i], $wci_chformat_id[$i], $wci_itmdesc_id[$i]);
			if(WcItem::getItemSerializeType($wcr_item_id) == SERIALIZED)
			{
				for($j=0; $j < $wci_qtiy_id[$i]; $j++){
					$wc_process_asset_code = CollectionProcess::getProcessCode($wc_id, $wc_code);
					CollectionProcess::addProcess($wc_process_asset_code, $wc_id, $wcr_item_id, '', '', '', '', $wci_weit_id[$i], 1, 0, 0);
				}
			}
			else
			{
				$wc_process_asset_code = CollectionProcess::getProcessCode($wc_id, $wc_code);
				CollectionProcess::addProcess($wc_process_asset_code, $wc_id, $wcr_item_id, '', '', '', '', $wci_weit_id[$i], $wci_qtiy_id[$i], 0, 0);
			}
            $i++;
        }
    }
	
	if($is_back_date_collection == 1)
	{
		$collection->update(array("wc_created_date" => date("Y-m-d H:i:s", strtotime($back_date_collection_date)),
        "wc_status" => $wc_status
    ));
	
	}
    new SMS($customer_phone, "Hi $customer_fname, Your Collection Request #$wc_code added on " . $app->siteName . " successfully. Estimated collection due date is " . date("d M Y h:i A", strtotime($wc_due_date)));
    //Email to Uploader 
    /*$email = new Email($app->siteName . " : " . "Collection Item #$wc_code Added");
    $email->send("You have successfully added Collection Request \"$wc_code\" on " . $app->siteName);*/
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
        $email  = new Email("New Customer Account on " . $app->siteName);
        $email->to($customer_email, $customer_fname, $app->basePath($customer_image))->template('customer_registration', $dataArray)->send();
    }
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
		
		if($wc_mail_to_customer)
		{
			$email     = new Email("Your Collection ".($wc_is_drop_off ? "(DropOff)" :"")." Request #$wc_code Added");
			$email->to($customer_email, $customer_fname, $app->basePath($customer_image));
			
			if($wc_mail_hwcn_to_customer)
			$email->addFile(DOC::HWCN($wc_id), $app->siteName . " Collection - $wc_code.pdf");
			if($wc_mail_wccn_to_customer)
			$email->addFile(DOC::WCNN($wc_id), $app->siteName . " Collection - $wc_code.pdf");
			if($wc_mail_docn_to_customer)
			$email->addFile(DOC::DOCN($wc_id), $app->siteName . " Collection - $wc_code.pdf");
			
			$email->template('collection_request_customer_new', $dataArray);
			$email->send();
		}
    //Email to Collector Manager 
    if ($wc_manager_id != 0 && $wc_mail_to_collector == 1) {
        $dataArray = array(
            "user_fname" => $mger['user_fname'],
            "user_email" => $mger['user_email'],
            "wc_code" => $wc_code,
            "login_page" => $app->basePath("login.php"),
            "wc_due_date" => date("d M Y", strtotime($wc_due_date))
        );
        $email     = new Email("New Collection ".($wc_is_drop_off ? "(DropOff)" :"")." Request #$wc_code assigned");
        $email->to($mger['user_email'], $mger['user_fname'], $mger['user_image'])->addFile(DOC::HWCN($wc_id), $app->siteName . " Collection - $wc_code.pdf")->template('collection_request_collector_new', $dataArray)->send();
    }
    $hwc_link = DOC::HWCN($wc_id);
    $wcn_link = DOC::WCNN($wc_id);
    $doc_link = DOC::DOCN($wc_id);
    $cer_link = DOC::CERT($wc_id);
    Activity::add("added|^|$wc_code", "W", $wc_id);
    echo json_encode(array("200",  "success|Collection ".($wc_is_drop_off ? "(DropOff)" :"")." Request added Successfully",
        $wc_id,
        $wc_code,
        "hwc_link" => "javascript:newWindow('$hwc_link')",
        "wcn_link" => "javascript:newWindow('$wcn_link')",
        "doc_link" => "javascript:newWindow('$doc_link')",
        "cer_link" => "javascript:newWindow('$cer_link')"
    ));
?>