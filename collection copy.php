<?php
function updatecarrierstatus()
{
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Carrier      = new Carrier($idvalue);
        $Carrier_name = $Carrier->get('carrier_name');
        $status ? $Carrier->Activate() : $Carrier->Deactivate();
        Activity::add(status($status) . " Carrier <b>" . $Carrier_name . "</b> staus");
        echo json_encode(array("200",  "success|Carrier \"" . $Carrier_name . "\" " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Carrier " . status($status));
        $email->send("Employee \"" . $Carrier_name . "\" " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|User Status not found."
        ));
}
function addwcitem()
{
    global $app;
    $wci_name    = $wci_ewc_code = $wci_chemical_component = $wci_concentration = $wci_physical_form = $wci_hazard_codes = $wci_container_type = $wci_description = $wci_serialize_type = "";
    $wci_status  = 1;
    $wci_type_id = 0;
    $data        = sanitizePostData($_POST);
    extract($data);
    $WcItem = new WcItem();
    $wci_id = $WcItem->add($wci_type_id, $wci_name, $wci_ewc_code, $wci_chemical_component, $wci_concentration, $wci_physical_form, $wci_hazard_codes, $wci_container_type, $wci_description, $wci_serialize_type, $wci_status);
    Activity::add("added new Collection Item <b>$wci_name</b>");
    echo json_encode(array("200",  "success|Collection Item added Successfully",
        $wci_id
    ));
    $email = new Email($app->siteName . " : " . "Collection Item Added");
    $email->send("You have successfully added Collection Item \"$wci_name\" on " . $app->siteName);
}
function updatewcitem()
{
    global $app;
    $wci_name    = $wci_ewc_code = $wci_chemical_component = $wci_concentration = $wci_physical_form = $wci_hazard_codes = $wci_container_type = $wci_description = $wci_serialize_type = "";
    $wci_status  = 1;
    $wci_type_id = $wci_id = 0;
    $data        = sanitizePostData($_POST);
    extract($data);
    $WcItem = new WcItem($wci_id);
    $WcItem->update(array("wci_type_id" => $wci_type_id,
        "wci_name" => $wci_name,
        "wci_ewc_code" => $wci_ewc_code,
        "wci_chemical_component" => $wci_chemical_component,
        "wci_concentration" => $wci_concentration,
        "wci_physical_form" => $wci_physical_form,
        "wci_hazard_codes" => $wci_hazard_codes,
        "wci_container_type" => $wci_container_type,
        "wci_description" => $wci_description,
		"wci_serialize_type" => $wci_serialize_type,
        "wci_status" => $wci_status
    ));
    Activity::add("updated Collection Item <b>$wci_name</b>");
    echo json_encode(array("200",  "success|Collection Item Updated Successfully",
        $wci_id
    ));
    $email = new Email($app->siteName . " : " . "Collection Item Updated");
    $email->send("You have successfully Updated Collection Item \"$wci_name\" on " . $app->siteName);
}
function addcollection()
{
    global $app;
    $customer_fname              = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $wc_consignment_note_code = $wc_on_behalf_of_user = $wc_loading_time = $wc_help_member = $wc_drop_off_vehicle = $wc_drop_off_driver = "";
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
			"customer_company" => $customer_company
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
    Activity::add("added", "W", $wc_id);
    echo json_encode(array("200",  "success|Collection ".($wc_is_drop_off ? "(DropOff)" :"")." Request added Successfully",
        $wc_id,
        $wc_code,
        "hwc_link" => "javascript:newWindow('$hwc_link')",
        "wcn_link" => "javascript:newWindow('$wcn_link')",
        "doc_link" => "javascript:newWindow('$doc_link')",
        "cer_link" => "javascript:newWindow('$cer_link')"
    ));
}
function updatecollection()
{
    global $app;
    $customer_fname = $customer_lname = $customer_email = $customer_phone = $customer_company =  $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $wc_consignment_note_code = $wc_on_behalf_of_user = $wc_drop_off_vehicle = $wc_drop_off_driver = "";
    $wc_id = $wc_manager_id = $wc_driver_id = $wc_status_id = $wc_due_date = $wc_is_local_authority = $wc_mail_to_customer = $wc_mail_to_collector = $wc_collection_report = $wc_transfer_note = $wc_consignment_note = $wc_id = $customer_id = $customer_address_id = $wc_carrier_id = $wc_vehicle_id = $wc_is_drop_off =0;
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
		"customer_company" => $customer_company
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
    Activity::add("updated", "W", $wc_id);
    echo json_encode(array("200",  "success|Collection ".($wc_is_drop_off ? "(DropOff)" :"")." Request updated Successfully",
        $wc_id,
        $wc_code,
        "hwc_link" => "javascript:newWindow('$hwc_link')",
        "wcn_link" => "javascript:newWindow('$wcn_link')",
        "doc_link" => "javascript:newWindow('$doc_link')",
        "cer_link" => "javascript:newWindow('$cer_link')"
    ));
}
function sendhwcnto()
{
    global $app;
    $usertype = $wc_id = 0;
    $data     = sanitizePostData($_POST);
    extract($data);
    $collection     = new Collection($wc_id);
    $collectionData = $collection->load();
    $email          = new Email("New Hazard waste Collection Report #$collectionData[wc_code]");
    if ($usertype = 1)
        $email->to($collectionData['collection_manager_email'], $collectionData['collection_manager'], $collectionData['collection_manager_image']);
    elseif ($usertype = 2)
        $email->to($collectionData['carrier_email'], $collectionData['carrier_name'], $collectionData['carrier_logo_image']);
    $email->addFile(DOC::HWCN($wc_id), $app->siteName . " HWC Collection - $collectionData[wc_code].pdf")->template('send_document')->send();
    Activity::add("send Hazard waste Consignment note to Collection Manager ", "W", $wc_id);
    echo json_encode(array("200",  "success|Hazard waste Collection Report send Successfully"
    ));
}
function sendcertificate()
{
    global $app;
    $wc_id = 0;
	$usertype = 2;
    $data  = sanitizePostData($_POST);
    extract($data);
    $collection     = new Collection($wc_id);
    $collectionData = $collection->load();
    $email          = new Email("New Collection Certificate for #$collectionData[wc_code]");
    if ($usertype == 1)
        $email->to($collectionData['collection_manager_email'], $collectionData['collection_manager'], $collectionData['collection_manager_image']);
    elseif ($usertype == 2){
        $email->to($collectionData['customer_email'], $collectionData['customer_name'], $collectionData['customer_image']);
		$collection->update(array("wc_is_certificate_sended"=>1));
	}
    $email->addFile(DOC::CERT($wc_id), $app->siteName . " Collection Certificate - $collectionData[wc_code].pdf")->template('send_document', array(
        "message" => "We are happy to inform you that your Collection Certificate is ready against Collection Order number : #$collectionData[wc_code]"
    ))->send();
    Activity::add("send Hazard waste Consignment Certificate to Customer", "W", $wc_id);
    echo json_encode(array("200",  "success|Collection Certificate send to customer Successfully",
        $collectionData
    ));
}
function savesignature()
{
    global $app;
    $wc_id = 0;
    $data  = sanitizePostData($_POST);
    extract($data);
    $signature   = str_replace('data:image/png;base64,', '', $signature);
    $signature   = str_replace(' ', '+', $signature);
    $signature   = base64_decode($signature);
    $filePath    = 'upload/temp/' . time() . '.png';
    $fileBaseUrl = $app->sitePath($filePath);
    $fileUrl     = $app->basePath($filePath);
    if (file_put_contents($fileBaseUrl, $signature))
        echo json_encode(array("200",  "success|Signature Saved successfully",
            $filePath,
            $fileUrl
        ));
    else
        echo json_encode(array("300", "danger|Ooops..Signature could not saved on server"
        ));
}
function managecollection()
{
	//die;
    global $app;
    $wc_transferor_signature = $wc_completion_date = $wc_arrival_time = $wc_departure_time = $wc_member_of_staff_name = $wc_authority_member_of_staff = "";
    $wc_id                   = $wc_status_id = 0;
    $data_wci_item_id = $data_wci_qtiy_id = $data_wci_weit_id = $data_wci_chamount_id = $data_wci_chformat_id = $data_wci_itmdesc_id = $data_wci_pdamount_id = array();
	
	$wc_mail_hwcn_to_customer = $wc_mail_wcnn_to_customer = $wc_mail_docn_to_customer = 0;
	
    $data                    = sanitizePostData($_POST);
    extract($data);
    $collection = new Collection($wc_id);
    $prevData   = $collection->load();
    if (!$prevData) {
        echo json_encode(array("300", "Error|Complaint not Exists."
        ));
        exit();
    }
    $signaturePath = "";
    if ($wc_transferor_signature != "") {
        $signaturePath = "upload/collection/sign/".getDirectorySeparatorPath()."$prevData[wc_code]-" . time() . ".png";
        if (!move_file($app->sitePath($wc_transferor_signature), $app->sitePath($signaturePath)))
            $signaturePath = "";
    } else
        $signaturePath = $prevData['wc_transferor_signature'];
    $wc_code = $prevData['wc_code'];
    $collection->update(array("wc_status_id" => $wc_status_id,
        "wc_transferor_signature" => $signaturePath,
        "wc_completion_date" => $wc_completion_date,
        "wc_arrival_time" => $wc_arrival_time,
        "wc_departure_time" => $wc_departure_time,
        "wc_member_of_staff_name" => $wc_member_of_staff_name,
        "wc_authority_member_of_staff" => $wc_authority_member_of_staff
    ));
    if ($signaturePath != $prevData['wc_transferor_signature'] && $prevData['wc_transferor_signature'] != "")
        unlink($app->sitePath($prevData['wc_transferor_signature']));
    	
	
	
	$storedAssetsCode = CollectionProcess::getAllProcessCode($wc_id);
	$storedAssetsCodeArray = explode("," , $storedAssetsCode);
	$reUsedAssetCodeArray = array();
	
	
	
    if (!empty($data_wci_item_id)) {
        $i = 0;
		$WcrItem = new WcrItem();
		$AddedAllItemArray = $WcrItem->getAllItemArray($wc_id);
        foreach ($data_wci_item_id as $wcr_item_id) 
		{
			if($wcr_id = $WcrItem->isItemExist($wc_id, $wcr_item_id))
			{
				$WcrItemRow = new WcrItem($wcr_id);
				$WcrItemRow->update(array(
					"wcr_item_qty" => $data_wci_qtiy_id[$i], 
					"wcr_item_weight" => $data_wci_weit_id[$i], 
					"wcr_item_charge_amount" => $data_wci_chamount_id[$i], 
					"wcr_item_charge_amount_paid" => $data_wci_pdamount_id[$i], 
					"wcr_item_charge_format" => $data_wci_chformat_id[$i], 
					"wcr_item_description" => $data_wci_itmdesc_id[$i]
				));
			}
			else
			{
				$WcrItem->add($wc_id, $wcr_item_id, $data_wci_qtiy_id[$i], $data_wci_weit_id[$i], 1, $data_wci_chamount_id[$i], $data_wci_pdamount_id[$i], $data_wci_chformat_id[$i], $data_wci_itmdesc_id[$i]);
			}
			
			$wc_item_process_asset_code_array = array();
			if(isset($wc_item_process_asset_code[$wcr_item_id]) && $wc_item_process_asset_code[$wcr_item_id]!= "")
			$wc_item_process_asset_code_array = array_filter(explode(",", $wc_item_process_asset_code[$wcr_item_id]));
			if($data_wci_qtiy_id[$i] > count($wc_item_process_asset_code_array))
			{
				if(WcItem::getItemSerializeType($wcr_item_id) == SERIALIZED)
				{
					if(($data_wci_qtiy_id[$i]-count($wc_item_process_asset_code_array)))
					for($j=0; $j < ($data_wci_qtiy_id[$i]-count($wc_item_process_asset_code_array)); $j++){
						$wc_process_asset_code = CollectionProcess::getProcessCode($wc_id, $prevData['wc_code']);
						CollectionProcess::addProcess($wc_process_asset_code, $wc_id, $wcr_item_id, '', '', '', '', $data_wci_weit_id[$i], 1, 0, 0);
					}
				}
				elseif(count($wc_item_process_asset_code_array)==0)
				{
					$wc_process_asset_code = CollectionProcess::getProcessCode($wc_id, $prevData['wc_code']);
					CollectionProcess::addProcess($wc_process_asset_code, $wc_id, $wcr_item_id, '', '', '', '', $data_wci_weit_id[$i], $data_wci_qtiy_id[$i], 0, 0);
				}
			}
			$i++;			
        }
		
		$removedItemArray = array_diff($AddedAllItemArray, $data_wci_item_id);
		if(count($removedItemArray))
		{
			foreach($removedItemArray as $wcr_item_id)	
			{
				$WcrItem->removeItemFromCollection($wc_id, $wcr_item_id);
				CollectionProcess::removeItemFromCollectionProcess($wc_id, $wcr_item_id);
			}
		}
    }
	
	
	
    //Email to Collector Manager 
    $comMgr    = new Employee($prevData['wc_manager_id']);
    $mger      = $comMgr->getDetails();
    $dataArray = array(
        "user_fname" => $mger['user_fname'],
		"manager_name" => $mger['user_fname'],
        "user_email" => $mger['user_email'],
        "wc_code" => $prevData['wc_code'],
        "login_page" => $app->basePath("login.php"),
        "wc_due_date" => date("d M Y", strtotime($prevData['wc_due_date']))
    );
    $email     = new Email("Collection Request #$prevData[wc_code] Updated");
    $email->to($mger['user_email'], $mger['user_fname'], $mger['user_image']);
	if($wc_mail_hwcn_to_customer)
	$email->addFile(DOC::HWCN($wc_id), $app->siteName . " Collection hwcn - $prevData[wc_code].pdf");
	if($wc_mail_wcnn_to_customer)
	$email->addFile(DOC::WCNN($wc_id), $app->siteName . " Collection wcnn - $prevData[wc_code].pdf");
	if($wc_mail_docn_to_customer)
	$email->addFile(DOC::DOCN($wc_id), $app->siteName . " Collection docn - $prevData[wc_code].pdf");
	
	$email->template('collection_request_collector_new', $dataArray);
	$email->send();
	
    $hwc_link = DOC::HWCN($wc_id);
    $wcn_link = DOC::WCNN($wc_id);
    $doc_link = DOC::DOCN($wc_id);
    $cer_link = DOC::CERT($wc_id);
    Activity::add("updated", "W", $wc_id);
    echo json_encode(array("200",  "success|Collection Request Managed Successfully",
        $wc_id,
        $prevData['wc_code'],
        "hwc_link" => "javascript:newWindow('$hwc_link')",
        "wcn_link" => "javascript:newWindow('$wcn_link')",
        "doc_link" => "javascript:newWindow('$doc_link')",
        "cer_link" => "javascript:newWindow('$cer_link')"
    ));
}
function addcollectionmedia()
{
    global $app;
    $wc_id              = 0;
    $record_file_name   = $record_file_path = $record_remark = "";
    $record_status      = 1;
    $record_added_by    = $user_id = $_SESSION['user_id'];
    $emp                = new Employee($user_id);
    $record_media_array = array();
    $data               = sanitizePostData($_POST);
    extract($data);
    $collection = new Collection($wc_id);
    $wcData     = $collection->load();
    $wcRecord   = new WcRecord(0);
    if ($wcData && !empty($record_media_array)) {
        foreach ($record_media_array as $media) {
            if (file_exists($app->sitePath($media))) {
                $image_name       = pathinfo($app->sitePath($media));
                $extension        = strtolower($image_name['extension']);
                $record_file_path = "upload/collection/record/" . $wcData['wc_code'] . "_" . time() . ".$extension";
                if (rename($app->sitePath($media), $app->sitePath($record_file_path))) {
                    $wcRecord->add($wc_id, $record_file_name, $record_file_path, $record_added_by, $record_remark, $record_status);
                    unlink($app->sitePath($media));
                } else {
                    echo json_encode(array("300", "Error|Ooops... Some Uploaded media file \"$record_file_name\" does not exist or removed."
                    ));
                    exit;
                }
            }
        }
        Activity::add("added Media Snapshot", "W", $wc_id);
        echo json_encode(array("200",  "Success|Collection Record media file added successfully."
        ));
    } else
        echo json_encode(array("300", "Error|Collection Record not Exists or No Collection media file found."
        ));
}
function removecollectionmedia()
{
    global $app;
    $wc_id             = $record_id = 0;
    $record_status     = 1;
    $record_deleted_by = $_SESSION['user_id'];
    $data              = sanitizePostData($_POST);
    extract($data);
    $collection        = new Collection($wc_id);
    $wcData            = $collection->load();
    $wcRecordMedia     = new WcRecordMedia($record_id);
    $wcRecordMediaData = $wcRecordMedia->getDetails();
    if ($wcRecordMediaData) {
        $delFilePathLink = pathinfo($wcRecordMediaData['record_file_path'], PATHINFO_DIRNAME) . "/del/";
        $delFilePath     = $delFilePathLink . basename($wcRecordMediaData['record_file_path']);
        
        if (move_file($app->sitePath($wcRecordMediaData['record_file_path']), $app->sitePath($delFilePath))) {
            $wcRecordMedia->update(array("record_is_deleted" => 1,
                "record_deleted_by" => $record_deleted_by,
                "record_file_path" => $delFilePath
            ));
            unlink($app->sitePath($wcRecordMediaData['record_file_path']));
            Activity::add("removed Media Snapshot", "W", $wc_id);
            echo json_encode(array("200",  "success|Collection Record removed successfully."
            ));
        } else
            echo json_encode(array("300", "danger|Unable to delete Record. Please try again"
            ));
    } else
        echo json_encode(array("300", "error|Collection Record not Exists or No Collection media file found."
        ));
}
function loadoptions()
{
    $class_name = "";
    $value      = $id = 0;
    $data       = sanitizePostData($_POST);
    extract($data);
    if ($class_name != "" && $value != 0 && class_exists($class_name)) {
        $class       = new $class_name();
        $optionsHtml = $class->getOptions($id, $value);
        echo json_encode(array("200",  "success|Options Loaded successfully",
            $optionsHtml
        ));
    } else
        echo json_encode(array("300", "warning|Could not load Options"
        ));
}
function processcollection(){
	//die;
	global $app;
	$wci_item_make = $wci_item_model =$wci_item_name = $wci_item_qty = $wci_item_srno =$wci_item_weight = $wci_aged_box  = $wci_aged_box_data = $wci_core_id = array();
	
	$wc_mail_hwcn_to_customer = $wc_mail_wcnn_to_customer = $wc_mail_docn_to_customer = 0;
	$wc_id = 0;
	$data  = sanitizePostData($_POST);  
    extract($data);
	
	if(count($wci_aged_box))
	foreach($wci_aged_box as $agedbox){
		$wci_aged_box_data[$agedbox['wci_id']] = $agedbox['aged'];	
	}
	$assetCounter = 1;
	$collection = new Collection($wc_id);
	$colData = $collection->getDetails();
	if($colData && count($wci_core_id)>0)
	{
		$storedAssetsCode = CollectionProcess::getAllProcessCode($colData['wc_id']);
		$storedAssetsCodeArray = explode("," , $storedAssetsCode);
		//print_r($storedAssetsCodeArray);
		$reUsedAssetCodeArray = array();
		foreach($wci_core_id as $index=>$key)
		{
			$wc_process_wc_id			= $wc_id;
			$wc_process_item_id 		= $key;
			if(isset($wci_item_name[$key]))
			{	
				if(count($wci_item_name[$key]) < 9999)
				{
					$total_item_quantity = 0;
					for($i=0; $i<count($wci_item_name[$key]); $i++)
					{			
						$wc_process_item_make		= isset($wci_item_make[$key][$i])?$wci_item_make[$key][$i]:"";
						$wc_process_item_model		= isset($wci_item_model[$key][$i])?$wci_item_model[$key][$i]:"";
						$wc_process_item_name 		= isset($wci_item_name[$key][$i])?$wci_item_name[$key][$i]:"";					
						$wc_process_item_qty		= isset($wci_item_qty[$key][$i])?$wci_item_qty[$key][$i]:"";
						
						$wc_process_item_sr_no		= isset($wci_item_srno[$key][$i])?strtoupper($wci_item_srno[$key][$i]):"";
						
						$wc_process_item_weight		= isset($wci_item_weight[$key][$i])?$wci_item_weight[$key][$i]:"";
						$wc_process_item_inext_phase= isset($wci_aged_box_data[$key][$i])?$wci_aged_box_data[$key][$i]:"";
						
						$total_item_quantity += $wci_item_qty[$key][$i];
										
						if(isset($wc_item_process_asset_code[$key][$i]) && $wc_item_process_asset_code[$key][$i] != "")
						{
							$wc_process_asset_code = $wc_item_process_asset_code[$key][$i];
							CollectionProcess::updateProcess($wc_process_asset_code, $wc_process_wc_id, $wc_process_item_id, $wc_process_item_make, $wc_process_item_model, $wc_process_item_name, $wc_process_item_sr_no, $wc_process_item_weight, $wc_process_item_qty, $wc_process_item_inext_phase);
							array_push($reUsedAssetCodeArray, $wc_process_asset_code);
						}
						else
						{
							$wc_process_asset_code = CollectionProcess::getProcessCode($colData['wc_id'], $colData['wc_code']);	
							CollectionProcess::addProcess($wc_process_asset_code, $wc_process_wc_id, $wc_process_item_id, $wc_process_item_make, $wc_process_item_model, $wc_process_item_name, $wc_process_item_sr_no, $wc_process_item_weight, $wc_process_item_qty, $wc_process_item_inext_phase);
						}
					}
					
					$WcrItemRow = new WcrItem();
					$WcrItemRow->updateQuantity($wc_id, $key, $total_item_quantity);
				}
			}
		}
		//print_r($reUsedAssetCodeArray);
		$deletableAssetsCodes = array_diff($storedAssetsCodeArray, $reUsedAssetCodeArray);
		//print_r($deletableAssetsCodes);
		//die;
		if(count($deletableAssetsCodes) > 0)
		{
			foreach($deletableAssetsCodes as $assetsCode)
			{
				$itemData = CollectionProcess::getProcessByCode($assetsCode);
			 	CollectionProcess::deleteProcessByCode($assetsCode);
				WcItem::removeProcessItemValues($itemData['wc_process_id']);
			}	
		}
		
		if($wc_mail_hwcn_to_customer ==1 || $wc_mail_wcnn_to_customer == 1 || $wc_mail_docn_to_customer == 1)
		{
			$customer = new Customer($colData['wc_customer_id']);
			$costomerRecord = $customer->getDetails();
			new SMS($costomerRecord['customer_phone'], "Your Collection #$colData[wc_code] processed successfully. Your registered email id is $costomerRecord[customer_email].");
			$dataArray = array(
				"customer_name" => $costomerRecord['customer_fname'],
				"customer_email" => $costomerRecord['customer_email'],
				"wc_code" => $colData['wc_code'],
				"login_page" => $app->basePath("customer-login.php")
			);			
			
			$email     = new Email("Your Collection Request #$colData[wc_code] Processed");
			$email->to($costomerRecord['customer_email'], $costomerRecord['customer_fname'], $app->imagePath($costomerRecord["customer_image"]));				
			
			if($wc_mail_hwcn_to_customer)
			$email->addFile(DOC::HWCN($wc_id), $app->siteName . " Collection - $colData[wc_code].pdf");
			if($wc_mail_wcnn_to_customer)
			$email->addFile(DOC::WCNN($wc_id), $app->siteName . " Collection - $colData[wc_code].pdf");
			if($wc_mail_docn_to_customer)
			$email->addFile(DOC::DOCN($wc_id), $app->siteName . " Collection - $colData[wc_code].pdf");
			
			$email->template('updatecollection', $dataArray);
			$email->send();
			
			Activity::add("Processed", "W", $wc_id);					
		}	
		
			
		$hwc_link = DOC::HWCN($wc_id);
		$wcn_link = DOC::WCNN($wc_id);
		$doc_link = DOC::DOCN($wc_id);
		$cer_link = DOC::CERT($wc_id);	
	
		echo json_encode(array("200",  "success|Collection Request processed Successfully",
			$wc_id,
			$colData['wc_code'],
			"hwc_link" => "javascript:newWindow('$hwc_link')",
			"wcn_link" => "javascript:newWindow('$wcn_link')",
			"doc_link" => "javascript:newWindow('$doc_link')",
			"cer_link" => "javascript:newWindow('$cer_link')"
		));	
	}
	else{
		echo json_encode(array("300",  "danger|No Processing Items found"));
		exit();
	}	
}
function addpallet(){
	global $app;
	$pallet_name = $pallet_type = $pallet_location = "";
	$pallet_status = 1;
	$pallet_is_full = $pallet_capacity = 0;
	$pallet_created_by = $_SESSION['user_id'];
	$pallet_cert_customer = "";
	$pallet_cert_address = "";
	$pallet_cert_telephone = "";
	$pallet_cert_date = "";
	$pallet_serial_number = "";
	$pallet_tester = "";
	$data  = sanitizePostData($_POST);
    extract($data);
	$pallet = new Pallet();	
	$pallet_code = $pallet->getPalletCode();
	if($pallet_name !="" && $pallet_type!=""){
		$pallet_id = $pallet->insert( array(
										"pallet_code" 				=>	$pallet_code, 
										"pallet_name" 				=>	$pallet_name, 
										"pallet_capacity"			=>  $pallet_capacity,
										"pallet_location"			=>  $pallet_location,
										"pallet_type" 				=>	$pallet_type, 
										"pallet_status" 			=>	$pallet_status,
										"pallet_is_full"			=>	$pallet_is_full, 
										"pallet_created_by"			=>	$pallet_created_by,
										"pallet_created_date"		=>	"NOW()",
										"pallet_cert_customer"		=>	$pallet_cert_customer,
										"pallet_cert_address"		=>	$pallet_cert_address,
										"pallet_cert_telephone"		=>	$pallet_cert_telephone,
										"pallet_cert_date"			=>	$pallet_cert_date,
										"pallet_serial_number"		=>	$pallet_serial_number,
										"pallet_tester"				=>	$pallet_tester
										)										
									);
		echo json_encode(array("200",  "success|Apllet Added Successfully. Pallet Code is #$pallet_code",
            $pallet_id
        ));
	}
	else{
		echo json_encode(array("300",  "Warning|Apllet Couldn't added. Try again."));
	}
}
function updatepallet(){
	global $app;
	$pallet_name = $pallet_type = $pallet_location = "";
	$pallet_status = $pallet_is_full = $pallet_id = $pallet_capacity = 0;
	$data  = sanitizePostData($_POST);
    extract($data);	
	if($pallet_id !=0 && $pallet_name !="" && $pallet_type!=""){
		
		$pallet = new Pallet($pallet_id);
		$pallet->update( array(
								"pallet_name" 				=>  $pallet_name, 
								"pallet_location"			=>  $pallet_location,
								"pallet_capacity"			=>  $pallet_capacity,
								"pallet_type" 				=>  $pallet_type, 
								"pallet_status" 			=>  $pallet_status, 
								"pallet_is_full" 			=>  $pallet_is_full,
								"pallet_cert_customer"		=>	$pallet_cert_customer,
								"pallet_cert_address"		=>	$pallet_cert_address,
								"pallet_cert_telephone"		=>	$pallet_cert_telephone,
								"pallet_cert_date"			=>	$pallet_cert_date,
								"pallet_serial_number"		=>	$pallet_serial_number,
								"pallet_tester"				=>	$pallet_tester
								)
						);
		echo json_encode(array("200",  "success|Pallet Updated Successfully.",
            $pallet_id
        ));
	}
	else{
		echo json_encode(array("300",  "Warning|Pallet Couldn't Updated. Try again."));
	}
}

function loadcollectionitemdetail(){
	$wc_process_asset_code = '';
	$data  = sanitizePostData($_POST);
    extract($data);	
	if($result = CollectionProcess::getProcessByCode($wc_process_asset_code))
		echo json_encode(array("200",  "success|Items details loaded",
            $result
        ));
	else
		echo json_encode(array("300",  "Warning|No Collection item found."));
}

function managepalletitems(){
	$listprocesscode = $processcode = array();
	$pallet_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$pallet = new Pallet($pallet_id);	
	if($pallet->isExist())
	{
		if(count($processcode)>0)
		{
			$PalletItems = new PalletItems($pallet_id);
			$storedPalletItems = $PalletItems->getStoredItems();
			$storedPalletItems = $storedPalletItems == NULL ? array() : $storedPalletItems;
			
			foreach($processcode as $wc_process_asset_code)
			{
				$itemData = json_decode(stripslashes($listprocesscode[$wc_process_asset_code]), true);
				$collectionProcess = new CollectionProcess($wc_process_asset_code);
				if($collectionProcess->isExist()){					
					$allowedStoredColumns = array(
							"wc_process_item_make",
							"wc_process_item_model",
							"wc_process_item_name",
							"wc_process_item_sr_no",
							"wc_process_item_location",
							"wc_process_item_voltage",
							"wc_process_item_rg_mi",
							"wc_process_item_plug_and_flex_body",
							"wc_process_item_gass_type",
							"wc_process_item_microwave_leakag_test",
							"wc_process_item_weight",
							"wc_process_item_qty",
							"wc_process_item_fuse_rating",
							"wc_process_item_insulation_ohms",
							"wc_process_item_fridge_temp",
							"wc_process_item_freezer_temp",
							"wc_process_item_clean_item",
							"wc_process_item_damage_status"
							);
					$itemData = array_intersect_key( $itemData, array_flip( $allowedStoredColumns ));					
					$collectionProcess->update($itemData);				
				}
				else
				{
					echo json_encode(array("300",  "error|Oooops.. Process beak. <br/>Item <b>$wc_process_asset_code</b> does not exit.<br/> Pallet items not updated. try again"));	
				}
			}
			
			$newItems = array_diff($processcode, $storedPalletItems);
			$removeItems = array_diff($storedPalletItems, $processcode);
			
			if(!empty($newItems))
			{
				foreach($newItems as $wc_process_asset_code)
				{
					$wpi_label_number = $PalletItems->getPalletItemLabel();
					$wpi_code_number = $PalletItems->getPalletItemNumber($wpi_label_number);					
					
					$PalletItems->add($wc_process_asset_code, $pallet_id, $wpi_code_number, $wpi_label_number, $wpi_item_order);
				}
			}
			if(!empty($removeItems))
			{
				foreach($removeItems as $wc_process_asset_code)
				{
					$PalletItems->removeByCodeItemCode($wc_process_asset_code);
				}
				
			}
			$wpi_item_order = 1;
			foreach($processcode as $wc_process_asset_code)
			{
				$PalletItems->updateItemOrderByCode($wc_process_asset_code, $wpi_item_order++);
			}
			echo json_encode(array("200",  "success|Pallet's Item updated.<br/>".count($newItems). " items added on Pallet<br/>".count($removeItems)." items removed from Pallet"));
		}
		else
			echo json_encode(array("300",  "warning|No Collection item found."));
	}
	else
		echo json_encode(array("300",  "warning|No Pallet found."));
}

function resetpalletitemsserial(){
	$pallet_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$PalletItems = new PalletItems($pallet_id);
	$PalletItems->resetSerial();
	echo json_encode(array("200",  "success|Pallet's Item order reset succesfully", json_encode($PalletItems->getItemsRecords())));
}

function updatewopaymentstatus(){
	$web_order_id = $web_order_is_paid = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$websiteOrder = new WebsiteOrder($web_order_id);
	if($websiteOrder->isExist() && in_array($web_order_is_paid, array("Yes","No")))
	{
		$websiteOrder->update(array("web_order_is_paid" => $web_order_is_paid));		
		echo json_encode(array("200",  "success|Weborder payment status updated successfully", $web_order_is_paid));
	}
	else
		echo json_encode(array("300",  "warning|No weborder found.", $web_order_is_paid));	
}

function updateweborder(){
	$web_order_id = $web_order_status = $web_order_is_picked = $web_order_is_packed = $web_order_is_processed =$web_order_picking_user = $web_order_packing_user = $web_order_process_user = 0;
	$web_order_picking_time =  $web_order_packing_time = $web_order_process_time = "";
	
	$wo_process_code = array();
	$data  = sanitizePostData($_POST);
	extract($data);	
	
	
	if(!$web_order_is_picked){
		$web_order_picking_user = 0;
		$web_order_picking_time = 'NULL';
	}
	if(!$web_order_is_packed){
		$web_order_packing_user = 0;
		$web_order_packing_time = 'NULL';
	}
	if(!$web_order_is_processed){
		$web_order_process_user = 0;
		$web_order_process_time = 'NULL';
	}
	
	//`web_order_picking_user`, `web_order_picking_time`, `web_order_packing_user`, `web_order_packing_time`, `web_order_process_user`, `web_order_process_time`
	$websiteOrder = new WebsiteOrder($web_order_id);
	if($websiteOrder->isExist())
	{
		$websiteOrder->update(array(
									"web_order_picking_user" => $web_order_picking_user,
									"web_order_picking_time" => $web_order_picking_time,
									"web_order_packing_user" => $web_order_packing_user,
									"web_order_packing_time" => $web_order_packing_time,
									"web_order_process_user" => $web_order_process_user,
									"web_order_process_time" => $web_order_process_time,
									"web_order_status" 		 => $web_order_status
								)
							 );
		if(count($wo_process_code) > 0)
		{
			foreach($wo_process_code as $wo_id => $wo_process_code_value)
			{
				$wo_product_srno_code = $wo_product_srno[$wo_id];
				$websiteOrderProduct = new WebsiteOrderProduct($wo_id);
				$websiteOrderProduct->update(array("wo_process_code"=>$wo_process_code_value, "wo_product_srno"=>$wo_product_srno_code));
			}
		}
		echo json_encode(array("200",  "success|Weborder updated successfully"));
	}
	else
		echo json_encode(array("300",  "warning|No weborder found."));	
}

function removewebordermedia(){
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$wopm = new WebsiteOrderProductMedia($record_id);
		$wopm->update(array("wpoi_status"=>0));
		echo json_encode(array("200",  "success|Weborder product image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No weborder image found."));	
}

function removecollectionitemmedia(){
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$wopm = new CollectionItemMedia($record_id);
		$recordData  = $wopm->getDetails();
		$wopm->remove();
		if(isset($recordData['image_path']) && $recordData['image_path'] != '')
			unlinkFile($recordData['image_path']);
		echo json_encode(array("200",  "success|Item image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No Item image found."));
}

function savecollectionprocessitem(){
	$wc_process_asset_code = '';
	$apply_on_all_items_of_this_collection = $wc_process_item_pallet = 0;
	$chkattribute = array();
	$wc_process_item_make = $wc_process_item_model = $wc_process_item_name = $wc_process_item_sr_no = $wc_process_item_weight = '';
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($wc_process_asset_code)
	{
		$collectionProcess = new CollectionProcess($wc_process_asset_code);
		$wcdata = $collectionProcess->getDetails();
		if($wcdata)
		{
			$collectionProcess->update(compact('wc_process_item_make', 'wc_process_item_model', 'wc_process_item_name', 'wc_process_item_sr_no', 'wc_process_item_weight'));
			$pallet = new Pallet($wc_process_item_pallet);
			if($pallet->isExist() && !$pallet->isFull()){
				$PalletItems = new PalletItems($wc_process_item_pallet);
				$wpi_label_number = $PalletItems->getPalletItemLabel();
				$wpi_code_number = $PalletItems->getPalletItemNumber($wpi_label_number);	
				$PalletItems->saveItem($wc_process_asset_code, $wc_process_item_pallet, $wpi_code_number, $wpi_label_number, $wpi_item_order);
			}
			else{
				echo json_encode(array("300",  "warning|Pallet is Full."));	
				die;
			}
			$values_wc_process_id = $wcdata['wc_process_id'];
			$wcItem = new WcItem($wcdata['wc_process_item_id']);
			$attributes = $wcItem->getItemAttributes();
			if($attributes){
				foreach($attributes as $item)
				{
					$values_realtion_id  = $item['attribute_relation_id'];
					$values_item_id 	 = $item['attribute_relation_item_id'];
					$values_attribute_id = $item['attribute_relation_attribute_id'];
					$values_wc_id 		 = $wcdata['wc_process_wc_id'];
					$values_data = isset($attribute[$values_realtion_id]) ? $attribute[$values_realtion_id] : "No";
					if(!$wcItem->isExistItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id))
						$wcItem->addItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
					else
						$wcItem->updateItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
				}				
								
				if($apply_on_all_items_of_this_collection && count($chkattribute)){
					$collectionProcess = new CollectionProcess();
					$getSimilarItems = $collectionProcess->getSimilarItemFromCollection($wcdata['wc_process_wc_id'], $wcdata['wc_process_item_id']);
					
					$process_fields = array_intersect($chkattribute, array('wc_process_item_make', 'wc_process_item_model', 'wc_process_item_name', 'wc_process_item_weight'));
					
					if(count($getSimilarItems)){
						foreach($getSimilarItems as $_process){
							if($_process['wc_process_asset_code'] != $wc_process_asset_code)
							{
								$_collectionProcess = new CollectionProcess($_process['wc_process_asset_code']);
								$values_wc_process_id = $_process['wc_process_id'];
								if(count($process_fields)){
									$_collectionProcess->update(compact($process_fields));
								}
								$wcItem = new WcItem($wcdata['wc_process_item_id']);
								$attributes = $wcItem->getItemAttributes();
								foreach($attributes as $item)
								{
									if(in_array($item['attribute_relation_attribute_id'], $chkattribute))
									{
										$values_realtion_id  = $item['attribute_relation_id'];
										$values_item_id 	 = $item['attribute_relation_item_id'];
										$values_attribute_id = $item['attribute_relation_attribute_id'];
										$values_wc_id 		 = $wcdata['wc_process_wc_id'];
										$values_data = isset($attribute[$values_realtion_id]) ? $attribute[$values_realtion_id] : "No";
										if(!$wcItem->isExistItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id))
											$wcItem->addItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
										else
											$wcItem->updateItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
									}
								}
							}
						}
					}
				}				
				echo json_encode(array("200",  "success|Item Details Saved"));
			}
			else
				echo json_encode(array("200",  "warning|Item attributes not found"));
		}
		else
			echo json_encode(array("300",  "warning|Item code not found."));	
	}
	else
		echo json_encode(array("300",  "warning|Item code not exist."));	
}

