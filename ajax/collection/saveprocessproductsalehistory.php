<?php

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

?>