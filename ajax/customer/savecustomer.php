<?php
    $customer_fname = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $customer_remark = $customer_trade_licence = '';
	$customer_id = 0;
	$customer_address_id = 0;
	$customer_image = DEFAULT_USER_IMAGE;
    $data           = sanitizePostData($_POST);
    extract($data);
    $customer_email = trim(strtolower($customer_email));
	$customerDetails = array(
		"customer_fname" => $customer_fname,
        "customer_lname" => $customer_lname,
        "customer_email" => $customer_email,
        "customer_phone" => $customer_phone,
        "customer_type_id" => $customer_type_id,
		"customer_company" => $customer_company,
		"customer_tax_number" => $customer_tax_number,
		"customer_remark"  => $customer_remark,
		"customer_status" => 1
    );
	
    $customer       = new Customer($customer_id);
	$customerIsExist = $customer->isExist();
	if($customerIsExist)
	{
		$customerOldData  = $customer->getDetails();
		$customer->update($customerDetails);
		if ($customerOldData['customer_email'] != $customer_email) 
		{
			$message = "Hi $customer_fname, Welcome to " . $app->siteName . ". Your account email <i>$customerOldData[customer_email]</i> is replaced by <b>$customer_email</b>. Your username is \"$customer_email\" and Password is $customerOldData[customer_password]";
			new SMS($customer_phone, $message);
			#==============================trig Email============= 
			$activation_link = $app->basePath("activation.php?r=cst&u=$customer_email&l=" . md5($customer_email . $complaint_customer_id) . "&i=" . md5($complaint_customer_id . $customer_email));
			$dataArray       = array(
				"customer_name" => $customer_fname,
				"customer_email" => $customer_email,
				"customer_password" => $customer_password,
				"login_page" => $app->basePath("customer-login.php"),
				"activation_link" => $activation_link,
				"message" => $message
			);
			$email = new Email("Customer Account update on " . $app->siteName);
			$email->to("$customer_email", $customer_fname)->template('customer_update', $dataArray)->send();
		}
	}
	else
	{
		$customer_id = $customer->insert(array_merge($customerDetails, array("customer_created_date" => "NOW", "customer_image" => $customer_image)));
	}
	
	if($customer_address_street_number != "" && $customer_address_route != "" && $customer_address_locality != "" && $customer_address_administrative_area != "" &&  $customer_address_country != "" && $customer_address_postcode != "")
	{
		$CustomerAddress = new CustomerAddress($customer_address_id);
		if ($customer_address_id != 0 || $customer_address_id = $CustomerAddress->isCustomerAddressExists($customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country)){
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
		}
		else
		{
			$customer_address_id = $CustomerAddress->insert(
			array(
				"customer_id" => $customer_id,
				"customer_address_street_number" => $customer_address_street_number,
				"customer_address_route" => $customer_address_route,
				"customer_address_locality" => $customer_address_locality,
				"customer_address_administrative_area" => $customer_address_administrative_area,
				"customer_address_country" => $customer_address_country,
				"customer_address_postcode" => $customer_address_postcode,
				"customer_address_geo_location" => $customer_address_geo_location,
				"customer_address_created_date" => "NOW()",
				"customer_address_status" => 1
			));
		}
	}
    if (isset($_SESSION['UPLOAD'][$field_handler]['PIC'])) {
        $image_name     = pathinfo($_SESSION['UPLOAD'][$field_handler]['PIC']);
        $extension      = strtolower($image_name['extension']);
        $customer_image = "upload/customer/customer_" . time() . "_" . $customer_id . ".$extension";
        if (rename($_SESSION['UPLOAD'][$field_handler]['PIC'], BP.$customer_image)) {
            $customer->update(array("customer_image" => $customer_image
            ));
        }
        unset($_SESSION['UPLOAD'][$field_handler]['PIC']);
    }
	
	if ($customer_trade_licence != "") {
		$customer_trade_licence_path = "upload/customer/trade/".getDirectorySeparatorPath()."trade-$customer_id-" . time()  . ".".pathinfo($customer_trade_licence, PATHINFO_EXTENSION);		
		if (move_file($app->sitePath($customer_trade_licence), $app->sitePath($customer_trade_licence_path))){
			$customer->update(array("customer_trade_licence" => $customer_trade_licence_path));
		}
	}
	
	
    Activity::add(($customerIsExist ? "Updated":"Added")." Customer <b>$customer_fname $customer_lname</b> Information");
    echo json_encode(array("200",  "success|Customer Details ".($customerIsExist ? "Updated":"Added")." Successfully",
        $customer_id,
        $customer_address_id
    ));

?>