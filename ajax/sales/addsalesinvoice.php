<?php

    $customer_fname              = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $sales_invoice_payment_mode = $sales_invoice_payment_reference = $sales_invoice_cycle_mode = $sales_invoice_dispatched_store = $sales_invoice_manufacturer = "";
    $sales_product_name          = $sales_product_price = $sales_product_quantity = $sales_product_type_id = $sales_purchase_amount = $sales_purchase_source = array();
    $customer_type_id            = $sales_invoice_store_id = $send_invoice_to_customer = $sales_invoice_customer_id = $sales_invoice_is_paid = $sales_invoice_is_estimate = $sales_invoice_status = $sales_invoice_is_generated = 0;
    $customer_status     =  $sales_invoice_is_original = $sales_invoice_is_vat_applicable = $sales_discount_amount = $sales_payment_paid_amount = $sales_invoice_margin_vat_label = 0;
	$sales_invoice_date = date('Y-m-d');
    $customer_password           = gePassword();
    $customer_is_mobile_verified = 0;
    $customer_is_email_verified  = 0;
    $customer_address_status     = 1;
	$sales_invoice_vat = 0;
	$sales_invoice_is_dummy = 0;
	$sales_invoice_currency = '';
    $data                        = sanitizePostData($_POST);
    extract($data);
	$invoice_type_name = $sales_invoice_is_estimate ? "Estimate" : "Invoice";
    $customer_email                                = strtolower($customer_email);
    $sales_invoice_creator                         = $customer_created_by = $_SESSION['user_id'];
    $is_new_customer       = false;
    $Customer              = new Customer(0);
    $sales_invoice_customer_id = $Customer->isEmailExists($customer_email);
    if (!$sales_invoice_customer_id) {
        $customer_image        = DEFAULT_USER_IMAGE;
        $customer_code         = $Customer->getNewCode($customer_email, $customer_fname . $customer_lname);
        $sales_invoice_customer_id = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
        $is_new_customer       = true;
    } else {
        $Customer = new Customer($sales_invoice_customer_id);
        $Customer->update(array("customer_fname" => $customer_fname,
            "customer_lname" => $customer_lname,
            "customer_phone" => $customer_phone,
            "customer_type_id" => $customer_type_id,
			"customer_company" => $customer_company,
			"customer_tax_number" => $customer_tax_number
        ));
    }
    if (!$sales_invoice_customer_id) {
        echo json_encode(array("300", "Warning|Customer Details Not Full filled. Try again"
        ));
        exit();
    }
    $CustomerAddress     = new CustomerAddress(0);
    $sales_invoice_customer_address_id = $CustomerAddress->isCustomerAddressExists($sales_invoice_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
    if (!$sales_invoice_customer_address_id)
        $sales_invoice_customer_address_id = $CustomerAddress->add($sales_invoice_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status);
	
	
	if ($is_new_customer) {
        new SMS($customer_phone, "Hi $customer_fname, Welcome to " . $app->siteName . ". Your account had been created with us successfully. Your username is \"$customer_email\" and Password is $customer_password");
        #==============================trig Email============= 
        $activation_link = $app->basePath("activation.php?r=cst&u=$customer_email&l=" . md5($customer_email . $sales_invoice_customer_id) . "&i=" . md5($sales_invoice_customer_id . $customer_email));
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
    
	
		
    $SalesInvoice = new SalesInvoice(0);
    $sales_invoice_number = $SalesInvoice->getSalesInvoiceNumber();
	$sales_invoice_id  = $SalesInvoice->insert(array(
		"sales_invoice_number" => $sales_invoice_number,
        "sales_invoice_customer_id" => $sales_invoice_customer_id,
        "sales_invoice_customer_address_id" => $sales_invoice_customer_address_id,
        "sales_invoice_store_id" => $sales_invoice_store_id,
		"sales_invoice_is_paid" => $sales_invoice_is_paid, 
		"sales_invoice_payment_mode" => $sales_invoice_payment_mode, 
		"sales_invoice_payment_reference" => $sales_invoice_payment_reference, 
		"sales_invoice_cycle_mode" => $sales_invoice_cycle_mode,
		"sales_invoice_created_date" => "NOW()",
		"sales_invoice_date" => $sales_invoice_date,
		"sales_invoice_creator" => $sales_invoice_creator,
		"sales_invoice_is_estimate" => $sales_invoice_is_estimate,
		"sales_invoice_is_vat_applicable" => $sales_invoice_is_vat_applicable,
		"sales_discount_amount" => $sales_discount_amount,
		"sales_invoice_manufacturer" => $sales_invoice_manufacturer,
		"sales_invoice_status" => $sales_invoice_status,
		"sales_invoice_is_generated" => $sales_invoice_is_generated,
		"sales_invoice_vat" => $sales_invoice_vat,	
		"sales_invoice_currency" => $sales_invoice_currency,
		"sales_invoice_dispatched_store" => $sales_invoice_dispatched_store,
		"sales_payment_paid_amount" => $sales_payment_paid_amount,
		"sales_invoice_is_dummy" => $sales_invoice_is_dummy,
		"sales_invoice_margin_vat_label" => $sales_invoice_margin_vat_label
    ));
    
	$SalesInvoice->updateNextInvoiceDate();
    
	if(count($sales_product_name) == count($sales_product_price) && count($sales_product_name)== count($sales_product_quantity) && count($sales_product_sku) == count($sales_product_quantity) && count($sales_product_name) == count($sales_product_type_id) && count($sales_product_name)>0 )
	{
		$isProductSkipped = false;
		foreach($sales_product_name as $index => $product_name)
		{
			$_sales_product_name 		= sanitizePostData(urldecode($sales_product_name[$index]));
			$_sales_product_price 		= $sales_product_price[$index];
			$_sales_product_quantity 	= $sales_product_quantity[$index];
			$_sales_product_sku 		= $sales_product_sku[$index];
			$_sales_product_batch_code	= $sales_product_batch_code[$index] == '-' ? '' : $sales_product_batch_code[$index] ;
			$_sales_product_type_id 	= $sales_product_type_id[$index];
			$_sales_purchase_amount 	= $sales_purchase_amount[$index];
			$_sales_purchase_source 	= $sales_purchase_source[$index];
			if($_sales_product_quantity>0)
			{
				$product = new Product(0);
				if(!$sipd_product_id = $product->isHardwareProductExist($_sales_product_name, $_sales_product_type_id))
				$sipd_product_id = $product->add($_sales_product_name, $_sales_product_type_id, $_sales_product_price, $sales_invoice_is_vat_applicable, 1);	
				
				$SalesInvoice->addProducts($sales_invoice_id, $sipd_product_id, $_sales_product_quantity, $_sales_product_sku, $_sales_product_batch_code, $_sales_product_price, $_sales_purchase_amount, $_sales_purchase_source);
			}
			else
				$isProductSkipped = true;			
		}
		
		if($isProductSkipped)
		{
			$SalesInvoice->delete();
			echo json_encode(array("300",  "error|Ooops...Some Product skipped. All Product Quantiry and Price must be Greater than 0. No Invoice saved. try Again."));
			exit;
		}
		else
		{
			$store = new Store($sales_invoice_store_id);
			$storeData = $store->getDetails();
			
						
			//===============================================
			if($sales_invoice_is_vat_applicable == 1)
			{
				$store_vat_percent = $sales_invoice_vat ? $sales_invoice_vat : ($storeData['store_vat_percent'] ? $storeData['store_vat_percent'] : SALES_VAT_PERCENTAGE);
			}
			else
			{
				$store_vat_percent = 0;
			}
			$sumAmount = $SalesInvoice->getInvoiceSum($store_vat_percent);
			$sales_invoice_total_amount = $sumAmount['product_sum_vat'] + $sumAmount['product_sum_amount'];
			$SalesInvoice->update(array("sales_invoice_total_amount"=>$sales_invoice_total_amount));
			//===============================================
			
			if($sales_invoice_is_paid == 1){
			$SalesInvoice->update(
									array(
										"sales_payment_paid_amount" => $sales_invoice_total_amount - $sales_discount_amount
										)
								);
			}
			
			
			new SMS($customer_phone, "Your Sales $invoice_type_name generated successfully. Your Sales $invoice_type_name number is #$sales_invoice_number. Your registered email id is $customer_email .");
			$dataArray = array(
				"customer_name" => $customer_fname,
				"store_name" =>$storeData['store_name'],
				"invoice_type_name" => $invoice_type_name,
				"sales_invoice_number" => $sales_invoice_number,
				"invoice_type_name" => $invoice_type_name,
				"login_page" => $app->basePath("customer-login.php"),
				"invoice_link" => $app->basePath("salesinvoice/$sales_invoice_id")
			);
			if($send_invoice_to_customer){
				$email     = new Email("New Sales $invoice_type_name #$sales_invoice_number Generated");
				$email->to($customer_email, $customer_fname);			
				$email->addFile(DOC::SALESINV($sales_invoice_id), $app->siteName . " Sales $invoice_type_name - $sales_invoice_number.pdf");
				$email->template('newsalesinvoice', $dataArray)->send();
			}
			
			Activity::add("Added".($sales_invoice_is_dummy ? " <b>dummy</b>":"")."|^|$sales_invoice_number", "S", $sales_invoice_id);
			echo json_encode(array("200",  "success|New Sales $invoice_type_name Added Successfully",
				$sales_invoice_id,
				$sales_invoice_customer_id,
				$sales_invoice_customer_address_id
			));
		}		
	}

?>