function saveattributeoption(){
	$attribute_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($attribute_id){
			$attribute = new Attribute($attribute_id);
			if($attribute->isExist())
			{
				$wcitem = new WcItem();
				$savedOptions = $wcitem->getAttributesOptions($attribute_id);
				$savedOptions = array_values($savedOptions);
				$newOptions = array_diff($options, $savedOptions);
				$delOptions = array_diff($savedOptions, $options);
				if(count($newOptions)){
					foreach($newOptions as $values_data)
					$wcitem->addAttributeOption($attribute_id, $values_data);
				}
				if(count($delOptions)){
					foreach($delOptions as $values_data)
					$wcitem->removeAttributeOption($attribute_id, $values_data);
				}
				echo json_encode(array("200",  "success|Attribute Option Saved", $record));			
			}
			else
				echo json_encode(array("300",  "warning|No Attribute found."));
		}
		else
			echo json_encode(array("300",  "warning|Invalid Attribute."));	
}

function getitemfilterattributes(){
	$wci_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($wci_id){
		$wcItem = new WcItem($wci_id);
		if($record = $wcItem->getFilterAttributes())
			echo json_encode(array("200",  "success|Filteration loaded", $record));
		else
			echo json_encode(array("300",  "warning|No Filteration found"));
	}
	else
		echo json_encode(array("300",  "warning|No Item found."));		
	
}

