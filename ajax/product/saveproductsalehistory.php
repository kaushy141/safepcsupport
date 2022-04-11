<?php


    $customer_fname              = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location =  "";
    $customer_type_id            = $bpca_sell_price = $bpca_product_id = $bpca_customer_id = $bpca_is_returned =  0;
	$bpca_sell_date = $bpca_returned_date = NULL;
    $bpca_status = 1;
    $customer_password           = gePassword();
    $customer_is_mobile_verified = 0;
    $customer_is_email_verified  = 0;
    $customer_status = $customer_address_status     = 1;
	$bpca_id = 0;
	$customer_created_by = getLoginId();
    $data                        = sanitizePostData($_POST);
	//SELECT `bpca_id`, `bpca_product_id`, `bpca_customer_id`, `bpca_customer_address_id`, `bpca_sell_price`, `bpca_sell_date`, `bpca_is_returned`, `bpca_returned_date`, `bpca_store_id`, `bpca_store_reference`, `bpca_remark`, `bpca_status` FROM `app_batch_product_customer_allocation` WHERE 1
    extract($data);
	$bpca_sell_date = date("Y-m-d H:i:s", strtotime($bpca_sell_date));
    $customer_email = strtolower($customer_email);
    $bpca_created_by = $_SESSION['user_id'];
    $is_new_customer       = false;
    $Customer              = new Customer(0);
    $bpca_customer_id = $Customer->isEmailExists($customer_email);
    if (!$bpca_customer_id) {
        $customer_image        = DEFAULT_USER_IMAGE;
        $customer_code         = $Customer->getNewCode($customer_email, $customer_fname . $customer_lname);
        $bpca_customer_id = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
        $is_new_customer       = true;
    } else {
        $Customer = new Customer($bpca_customer_id);
        $Customer->update(array(
			"customer_fname" => $customer_fname,
            "customer_lname" => $customer_lname,
            "customer_phone" => $customer_phone,
            "customer_type_id" => $customer_type_id,
			"customer_company" => $customer_company
        ));
    }
    if (!$bpca_customer_id) {
        echo json_encode(array("300", "Warning|Customer Details Not Full filled. Try again"
        ));
        exit();
    }
    $CustomerAddress     = new CustomerAddress(0);
    $bpca_customer_address_id = $CustomerAddress->isCustomerAddressExists($bpca_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
    if (!$bpca_customer_address_id)
        $bpca_customer_address_id = $CustomerAddress->add($bpca_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status);
	
	
	if ($is_new_customer) {
        new SMS($customer_phone, "Hi $customer_fname, Welcome to " . $app->siteName . ". Your account had been created with us successfully. Your username is \"$customer_email\" and Password is $customer_password");
        #==============================trig Email============= 
        $activation_link = $app->basePath("activation.php?r=cst&u=$customer_email&l=" . md5($customer_email . $bpca_customer_id) . "&i=" . md5($bpca_customer_id . $customer_email));
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
    
	
		
    $BatchProductSaleHistory = new BatchProductSaleHistory($bpca_id);
	$bpcaData = compact('bpca_product_id', 'bpca_customer_id', 'bpca_customer_address_id', 'bpca_sell_price', 'bpca_sell_date', 'bpca_is_returned', 'bpca_returned_date', 'bpca_store_id', 'bpca_store_reference', 'bpca_remark', 'bpca_created_by', 'bpca_status');
	if($bpca_id == 0)
    $bpca_id     = $BatchProductSaleHistory->insert($bpcaData);
	else
    $BatchProductSaleHistory->update($bpcaData);
	Activity::add("Saved Batch Product Sales Record", "B", $bpca_product_id);
	//Activity::add("Saved Batch Product Sales Record");
	echo json_encode(array("200",  "success|New Product Selling Record saved Successfully", $BatchProductSaleHistory->getRecords($bpca_product_id)));

?>