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
}
function updatecollection()
{
    global $app;
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
    Activity::add("send Hazard waste Consignment note to Collection Manager|^|$collectionData[wc_code]", "W", $wc_id);
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
    Activity::add("send Hazard waste Consignment Certificate to Customer|^|$collectionData[wc_code]", "W", $wc_id);
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
	if(is_array($storedAssetsCode))
	$storedAssetsCodeArray = $storedAssetsCode;
	else
	$storedAssetsCodeArray = $storedAssetsCode ? explode("," , $storedAssetsCode) : array();
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
    Activity::add("updated|^|$prevData[wc_code]", "W", $wc_id);
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
        Activity::add("added Media Snapshot|^|{$wcData['wc_code']}", "W", $wc_id);
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
            Activity::add("removed Media Snapshot|^|{$wcData['wc_code']}", "W", $wc_id);
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
	$wci_item_make = $wci_item_model =$wci_item_name = $wci_item_qty = $wci_item_srno =$wci_item_weight = $wci_aged_box  = $wci_aged_box_data = $wci_core_id = $wc_item_process_asset_code = $wci_core_id = array();
	
	$wc_mail_hwcn_to_customer = $wc_mail_wcnn_to_customer = $wc_mail_docn_to_customer = 0;
	$wc_id = 0;
	//$_POST = getRealPOST();
	$data  = sanitizePostData($_POST);  
    extract($data);
	
	$wci_item_make = $_POST['wci_item_make'];
	$wci_item_model = $_POST['wci_item_model'];
	$wci_item_name = $_POST['wci_item_name'];
	$wci_item_qty = $_POST['wci_item_qty'];
	$wci_item_srno = $_POST['wci_item_srno'];
	$wci_item_weight = $_POST['wci_item_weight'];
	$wci_aged_box  = $_POST['wci_aged_box'];
	$wci_aged_box_data = $_POST['wci_aged_box_data'];
	$wci_core_id = $_POST['wci_core_id'];
	$wc_mail_hwcn_to_customer = $_POST['wc_mail_hwcn_to_customer'];
	$wc_mail_wcnn_to_customer = $_POST['wc_mail_wcnn_to_customer'];
	$wc_mail_docn_to_customer = $_POST['wc_mail_docn_to_customer'];
	$wc_id = $_POST['wc_id'];
	
	if(count($wci_aged_box))
	foreach($wci_aged_box as $agedbox){
		$wci_aged_box_data[$agedbox['wci_id']] = $agedbox['aged'];	
	}
	$assetCounter = 1;
	//print_r($_POST);die;
	$collection = new Collection($wc_id);
	$colData = $collection->getDetails();
	if($colData && count($wci_core_id)>0)
	{
		$storedAssetsCode = CollectionProcess::getAllProcessCode($colData['wc_id']);
		if(!is_array($storedAssetsCode))
			$storedAssetsCodeArray = explode("," , $storedAssetsCode);
		else
			$storedAssetsCodeArray = $storedAssetsCode;
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
					for($i=0; $i < count($wci_item_name[$key]); $i++)
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
			
			Activity::add("Processed|^|{$colData['wc_code']}", "W", $wc_id);					
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
function processcollectionv2(){
	$wc_process_asset_code = $wci_item_name = $wci_item_make = $wci_item_model = $wci_item_srno = array();
	$data  = sanitizePostData($_POST);
    extract($data);
	if(count($wc_process_asset_code))
	{
		foreach($wc_process_asset_code as $p_code)
		{
			$wc_process_item_make = isset($wci_item_make[$p_code]) ? $wci_item_make[$p_code] : "";
			$wc_process_item_name = isset($wci_item_name[$p_code]) ? $wci_item_name[$p_code] : "";
			$wc_process_item_model = isset($wci_item_model[$p_code]) ? $wci_item_model[$p_code] : "";
			$wc_process_item_sr_no = isset($wci_item_srno[$p_code]) ? $wci_item_srno[$p_code] : "";
			$cp = new CollectionProcess($p_code);
			$cp->update(
						array(
							"wc_process_item_make"=>$wc_process_item_make,
							"wc_process_item_name"=>$wc_process_item_name,
							"wc_process_item_model"=>$wc_process_item_model,
							"wc_process_item_sr_no"=>$wc_process_item_sr_no
							)
						);
		}
		echo json_encode(array("200",  "success|Collection Request processed Successfully"));
		exit();	
	}
	else
	{
		echo json_encode(array("300",  "danger|No Processing Code found"));
		exit();	
	}
}
function processcollectionitem(){
	global $app;
	$wc_id = $item_id = 0;
	$data  = sanitizePostData($_POST);
    extract($data);
	if($wc_id && $item_id){
		$collectionItems = CollectionProcess::getAllProcessCodeOfItem($wc_id, $item_id);
		if(!$collectionItems)
		{
			$collectionItem = CollectionProcess::getColletionItemDetails($wc_id, $item_id);
			$collection = new Collection($wc_id);
			$colData = $collection->getDetails();
			$item = new WcItem($item_id);
			$itemData = $item->getDetails();	
			$wc_process_wc_id 	= $wc_id;
			$wc_process_item_id = $item_id;
			$wc_process_item_make = '';
			$wc_process_item_model = '';
			$wc_process_item_name = $itemData['wci_name'];
			$wc_process_item_make = '';
			$wc_process_item_sr_no = '';
			$wc_process_item_weight = '';
			$wc_process_item_qty = $itemData['wci_serialize_type'] == SERIALIZED ? 1 : 0;
			$wc_process_item_inext_phase = 0;
			$loopQty = $itemData['wci_serialize_type'] == SERIALIZED ? $collectionItem['wcr_item_qty'] : 1;
			for($i=0; $i<$loopQty; $i++)
			{
				$wc_process_asset_code = CollectionProcess::getProcessCode($colData['wc_id'], $colData['wc_code']);	
				CollectionProcess::addProcess($wc_process_asset_code, $wc_process_wc_id, $wc_process_item_id, $wc_process_item_make, $wc_process_item_model, $wc_process_item_name, $wc_process_item_sr_no, $wc_process_item_weight, $wc_process_item_qty, $wc_process_item_inext_phase);
			}
		}
		$processList = CollectionProcess::getProcess($wc_id, $item_id);
		$processRows = '';
		foreach($processList as $item){
			$processRows .= CollectionProcess::getProcessItemTableRow($item);
		}
		echo json_encode(array("200",  "success|Collection Item Request Processed.", $processRows));
		exit();
	}
	else{
		echo json_encode(array("300",  "danger|No Processing Items found"));
		exit();
	}	
}

function addnewprocessitem(){
	global $app;
	$wc_id = $item_id = 0;
	$data  = sanitizePostData($_POST);
    extract($data);
	if($wc_id && $item_id)
	{
		$collectionItem = CollectionProcess::getColletionItemDetails($wc_id, $item_id);
		$collection = new Collection($wc_id);
		$colData = $collection->getDetails();
		$item = new WcItem($item_id);
		$itemData = $item->getDetails();	
		$wc_process_wc_id 	= $wc_id;
		$wc_process_item_id = $item_id;
		$wc_process_item_make = '';
		$wc_process_item_model = '';
		$wc_process_item_name = $itemData['wci_name'];
		$wc_process_item_make = '';
		$wc_process_item_sr_no = '';
		$wc_process_item_weight = '';
		$wc_process_item_qty = $itemData['wci_serialize_type'] == SERIALIZED ? 1 : 0;
		$wc_process_item_inext_phase = 0;
		
		$wc_process_asset_code = CollectionProcess::getProcessCode($colData['wc_id'], $colData['wc_code']);	
		CollectionProcess::addProcess($wc_process_asset_code, $wc_process_wc_id, $wc_process_item_id, $wc_process_item_make, $wc_process_item_model, $wc_process_item_name, $wc_process_item_sr_no, $wc_process_item_weight, $wc_process_item_qty, $wc_process_item_inext_phase);		
		$WcrItemRow = new WcrItem();
		$WcrItemRow->updateQuantity($wc_id, $item_id, $collectionItem['wcr_item_qty']+1);
		$processList = CollectionProcess::getProcess($wc_id, $item_id, $wc_process_asset_code);
		$processRows = '';
		foreach($processList as $item){
			$processRows .= CollectionProcess::getProcessItemTableRow($item);
		}
		echo json_encode(array("200",  "success|Collection Item Product added.", $processRows));
		exit();
	}
	else{
		echo json_encode(array("300",  "danger|No Processing Items found"));
		exit();
	}
}

function removecollectionprocesscode(){
	global $app;
	$wc_id = $item_id = $process_code = 0;
	$data  = sanitizePostData($_POST);
    extract($data);
	if($wc_id && $item_id && $process_code)
	{
		$collectionItem = CollectionProcess::getColletionItemDetails($wc_id, $item_id);
		$cp = new CollectionProcess($process_code);
		if($cp->isExist())
		{
			$collItemData = $cp->getDetails();
			$cp->remove();
			$WcrItemRow = new WcrItem();
			$WcrItemRow->updateQuantity($wc_id, $item_id, $collectionItem['wcr_item_qty']-1);
			$cpi = new CollectionProcessItem();
			$cpi->removeCollectionItemAttributeValues($collItemData['wc_process_id']);
			echo json_encode(array("200",  "success|Collection Item Product removed."));
			exit();
		}
		else
		{
			echo json_encode(array("300",  "danger|Collection item not found"));
			exit();
		}
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
							"wc_process_item_sku",
							"wc_process_item_stock",
							"wc_process_item_sr_no",
							"wc_process_item_location",
							"wc_process_item_weight",
							"wc_process_item_qty",
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
					
					$PalletItems->add($wc_process_asset_code, $pallet_id, $wpi_code_number, $wpi_label_number, 0);
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
	$wc_process_verified = 'NULL';
	$apply_on_all_items_of_this_collection = $wc_process_item_pallet = $apply_data_count = $wc_process_item_stock = $wc_process_under_technician = $wc_process_is_on_way = 0;
	$chkattribute = array();
	$wc_process_item_make = $wc_process_item_model = $wc_process_item_name = $wc_process_item_sku = $wc_process_item_sr_no = $wc_process_item_weight = $wc_process_age_date = $wc_process_item_location = '';
	$data  = sanitizePostData($_POST);
	$wc_process_item_stock = isset($_POST['wc_process_item_stock']) ? 1 : 0;
	extract($data);	
	$wc_process_verified = $wc_process_verified ? 'NOW()' : 'NULL';
	if($wc_process_asset_code)
	{
		$collectionProcess = new CollectionProcess($wc_process_asset_code);
		
		if(($wc_process_item_sr_no != "" && $wc_process_item_sr_no != "N/A" ) && $collectionProcess->isSerialNumberExist($wc_process_item_sr_no)){
			echo json_encode(array("300", "warning|Serial number \"$product_serial_number\" already exist."));	
			die;
		}
		$wcdata = $collectionProcess->getDetails();
		if($wcdata)
		{			
			$collectionProcess->update(
				array(
					'wc_process_item_make' 	=> $wc_process_item_make, 
					'wc_process_item_model' => $wc_process_item_model, 
					'wc_process_item_name' 	=> $wc_process_item_name, 
					'wc_process_item_sku' 	=> $wc_process_item_sku, 
					'wc_process_item_stock' => $wc_process_item_stock, 
					'wc_process_item_sr_no' => $wc_process_item_sr_no, 
					'wc_process_item_weight'=> $wc_process_item_weight,
					'wc_process_under_technician' =>$wc_process_under_technician,
					'wc_process_age_date'=>	$wc_process_age_date,
					'wc_process_verified' => $wc_process_verified,
					'wc_process_item_location' => $wc_process_item_location,
					'wc_process_is_on_way' => $wc_process_is_on_way
					)
			);
			if($wcdata['wc_process_under_technician'] != $wc_process_under_technician)
			{
				if($wc_process_under_technician)
				$techuser = new Employee($wc_process_under_technician);
				else
				$techuser = new Employee($wcdata['wc_process_under_technician']);
				$techuserData = $techuser->getDetails();
				Activity::add(($wc_process_under_technician ? "assigned":"discharged")." Collection item <b>$wc_process_asset_code</b> ".($wc_process_under_technician ? "to":"from")." ".$techuserData['user_name']);	
			}
			$pallet = new Pallet($wc_process_item_pallet);
			if($wc_process_item_pallet && $pallet->isExist() && !$pallet->isFull()){
				$PalletItems = new PalletItems($wc_process_item_pallet);
				$wpi_label_number = $PalletItems->getPalletItemLabel();
				$wpi_code_number = $PalletItems->getPalletItemNumber($wpi_label_number);	
				$PalletItems->saveItem($wc_process_asset_code, $wc_process_item_pallet, $wpi_code_number, $wpi_label_number, 0);
			}
			elseif($wc_process_item_pallet){
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
					$values_data = isset($attribute[$values_attribute_id]) ? $attribute[$values_attribute_id] : "No";
					if(!$wcItem->isExistItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id))
						$wcItem->addItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
					else
						$wcItem->updateItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
				}				
								
				if($apply_on_all_items_of_this_collection && count($chkattribute)){
					$collectionProcess = new CollectionProcess();
					$getSimilarItems = $collectionProcess->getSimilarItemFromCollection($wcdata['wc_process_wc_id'], $wcdata['wc_process_item_id']);
					
					$process_fields = array_intersect($chkattribute, array('wc_process_item_make', 'wc_process_item_model', 'wc_process_item_name', 'wc_process_item_weight', 'wc_process_age_date', 'wc_process_item_sku', 'wc_process_item_location'));
					$similarUpdateFields = compact($process_fields);
					if($apply_data_count > 0){
						$similar_wc_process_asset_code_array = array_column($getSimilarItems, 'wc_process_asset_code');
						$from = array_search($wc_process_asset_code, $similar_wc_process_asset_code_array);
						$getSimilarItems = array_slice($getSimilarItems, $from, $apply_data_count+1, true);
					}
					
					if(count($getSimilarItems)){
						foreach($getSimilarItems as $_process){
							if($_process['wc_process_asset_code'] != $wc_process_asset_code)
							{
								$_collectionProcess = new CollectionProcess($_process['wc_process_asset_code']);
								$values_wc_process_id = $_process['wc_process_id'];
								if(count($process_fields)){
									$_collectionProcess->update($similarUpdateFields);
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
										$values_data = isset($attribute[$values_attribute_id]) ? $attribute[$values_attribute_id] : "No";
										if(!$wcItem->isExistItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id))
											$wcItem->addItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
										else
											$wcItem->updateItemValues($values_wc_id, $values_wc_process_id, $values_realtion_id, $values_item_id, $values_attribute_id, $values_data);
									}
								}
								/*
								$pallet = new Pallet($wc_process_item_pallet);
								if($wc_process_item_pallet && $pallet->isExist() && !$pallet->isFull())
								{
									$PalletItems = new PalletItems($wc_process_item_pallet);
									$wpi_label_number = $PalletItems->getPalletItemLabel();
									$wpi_code_number = $PalletItems->getPalletItemNumber($wpi_label_number);	
									$PalletItems->saveItem($wc_process_asset_code, $wc_process_item_pallet, $wpi_code_number, $wpi_label_number, $wpi_item_order);									
								}
								*/
							}
						}
					}
				}
				Activity::add("updated Collection item <b>$wc_process_asset_code</b>");				
				echo json_encode(array("200",  "success|Item Details Saved", $attribute));
			}
			else
				echo json_encode(array("200",  "warning|Product saved but Item attributes not found"));
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
				$newOptions = count($savedOptions) ? array_diff($options, $savedOptions) : $options;
				$delOptions = count($savedOptions) ? array_diff($savedOptions, $options) : $savedOptions;
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
		$technician = CollectionProcess::getTechnicianFilteration($wci_id);
		$otherfilter = array();
		array_push($otherfilter, $technician);
		$wcItem = new WcItem($wci_id);
		if($record = $wcItem->getFilterAttributes())
			echo json_encode(array("200",  "success|Filteration loaded", $record, $otherfilter));
		else
			echo json_encode(array("300",  "warning|No Filteration found"));
	}
	else
		echo json_encode(array("300",  "warning|No Item found."));		
	
}

