<?php
function addproduct()
{
    global $app;
    $product_id     = $product_hardware_id = $product_price = $product_inc_vat = 0;
	$product_quantity = 1;
    $product_status = 1;
    $product_name   = "";
    $data           = sanitizePostData($_POST);
    $product        = new Product(0);
    extract($data);
	if($product_inc_vat == 0)
	{
	   $product_price = $product_price - round((($product_price * SALES_VAT_PERCENTAGE) / 100),2);
	   $product_inc_vat = 1;
	}
    if (!$product->isproductexist($product_name)) {
        $product_id = $product->add($product_name, $product_hardware_id, $product_quantity, $product_price, $product_inc_vat, $product_status);
        echo json_encode(array("200",  "success|Product Added Successfully"
        ));
    } else
        echo json_encode(array("200",  "warning|Product with same name allready exist"
        ));
}
function updateproduct()
{
    global $app;
    $product_id     = $product_hardware_id = $product_price = $product_id = $product_inc_vat = 0;
    $product_status = 1;
	$product_quantity = 1;
    $product_name   = "";
    $data           = sanitizePostData($_POST);
    extract($data);
    $product        = new Product($product_id);
	if($product_inc_vat == 0)
	{
	   $product_price = $product_price - round((($product_price * SALES_VAT_PERCENTAGE) / 100),2);
	   $product_inc_vat = 1;
	}
	
    if (!$product->isproductexist($product_name, $product_id)) {
        $product->update(array(
								"product_name"=>$product_name, 
								"product_hardware_id"=>$product_hardware_id, 
								"product_price"=>$product_price,
								"product_inc_vat"=>$product_inc_vat,
								"product_quantity"=>$product_quantity
								)
						);
        echo json_encode(array("200",  "success|Product Updated Successfully"
        ));
    } else
        echo json_encode(array("200",  "warning|Product with same name allready exist"
        ));
}
function addsalesinvoice()
{
    global $app;
    $customer_fname              = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $sales_invoice_payment_mode = $sales_invoice_payment_reference = $sales_invoice_cycle_mode = $sales_invoice_dispatched_store = $sales_invoice_manufacturer = "";
    $sales_product_name          = $sales_product_price = $sales_product_quantity = $sales_product_type_id = array();
    $customer_type_id            = $sales_invoice_store_id = $send_invoice_to_customer = $sales_invoice_customer_id = $sales_invoice_is_paid = $sales_invoice_is_estimate = $sales_invoice_status = $sales_invoice_is_generated = 0;
    $customer_status     =  $sales_invoice_is_original = $sales_invoice_is_vat_applicable = $sales_discount_amount = $sales_payment_paid_amount = 0;
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
		"sales_invoice_is_dummy" => $sales_invoice_is_dummy
    ));
    //$sales_invoice_id     = $SalesInvoice->add($sales_invoice_number, $sales_invoice_customer_id, $sales_invoice_customer_address_id, $sales_invoice_store_id, $sales_invoice_is_paid, $sales_invoice_payment_mode, $sales_invoice_payment_reference, $sales_invoice_cycle_mode, $sales_invoice_is_generated, $sales_invoice_is_original, $sales_invoice_creator, $sales_invoice_is_estimate, $sales_invoice_is_vat_applicable, $sales_discount_amount, $sales_invoice_status, $sales_invoice_vat, $sales_invoice_currency);
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
			if($_sales_product_quantity>0)
			{
				$product = new Product(0);
				if(!$sipd_product_id = $product->isHardwareProductExist($_sales_product_name, $_sales_product_type_id))
				$sipd_product_id = $product->add($_sales_product_name, $_sales_product_type_id, $_sales_product_price, $sales_invoice_is_vat_applicable, 1);	
				
				$SalesInvoice->addProducts($sales_invoice_id, $sipd_product_id, $_sales_product_quantity, $_sales_product_sku, $_sales_product_batch_code, $_sales_product_price);
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
}