function fetchprocessitemmodeldetail(){
	$wc_process_item_id = 0;
	$wc_process_item_model = "";
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($wc_process_item_id && $wc_process_item_model != ""){
		$cp = new CollectionProcess($wci_id);
		if($record = $cp->getItemModelCopy($wc_process_item_id, $wc_process_item_model))
			echo json_encode(array("200",  "success|Details loaded", $record));
		else
			echo json_encode(array("300",  "warning|No Details found"));
	}
	else
		echo json_encode(array("300",  "warning|No Item found."));	
}

function updateattributestatus(){
	global $app;
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $attribute = new Attribute($idvalue);
        $status ? $attribute->Activate() : $attribute->Deactivate();
        Activity::add(status($status) . " Attribute <b>" . $attribute->get('attribute_name') . "</b> status");
        echo json_encode(array("200",  "success|Attribute " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Attribute " . status($status));
        $email->send("Attribute <b>\"" . $attribute->get('attribute_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Attribute Status not found."
        ));
}

function getitemattributes(){
	global $app;
    $attribute_relation_item_id  = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($attribute_relation_item_id != 0) {
		$attribute = new Attribute(0);        
		echo json_encode(array("200",  "success|Item Attribute Loaded Successfully", $attribute->getItemAttributesList($attribute_relation_item_id)));
    } 
	else
        echo json_encode(array("300", "warning|Attribute Status not found."));
}

function saveitemattributes(){
	global $app;
    $attribute_relation_item_id  = 0;
	$attributes = array();
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($attribute_relation_item_id != 0) 
	{
		$wcItem = new WcItem($attribute_relation_item_id); 
		if($wcItem->isExist())
		{
			$attribute = new Attribute();
			$prevAttribiutes = $attribute->getItemAttributesList($attribute_relation_item_id);
			$newAttributes = array_diff($attributes, $prevAttribiutes);
			$removeAttributes = array_diff($prevAttribiutes, $attributes);			
			if(count($newAttributes)){
				foreach($newAttributes as $attribute_relation_attribute_id)
				{
					if($attribute->isItemAttributeRelationExist($attribute_relation_item_id, $attribute_relation_attribute_id))
						$attribute->updateItemAttributeRelation($attribute_relation_item_id, $attribute_relation_attribute_id, 1);
					else
						$attribute->addItemAttribute($attribute_relation_item_id, $attribute_relation_attribute_id);
				}
			}
			if(count($removeAttributes)){
				foreach($removeAttributes as $attribute_relation_attribute_id){
					$attribute->updateItemAttributeRelation($attribute_relation_item_id, $attribute_relation_attribute_id, 0);
				}
			}
			Activity::add("Updated Item's attributes");
			echo json_encode(array("200",  "success|Item Attribute saved Successfully", ));
		} 
		else
			echo json_encode(array("300", "warning|No Attribute found."));
	}
	else
        echo json_encode(array("300", "warning|Invalid Attribute found."));
}

function saveattribute(){
	$attribute_id	= 0;
	$attribute_name	= "";
	$attribute_element_type = 'atext';
	$attribute_is_filtrer = 0;
	$attribute_status = 0;	
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($attribute_name != "" && $attribute_element_type != ""){
		$attribute = new Attribute($attribute_id);
		if(!$attribute->isAvailable($attribute_name) && $attribute_id == 0){
			$attribute_id = $attribute->insert(compact('attribute_name', 'attribute_element_type', 'attribute_is_filtrer', 'attribute_status'));
			echo json_encode(array("200",  "success|Atribute Saved", $attribute_id));
		}
		elseif(!$attribute->isAvailableExceptThis($attribute_name, $attribute_id)){
			$attribute->update(compact('attribute_name', 'attribute_is_filtrer', 'attribute_status'));
			echo json_encode(array("200",  "success|Atribute Updated", $attribute_id));
		}
		else
			echo json_encode(array("300",  "warning|Attribute name already exist."));	
	}
	else
		echo json_encode(array("300",  "warning|Attribute name and type not found."));	
}
?>