function fetchprocessitemskudetail(){
	$wc_process_item_id = 0;
	$wc_process_item_sku = "";
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($wc_process_item_id && $wc_process_item_sku != ""){
		$cp = new CollectionProcess(0);
		if($record = $cp->getItemSkuCopy($wc_process_item_id, $wc_process_item_sku))
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
		$record = $attribute->getItemAttributesList($attribute_relation_item_id);		
		echo json_encode(array("200",  "success|Item Attribute Loaded Successfully", $record['attribute'], array_combine($record['attribute'],$record['exportable'])));
    } 
	else
        echo json_encode(array("300", "warning|Attribute Status not found."));
}

function saveitemattributes(){
	global $app;
    $attribute_relation_item_id  = 0;
	$attributes = array();
	$attributesexp = array();
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($attribute_relation_item_id != 0) 
	{
		$wcItem = new WcItem($attribute_relation_item_id); 
		if($wcItem->isExist())
		{
			$attribute = new Attribute();
			$prevAttribiutes = $attribute->getItemAttributesArray($attribute_relation_item_id);
			/*
			$newAttributes = count($prevAttribiutes) ? array_diff($attributes, $prevAttribiutes) : $attributes;
			$removeAttributes = count($prevAttribiutes) ? array_diff($prevAttribiutes, $attributes) : $prevAttribiutes;		
			*/	
			$newAttributes = array_diff($attributes, $prevAttribiutes);
			$removeAttributes = array_diff($prevAttribiutes, $attributes);		
			
			if(count($attributes)){
				foreach($attributes as $attribute_relation_attribute_id)
				{
					$attribute_relation_exportable = in_array($attribute_relation_attribute_id, $attributesexp) ? 1 : 0;
					if($attribute->isItemAttributeRelationExist($attribute_relation_item_id, $attribute_relation_attribute_id))
						$attribute->updateItemAttributeRelation($attribute_relation_item_id, $attribute_relation_attribute_id, $attribute_relation_exportable, 1);
					else
						$attribute->addItemAttribute($attribute_relation_item_id, $attribute_relation_attribute_id, $attribute_relation_exportable);
				}
			}
			
			if(count($removeAttributes)){
				foreach($removeAttributes as $attribute_relation_attribute_id){
					$attribute_relation_exportable = 0;
					$attribute->updateItemAttributeRelation($attribute_relation_item_id, $attribute_relation_attribute_id, $attribute_relation_exportable, 0);
				}
			}
			
			Activity::add("Updated Items attributes");
			echo json_encode(array("200",  "success|Item Attribute saved Successfully", $attributes, $prevAttribiutes, $removeAttributes));
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

function exportcollectionproductcsv(){
	global $app;
	$data = sanitizePostData($_POST);
	if(isset($data['filter']) && !empty($data['filter'])){
		$filter = $data['filter'];
		$searchKeyword = $data['searchKeyword'];
		if(isset($filter['wc_process_item_id']))
		{
			$condition = "WHERE 1";
			foreach($filter as $field=>$values){
				if($field == 'attribute'){
					if(is_array($values)){
						
						foreach($values as $attribute_id => $_val){
							$subValues = array();
							$subcondition = "(`values_attribute_id`='".sanitizePostData($attribute_id)."' AND ";
							if(is_array($_val))
							{						
								foreach($_val as $_v)
								{
									$subValues[] = "`values_data` = '".sanitizePostData($_v)."'";
								}
							}else{
								$subValues[] = "`values_data` = '".sanitizePostData($_val)."'";
							}
							$conditionArray[] = $subcondition. "(".implode(" OR ", $subValues)."))";
						}						
					}
					else{
							$conditionArray[] = array("`values_attribute_id`", "=", sanitizePostData($attribute_id));
							$conditionArray[] = array("`values_data`", "=", sanitizePostData($values));
					}	
				}
				elseif($field != "")
				{
					$filedCondArray = array();
					if(is_array($values)){
						foreach($values as $_val)
							$filedCondArray[] = array($field, "=", sanitizePostData($_val));
					}
					else
						$filedCondArray[] = array($field, "=", sanitizePostData($values));
					$conditionArray[] = $filedCondArray;	
				}
			}
			
			//Sprint_r($conditionArray);
			//$collectionProcess = new CollectionProcess();
			//$collectionProcess->condition = $conditionArray;
			//$condition = $collectionProcess->getCondition();
			$collectionProcessItem = new CollectionProcessItem(0);
			$sqlParam = CollectionProcess::getFilTerJoinCondition($filter, $searchKeyword);
			
			if(isset($_SESSION['COLL-PROD']['EXPORT']))
				unset($_SESSION['COLL-PROD']['EXPORT']);
			if($items = $collectionProcessItem->getCollectionItemExport($sqlParam)){
				$time = time();
				$_SESSION['COLL-PROD']['EXPORT'][md5($time)] = $items;
				echo json_encode(array("200", "success|Product export to csv intilized", md5($time), $items));
			}
			else
				echo json_encode(array("300",  "danger|Products not available to print label."));
		}
		else
			echo json_encode(array("300",  "danger|Product type filter reqired."));
	}
	else
	echo json_encode(array("300",  "danger|Please apply filter."));
}


function printcollectionproductbarcode(){
	global $app;
	$searchKeyword = '';
	$data = sanitizePostData($_POST);
	if(isset($data['filter']) && !empty($data['filter'])){
		$filter = $data['filter'];
		$searchKeyword = $data['searchKeyword'];
		if(isset($filter['wc_process_item_id']))
		{
			$condition = "WHERE 1";
			foreach($filter as $field=>$values){
				if($field == 'attribute'){
					if(is_array($values)){
						
						foreach($values as $attribute_id => $_val){
							$subValues = array();
							$subcondition = "(`values_attribute_id`='".sanitizePostData($attribute_id)."' AND ";
							if(is_array($_val))
							{						
								foreach($_val as $_v)
								{
									$subValues[] = "`values_data` = '".sanitizePostData($_v)."'";
								}
							}else{
								$subValues[] = "`values_data` = '".sanitizePostData($_val)."'";
							}
							$conditionArray[] = $subcondition. "(".implode(" OR ", $subValues)."))";
						}						
					}
					else{
							$conditionArray[] = array("`values_attribute_id`", "=", sanitizePostData($attribute_id));
							$conditionArray[] = array("`values_data`", "=", sanitizePostData($values));
					}	
				}
				elseif($field != "")
				{
					$filedCondArray = array();
					if(is_array($values)){
						foreach($values as $_val)
							$filedCondArray[] = array($field, "=", sanitizePostData($_val));
					}
					else
						$filedCondArray[] = array($field, "=", sanitizePostData($values));
					$conditionArray[] = $filedCondArray;	
				}
			}
			
			//Sprint_r($conditionArray);
			
			$collectionProcess = new CollectionProcess();
			$collectionProcess->condition = $conditionArray;
			$condition = $collectionProcess->getCondition();
			
			$sqlParam = CollectionProcess::getFilTerJoinCondition($filter, $searchKeyword);
		
			if(isset($_SESSION['COLL-PROD']['PRINT']))
				unset($_SESSION['COLL-PROD']['PRINT']);
			
			if($items = collectionProcess::getFilteredPrintProductCode($sqlParam)){
				$time = time();
				$_SESSION['COLL-PROD']['PRINT'][md5($time)] = $items;
				echo json_encode(array("200", "success|Product print to csv intilized", md5($time), $items));
			}
			else
				echo json_encode(array("300",  "danger|Products not available to print label.", $condition));
		}
		else
			echo json_encode(array("300",  "danger|Product type filter reqired."));
	}
	else
	echo json_encode(array("300",  "danger|Please apply filter."));
}


function getprocessproducthistorydetail(){
	$wpca_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($wpca_id){
		$processProductSaleHistory = new ProcessProductSaleHistory($wpca_id);
		echo json_encode(array("200", "success|Product sale record detail loaded", $processProductSaleHistory->getDetails()));
	}
	else{
		echo json_encode(array("300",  "danger|Products saled record not found."));
	}
}


function removeprocessproductsalehistory(){
	$wpca_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($wpca_id){
		$processProductSaleHistory = new ProcessProductSaleHistory($wpca_id);
		$processProductSaleHistory->remove();		
		echo json_encode(array("200", "success|Product sale record detail deleted"));
	}
	else{
		echo json_encode(array("300",  "danger|Products saled record not found."));
	}
}

function saveprocessproductsalehistory(){
	
    global $app;
    $customer_fname              = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location =  "";
    $customer_type_id            = $wpca_sell_price = $wpca_process_code = $wpca_customer_id = $wpca_is_returned =  0;
	$wpca_sell_date = $wpca_returned_date = NULL;
    $wpca_status = 1;
    $customer_password           = gePassword();
    $customer_is_mobile_verified = 0;
    $customer_is_email_verified  = 0;
    $customer_status = $customer_address_status     = 1;
	$wpca_id = 0;
	$customer_created_by = getLoginId();
    $data                        = sanitizePostData($_POST);
	//SELECT `wpca_id`, `wpca_product_code`, `wpca_customer_id`, `wpca_customer_address_id`, `wpca_sell_price`, `wpca_sell_date`, `wpca_is_returned`, `wpca_returned_date`, `wpca_store_id`, `wpca_store_reference`, `wpca_remark`, `wpca_status` FROM `app_batch_product_customer_allocation` WHERE 1
    extract($data);
	$wpca_sell_date = date("Y-m-d H:i:s", strtotime($wpca_sell_date));
    $customer_email = strtolower($customer_email);
    $wpca_created_by = $_SESSION['user_id'];
    $is_new_customer       = false;
    $Customer              = new Customer(0);
    $wpca_customer_id = $Customer->isEmailExists($customer_email);
    if (!$wpca_customer_id) {
        $customer_image        = DEFAULT_USER_IMAGE;
        $customer_code         = $Customer->getNewCode($customer_email, $customer_fname . $customer_lname);
        $wpca_customer_id = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
        $is_new_customer       = true;
    } else {
        $Customer = new Customer($wpca_customer_id);
        $Customer->update(array(
			"customer_fname" => $customer_fname,
            "customer_lname" => $customer_lname,
            "customer_phone" => $customer_phone,
            "customer_type_id" => $customer_type_id,
			"customer_company" => $customer_company
        ));
    }
    if (!$wpca_customer_id) {
        echo json_encode(array("300", "Warning|Customer Details Not Full filled. Try again"
        ));
        exit();
    }
    $CustomerAddress     = new CustomerAddress(0);
    $wpca_customer_address_id = $CustomerAddress->isCustomerAddressExists($wpca_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
    if (!$wpca_customer_address_id)
        $wpca_customer_address_id = $CustomerAddress->add($wpca_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status);
	
	
	if ($is_new_customer) {
        new SMS($customer_phone, "Hi $customer_fname, Welcome to " . $app->siteName . ". Your account had been created with us successfully. Your username is \"$customer_email\" and Password is $customer_password");
        #==============================trig Email============= 
        $activation_link = $app->basePath("activation.php?r=cst&u=$customer_email&l=" . md5($customer_email . $wpca_customer_id) . "&i=" . md5($wpca_customer_id . $customer_email));
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
    
	
		
    $ProcessProductSaleHistory = new ProcessProductSaleHistory($wpca_id);
	$bpcaData = compact('wpca_process_code', 'wpca_customer_id', 'wpca_customer_address_id', 'wpca_sell_price', 'wpca_sell_date', 'wpca_is_returned', 'wpca_returned_date', 'wpca_store_id', 'wpca_store_reference', 'wpca_remark', 'wpca_created_by', 'wpca_status');
	if($wpca_id == 0)
    $wpca_id     = $ProcessProductSaleHistory->insert($bpcaData);
	else
    $ProcessProductSaleHistory->update($bpcaData);
	Activity::add("Saved Process Product Sales Record|^|$wpca_process_code", "P", $wpca_process_code);
	//Activity::add("Saved Process Product Sales Record");
	echo json_encode(array("200",  "success|New Product Selling Record saved Successfully", $ProcessProductSaleHistory->getRecords($wpca_process_code)));
}

function getpalletitems(){
	$pallet_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($pallet_id){
		$pallet = new Pallet($pallet_id);
		$palletItems = new PalletItems($pallet_id);
		$palletData = $pallet->getDetails();
		echo json_encode(array("200", "success|Pallet Items Loaded", $palletItems->getItemsRecords(), $palletData['pallet_capacity']));
	}
	else{
		echo json_encode(array("300",  "danger|Pallet not found."));
	}
}

function getcollectionitemsoption(){
	$wc_code = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($wc_code != ""){
		$collection = new Collection();
		$collectionData = $collection->getDetailsByCode($wc_code);
		$col = new Collection($collectionData['wc_id']);
		echo json_encode(array("200", "success|Collection Items Loaded", $col->getCollectionItems()));
	}
	else{
		echo json_encode(array("300",  "danger|Collection not found."));
	}
}

function getcollectionprocessitemsnotinpallet(){
	$wc_code = "";
	$item_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($wc_code != ""){
		$collection = new Collection();
		$collectionData = $collection->getDetailsByCode($wc_code);
		$colProcess = new CollectionProcess();
		echo json_encode(array("200", "success|Collection Items Loaded", $colProcess->getCollectionItemsNotInPallet($collectionData['wc_id'], $item_id)));
	}
	else{
		echo json_encode(array("300",  "danger|Collection items not found."));
	}
}

function addmultiitemstopallet(){
	$multiitems = array();
	$pallet_id = 0;
	$data = sanitizePostData($_POST);
	$alreadyExist = $canProcess = array();
	extract($data);
	if($pallet_id != 0)
	{
		$pallet = new Pallet($pallet_id);
		if($pallet->isExist())
		{
			if($space = $pallet->getAvaialableSpace())
			{
				if($space < count($multiitems))
				{
					echo json_encode(array("300",  "danger|Pallet Don't have sufficient sapce. Only $space is available"));
					die;
				}
				$palletItem = new PalletItems();
				if(count($multiitems))
				{
					foreach($multiitems as $code)
					{
						if(!$palletItem->isItemExist($code))
							$canProcess[] = $code;
						else
							$alreadyExist[] = $code;
					}
					if(count($alreadyExist) == 0)
					{
						foreach($multiitems as $wc_process_asset_code)
						{
							$PalletItems = new PalletItems();
							$wpi_label_number = $PalletItems->getPalletItemLabel();
							$wpi_code_number = $PalletItems->getPalletItemNumber($wpi_label_number);	
							$PalletItems->saveItem($wc_process_asset_code, $pallet_id, $wpi_code_number, $wpi_label_number, 0);
						}
						echo json_encode(array("200", "success|".count($multiitems)." Items Added on Pallet"));
					}
					else
						echo json_encode(array("300",  "danger|".count($alreadyExist)." Items Already alloted on Pallet.<br/>".implode(", ", $alreadyExist)));
				}
				else
					echo json_encode(array("300",  "danger|Collection items not found."));
			}
			else
				echo json_encode(array("300",  "danger|Pallet is Full."));
		}
		else
			echo json_encode(array("300",  "danger|Pallet not found."));
	}
	else{
		echo json_encode(array("300",  "danger|Collection Pallet not found."));
	}
}

function removeitemsfrompallet(){
	$code = "";
	$pallet_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	$pallet = new Pallet($pallet_id);
	if($pallet->isExist())
	{
		$PalletItems = new PalletItems();
		$PalletItems->removePalletItemByCode($pallet_id, $code);
		echo json_encode(array("200", "success|Pallet Items Removed"));
	}
	else{
		echo json_encode(array("300",  "danger|Collection Pallet not found."));
	}
}
function verifyprocessproduct(){
	$product_code = '';
	$verify = '';
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($product_code){
		$cp = new CollectionProcess($product_code);
		if(in_array($verify, array(0,1)) && $cp->isExist())
		{
			$cp->update(array('wc_process_verified'=> $verify ? 'NOW()':'NULL'));
			echo json_encode(array("200",  "success|Collection product ".($verify ? 'Verified':'Unverified')));
		}
		else
			echo json_encode(array("300",  "warning|No Collection product found."));
	}
	else
		echo json_encode(array("300",  "warning|Invalid Collection product request."));
}
?>