function removeinvoicepackinguser(){
	$sales_invoice_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($sales_invoice_id){
		$salesInvoice = new SalesInvoice($sales_invoice_id);
		if($salesInvoice->isExist())
		{
			$action_user_id = getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $salesInvoice->getDetails();
			if($details['sales_invoice_cancel_user'] == 0 || $details['sales_invoice_status'] != 3 )
			{
				if($details['sales_invoice_packing_user'] != 0 && $details['sales_invoice_process_user'] == 0)
				{						
					$salesInvoice->update(array(
						"sales_invoice_packing_user"=> 0,
						"sales_invoice_packing_time" => 'NULL'
					));
					Activity::add("Removed assigned Packing user from |^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
					echo json_encode(array("200",  "success|Packing user removed."));
				}
				else
				{
					echo json_encode(array("300",  "warning|Sales Invoice is Processed. Can't remove Packing user"));
				}				
			}
			else
				echo json_encode(array("300",  "warning|Sales Invoice is Cancelled or Completed. Can't remove Packing user"));
		}
		else
			echo json_encode(array("300",  "warning|Sales Invoice not found"));
	}
	else
		echo json_encode(array("300",  "warning|Not a valid Sales Invoice"));
}

function duplicatesalesinvoice(){
	global $app;
	$sales_invoice_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	$salesinvoice = new SalesInvoice($sales_invoice_id);
	if($salesinvoice->isExist())
	{		
		$sales_invoice_id_new = $salesinvoice->duplicateInvoice();
		echo json_encode(array("200",  "success|Duplicate sales invoice created", $sales_invoice_id_new));
	}
	else
		echo json_encode(array("300", "danger|Requested sales invoice not found."));	
}

function updatesalesinvoice()
{	
    global $app;
    $customer_fname              = $customer_lname = $customer_email = $customer_phone =  $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $sales_invoice_manufacturer = "";
    $sales_product_name          = $sales_product_price = $sales_product_quantity = $sales_product_type_id = array();
    $customer_type_id            = $sales_invoice_store_id = $send_invoice_to_customer = $sales_invoice_customer_id = $sales_invoice_customer_address_id = $sales_invoice_is_paid = $sales_invoice_is_estimate = $sales_invoice_is_vat_applicable = $sales_invoice_status = $sales_discount_amount = $sales_invoice_is_generated = $sales_payment_paid_amount = 0;
    $customer_status             = 1;
    $customer_password           = gePassword();
    $customer_is_mobile_verified = 0;
    $customer_is_email_verified  = 0;
    $customer_address_status     = 1;
	$sales_invoice_is_dummy = 0;
	$sales_invoice_vat = 0;
	$sales_invoice_currency = '';
    $data                        = sanitizePostData($_POST);
    extract($data);
	
	$invoice_type_name = $sales_invoice_is_estimate ? "Estimate" : "Invoice";
    $SalesInvoice = new SalesInvoice($sales_invoice_id);
    $prevData  = $SalesInvoice->load();
	$sales_invoice_is_generated = ($prevData['sales_invoice_is_generated'] == 1 && !isAdminRole()) ? $prevData['sales_invoice_is_generated'] : $sales_invoice_is_generated;
	
	
    if (!$prevData) {
        echo json_encode(array("300", "Error|Sales $invoice_type_name not Exists."
        ));
        exit();
    }
	$sales_invoice_number = $prevData['sales_invoice_number'];
    $customer_email  = strtolower($customer_email);
    
    $Customer = new Customer($sales_invoice_customer_id);
    $Customer->update(array("customer_fname" => $customer_fname,
        "customer_lname" => $customer_lname,
        "customer_email" => $customer_email,
        "customer_phone" => $customer_phone,
        "customer_type_id" => $customer_type_id,
		"customer_company" => $customer_company,
		"customer_tax_number" => $customer_tax_number
    ));
    $CustomerAddress = new CustomerAddress($sales_invoice_customer_address_id);
	$CustomerExistedAddres = $CustomerAddress->isCustomerAddressExists($sales_invoice_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
    if (!$CustomerExistedAddres)
        $CustomerAddress->update(array("customer_id" => $sales_invoice_customer_id,
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
		$sales_invoice_customer_address_id = $CustomerExistedAddres;	
	
	if($prevData['sales_invoice_status'] == 1 || $prevData['sales_invoice_status'] == 3){
		if(!isAdminRole())
		$sales_invoice_status = $prevData['sales_invoice_status'];
	}
		
	$SalesInvoice->update(array(
        "sales_invoice_customer_id" => $sales_invoice_customer_id,
        "sales_invoice_customer_address_id" => $sales_invoice_customer_address_id,
        "sales_invoice_store_id" => $sales_invoice_store_id,
		"sales_invoice_is_paid" => $sales_invoice_is_paid, 
		"sales_invoice_payment_mode" => $sales_invoice_payment_mode, 
		"sales_invoice_payment_reference" => $sales_invoice_payment_reference, 
		"sales_invoice_cycle_mode" => $prevData['sales_invoice_is_original']? $sales_invoice_cycle_mode : $prevData['sales_invoice_cycle_mode'],
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
		"sales_invoice_is_dummy" => $sales_invoice_is_dummy
    ));
		
	if($sales_invoice_status != $prevData['sales_invoice_status'] && ($sales_invoice_status == 1 || $sales_invoice_status == 3)){
		if($sales_invoice_status == 1){
			$SalesInvoice->update(
									array(
										"sales_invoice_is_cancelled" => 0,
										"sales_invoice_cancel_user" => 0,
										"sales_invoice_cancel_time" => "NULL"
										)
								);
			Activity::add("Completed|^|{$prevData['sales_invoice_number']}","S", $sales_invoice_id);
		}
		elseif($sales_invoice_status == 3){
			$SalesInvoice->update(
									array(
										"sales_invoice_is_cancelled" => 1,
										"sales_invoice_cancel_user" => getLoginId(),
										"sales_invoice_cancel_time" => "NOW()"
										)
								);
			Activity::add("Cancelled|^|{$prevData['sales_invoice_number']}","S", $sales_invoice_id);
		}
	}
		
    $invoiceSavedProductsArray = $SalesInvoice->getProductsIdArray();
	if(count($sales_product_name) == count($sales_product_price) && count($sales_product_name)== count($sales_product_quantity) && count($sales_product_sku)== count($sales_product_quantity)  && count($sales_product_batch_code)== count($sales_product_quantity) && count($sales_product_name) == count($sales_product_type_id) && count($sales_product_name)>0 )
	{
		$isProductSkipped = false;
		//print_r($sales_product_name);die;
		foreach($sales_product_name as $index => $product_name)
		{
			$_sales_product_name 		= sanitizePostData(urldecode($sales_product_name[$index]));
			//echo $_sales_product_name;die;
			$_sales_product_price 		= $sales_product_price[$index];
			$_sales_product_quantity 	= $sales_product_quantity[$index];
			$_sales_product_sku 		= $sales_product_sku[$index];
			$_sales_product_batch_code 	= $sales_product_batch_code[$index] == '-' ? '' : $sales_product_batch_code[$index] ;
			$_sales_product_type_id 	= $sales_product_type_id[$index];
			if($_sales_product_quantity>0)
			{
				$product = new Product(0);
				if(!$sipd_product_id = $product->isHardwareProductExist($_sales_product_name, $_sales_product_type_id))
				$sipd_product_id = $product->add($_sales_product_name, $_sales_product_type_id, $_sales_product_price, $sales_invoice_is_vat_applicable, 1);	
				//echo $sipd_product_id."<br/>";
				if(in_array($sipd_product_id, $invoiceSavedProductsArray))
				{					
					$SalesInvoice->updateProduct($sipd_product_id, $_sales_product_quantity, $_sales_product_sku, $_sales_product_batch_code, $_sales_product_price);
					if (($key = array_search($sipd_product_id, $invoiceSavedProductsArray)) !== false) {
						unset($invoiceSavedProductsArray[$key]);
					}
				}
				else
					$SalesInvoice->addProducts($sales_invoice_id, $sipd_product_id, $_sales_product_quantity, $_sales_product_sku, $_sales_product_batch_code, $_sales_product_price);				
			}
			else
				$isProductSkipped = true;			
		}
		//print_r($invoiceSavedProductsArray);
		if(count($invoiceSavedProductsArray))
		foreach($invoiceSavedProductsArray as $sipd_product_id)
		$SalesInvoice->removeProduct($sipd_product_id);		
		
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
		
		
		new SMS($customer_phone, "Your Sales $invoice_type_name #$sales_invoice_number updated successfully. Your registered email id is $customer_email .");
		$dataArray = array(
			"customer_name" => $customer_fname,
			"store_name" =>$storeData['store_name'],
			"sales_invoice_number" => $sales_invoice_number,
			"login_page" => $app->basePath("customer-login.php"),
			"invoice_type_name" => $invoice_type_name,
			"invoice_link" => $app->basePath("salesinvoice/$sales_invoice_id")
		);
		if($send_invoice_to_customer)
		{
			$email     = new Email("Sales $invoice_type_name #$sales_invoice_number Updated");
			$email->to($customer_email, $customer_fname);		
			$email->addFile(DOC::SALESINV($sales_invoice_id), $app->siteName . " Sales $invoice_type_name - $sales_invoice_number.pdf");
			$email->template('newsalesinvoice', $dataArray)->send();
		}
		
		
		Activity::add("Updated".($sales_invoice_is_dummy ? " <b>dummy</b>":"")."|^|{$prevData['sales_invoice_number']}", "S", $sales_invoice_id);
		echo json_encode(array("200",  "success|Sales $invoice_type_name Updated Successfully",
			$sales_invoice_id,
			$sales_invoice_customer_id,
			$sales_invoice_customer_address_id
		));		
	}else{
		 echo json_encode(array("300", "Error|Insufficient data found. Please try again"
        ));
	}
}
	
function cancelinvoice()
{
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$SalesInvoice = new SalesInvoice($id);
	if($SalesInvoice->isExist())
	{
		$record = $SalesInvoice->getDetails();
		if($record['sales_invoice_is_cancelled']==0)
		{
			$SalesInvoice->update(
									array(
										"sales_invoice_status" => 3,
										"sales_invoice_is_cancelled" => 1,
										"sales_invoice_cancel_user" => getLoginId(),
										"sales_invoice_cancel_time" => "NOW()"
										)
								);
			Activity::add("Cancelled|^|{$record['sales_invoice_number']}","S", $id);
			echo json_encode(array("200",  "success|Sales Invoice cancelled Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Sales Invoice allready cancelled."));
	}
	else
		echo json_encode(array("300",  "warning|Sales Invoice record not found."));
}


function undocancelinvoice()
{
	global $app;
	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$SalesInvoice = new SalesInvoice($id);
	if($SalesInvoice->isExist())
	{
		$record = $SalesInvoice->getDetails();
		if($record['sales_invoice_is_cancelled']==1)
		{
			$SalesInvoice->update(
									array(
										"sales_invoice_is_cancelled" => 0,
										"sales_invoice_status" => 4,
										"sales_invoice_cancel_user" => 0,
										"sales_invoice_cancel_time" => "NULL"
										)
								);
			Activity::add("UnDo Cancelled|^|{$record['sales_invoice_number']}","S", $id);
			echo json_encode(array("200",  "success|Sales Invoice cancelled Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Sales Invoice not cancelled."));
	}
	else
		echo json_encode(array("300",  "warning|Sales Invoice record not found."));
}
function removeinvoicemedia(){
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$wopm = new SalesMedia($record_id);
		$wopm->update(array("sales_image_status"=>0));
		echo json_encode(array("200",  "success|Sales invoice image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No Sales invoice image found."));	
}

function salescommissionsummary(){
	$sales_commission_batch = '';
	$data = sanitizePostData($_POST);
	extract($data);	
	if($sales_commission_batch != "")
	{
		Modal::load(array('SalesCommission'));
		$salesCommission = new SalesCommission();		
		if($records = $salesCommission->getCommissionDetails($sales_commission_batch))
		{
			echo json_encode(array("200",  "success|Sales commission details loaded", $records));
		}
		else
			echo json_encode(array("300", "danger|No Commission records found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid commission."));
}

function getsalescommissionrecord(){
	$user_id = $user_month = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($user_id != "" && $user_month != "")
	{
		Modal::load(array('SalesCommission'));
		$salesCommission = new SalesCommission();		
		if(!$salesCommission->isCommissionExist($user_id, date("Y-m-d", strtotime($user_month))))
		{			
			$user_month = date("Y-m", strtotime($user_month));
			$records = SalesCommission::getUserCompletedCommisionList($user_id);
			echo json_encode(array("200",  "success|Sales Records loaded", $records, $user_id, date("Y-m-d", strtotime($user_month))));
		}
		else
			echo json_encode(array("300", "danger|User already alloted commission for this month."));
	}
	else
		echo json_encode(array("300", "danger|No user found."));
}

function removesalesinvoicefromcommission(){
	$sales_invoice_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($sales_invoice_id != 0 )
	{
		$salesinvoice = new SalesInvoice($sales_invoice_id);
		if($salesinvoice->isExist())
		{
			$salesinvoice->update(array('sales_invoice_commission_processed'=>1));
			echo json_encode(array("200",  "success|Sales Invoice Removed from Commission"));
		}			
		else
			echo json_encode(array("300", "danger|No Invoice found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid invoice."));
}

function updateslaesproductserial(){
	$srno = $type = $sipd_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($srno != "" )
	{
		$salesinvoice = new SalesInvoice();
		$salesinvoice->updateProductSerials($sipd_id, $srno, $type);
		echo json_encode(array("200",  "success|Product serial number ".($type ? "added":"removed")));
	}
	else
		echo json_encode(array("300", "danger|Not a valid serial number."));	
}

function savesalescommission(){
	$sales_commission_user_id = $sales_commission_month = 0;
	$chkinvoice = $commission_percent = array();
	$data = sanitizePostData($_POST);
	Modal::load(array('SalesCommission'));
	extract($data);	
	//print_r($data);die;
	if($sales_commission_user_id != "" && $sales_commission_month != "")
	{
		$salesCommission = new SalesCommission();		
		if(!$salesCommission->isCommissionExist($sales_commission_user_id, $sales_commission_month))
		{	if(count($chkinvoice) > 0 && count($chkinvoice) == count($commission_percent))	
			{
				$processCommisionIds = array();
				foreach($chkinvoice as $code => $commisionArray)
				{
					$processCommisionIds = array_merge($processCommisionIds, $commisionArray);
				}
				if(!$salesCommission->isSalesCommisionIssued($processCommisionIds))
				{
					$sales_commission_batch = $salesCommission->getSalesCommissionBatchCode();
					foreach($chkinvoice as $code => $commisionArray)
					{
						foreach($commisionArray as $key => $sales_commission_sales_id)
						{
							$sales_commission_percentage = $commission_percent[$code][$key];
							$sales_invoice_amount = SalesCommission::getSalesInvoiceAmount($code, $sales_commission_sales_id, $sales_commission_user_id);
							$sales_commission_amount = round(($sales_invoice_amount * $sales_commission_percentage)/100, 2);
							if($salesCommission->insert(array(
								"sales_commission_sales_id" 	=> $sales_commission_sales_id, 
								"sales_commission_type_code" 	=> $code, 
								"sales_commission_created_date" => "NOW()",
								"sales_commission_user_id" 		=> $sales_commission_user_id,
								"sales_commission_month" 		=> $sales_commission_month, 
								"sales_commission_invoice_amount"	=> $sales_invoice_amount,
								"sales_commission_percentage" 	=> $sales_commission_percentage,
								"sales_commission_amount" 		=> $sales_commission_amount,
								"sales_commission_batch" 		=> $sales_commission_batch,
								"sales_commission_status" 		=> 1
								))
							)
							SalesCommission::markCommissionProcessed($code, $sales_commission_sales_id);
						}
					}
					echo json_encode(array("200",  "success|Sales Commision genarated"));
				}
				else
					echo json_encode(array("300", "danger|Some invoices already commisioned to employee."));
			}
			else
				echo json_encode(array("300", "danger|Invoices and their commission not matched"));
		}
		else
			echo json_encode(array("300", "danger|User already alloted commission for this month."));
	}
	else
		echo json_encode(array("300", "danger|No user found."));
}


function salesinvoiceaction(){
	global $app;
	$order_action = '';
	$sales_invoice_id = 0;
	$invoice_user_id = 0;
	$user_reset = 0;
	$sales_invoice_is_outsourced = 0;
	$same_user_to_pack_order = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($sales_invoice_id){
		$salesInvoice = new SalesInvoice($sales_invoice_id);
		if($salesInvoice->isExist())
		{
			$action_user_id = $invoice_user_id ?  $invoice_user_id : getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $salesInvoice->getDetails();
			if($details['sales_invoice_cancel_user'] == 0 || $details['sales_invoice_status'] != 3 )
			{
				if($order_action == 'pick')
				{
					$updateArry = array(
					"sales_invoice_picking_user"=> $action_user_id,
					"sales_invoice_picking_time" => 'NOW()');
					if($same_user_to_pack_order == 1){
						$updateArry["sales_invoice_packing_user"] = $action_user_id;
						$updateArry["sales_invoice_packing_time"] = 'NOW()';
						$updateArry["sales_invoice_is_outsourced"]= 0;
					}
					$salesInvoice->update($updateArry);
					
					if($action_user_id != getLoginId())
					{
						$userTag = new UserTag();
						$userTag->insert(array(
								"tag_mentioner_id" 	=> getLoginId(), 
								"tag_user_id" 		=> $action_user_id, 
								"tag_module_code"	=> 'S', 
								"tag_module_id"		=> $sales_invoice_id, 
								"tag_time"			=> 'NOW()', 
								"tag_is_readed"		=> 0, 
								"tag_type"			=> 'assigned picking by', 
								"tag_text"			=> $details['sales_invoice_number'],
								"tag_log_id"		=> 0
							)
						);
					}
					
					Activity::add("marked <b>Picked</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
					if($same_user_to_pack_order == 1){
						$userTag = new UserTag();
						if($action_user_id != getLoginId())
						{
							$userTag->insert(array(
									"tag_mentioner_id" 	=> getLoginId(), 
									"tag_user_id" 		=> $action_user_id, 
									"tag_module_code"	=> 'S', 
									"tag_module_id"		=> $sales_invoice_id, 
									"tag_time"			=> 'NOW()', 
									"tag_is_readed"		=> 0, 
									"tag_type"			=> 'assigned packing by', 
									"tag_text"			=> $details['sales_invoice_number'],
									"tag_log_id"		=> 0
								)
							);
						}
						Activity::add("marked <b>Packed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
					}
					echo json_encode(array("200",  "success|Sales Invoice marked as Picked".($same_user_to_pack_order ? " and Packed":"")));
				}
				elseif($order_action == 'pack')
				{
					if($details['sales_invoice_picking_user'] != 0)
					{
						if($details['sales_invoice_packing_user'] == 0 || ($user_reset != 0 && $user_reset == md5($details['sales_invoice_packing_user']))){
							$salesInvoice->update(array(
							"sales_invoice_packing_user"=> $action_user_id,
							"sales_invoice_packing_time" => 'NOW()',
							"sales_invoice_is_outsourced" => $sales_invoice_is_outsourced
							));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'S', 
										"tag_module_id"		=> $sales_invoice_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned packing by', 
										"tag_text"			=> $details['sales_invoice_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add(($user_reset ? "Remarked":"marked")." <b>Packed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
							echo json_encode(array("200",  "success|Sales Invoice ".($user_reset ? "Remarked":"marked")." as Packed"));
						}
						else{
							$employee = new Employee($details['sales_invoice_packing_user']);
							echo json_encode(array("300",  "warning|Sales Invoice already Packed by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|Sales Invoice should be Picked first"));
					}
				}
				elseif($order_action == 'process')
				{
					if($details['sales_invoice_packing_user'] != 0)
					{
						if($details['sales_invoice_process_user'] == 0){
							$salesInvoice->update(array(
							"sales_invoice_process_user"=> $action_user_id,
							"sales_invoice_process_time" => 'NOW()',
							"sales_invoice_status" => 4));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'S', 
										"tag_module_id"		=> $sales_invoice_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned processing by', 
										"tag_text"			=> $details['sales_invoice_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add("marked <b>Processed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
							echo json_encode(array("200",  "success|Sales Invoice marked as Process"));
						}
						else{
							$employee = new Employee($details['sales_invoice_process_user']);
							echo json_encode(array("300",  "warning|Sales Invoice already Process by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|Sales Invoice should be Packed first"));
					}
				}
				elseif($order_action == 'cancel')
				{				
					$salesInvoice->update(array(
					"sales_invoice_cancel_user"=> $action_user_id,
					"sales_invoice_cancel_time" => 'NOW()',
					"sales_invoice_status" => 3
					));
					Activity::add("marked <b>Cancelled</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
					echo json_encode(array("200",  "success|Sales Invoice marked as Cancel"));
				}
				else
				{
					echo json_encode(array("300",  "warning|Invalid Action Found"));
				}				
			}
			else
			{				
				$employee = new Employee($details['sales_invoice_cancel_user']);
				echo json_encode(array("300",  "warning|Sales Invoice already Cancel by ".$employee->get('user_fname')));
			}
		}
		else
		{
			echo json_encode(array("300",  "warning|Sales Invoice not exist"));
		}
	}
	else
	{
		echo json_encode(array("300",  "warning|Sales Invoice not found"));
	}
}
function downloadsalesinvoicelabel(){
	global $app;
	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('SalesLabels'));
		$SalesLabels = new SalesLabels();		
		if($labelData = $SalesLabels->loadByMd5($label_id)){
			$SalesLabels->updateDownloadCount();	
			echo json_encode(array("200", "success|Label Downloaded", $app->basePath($labelData['label_path']), $labelData['label_downloads']+1));
		}
		else{
			echo json_encode(array("300",  "warning|Sales Invoice not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Label not found"));
	}
}
function salescancellabel(){
	global $app;
	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('SalesLabels'));
		$SalesLabels = new SalesLabels();		
		if($labelData = $SalesLabels->loadByMd5($label_id)){
			$SalesLabels->update(array("label_status" => 0));
			Activity::add("Cancelled Label for", "S", $labelData['label_order_id']);
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