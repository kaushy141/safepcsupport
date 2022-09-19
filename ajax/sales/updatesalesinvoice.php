<?php    $customer_fname              = $customer_lname = $customer_email = $customer_phone =  $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $sales_invoice_manufacturer = "";
    $sales_product_name          = $sales_product_price = $sales_product_quantity = $sales_product_type_id = $sales_purchase_amount = $sales_purchase_source = array();
    $customer_type_id            = $sales_invoice_store_id = $send_invoice_to_customer = $sales_invoice_customer_id = $sales_invoice_customer_address_id = $sales_invoice_is_paid = $sales_invoice_is_estimate = $sales_invoice_is_vat_applicable = $sales_invoice_status = $sales_discount_amount = $sales_invoice_is_generated = $sales_payment_paid_amount = $sales_invoice_margin_vat_label = $sales_invoice_bank_account = 0;
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
		"sales_invoice_date" => $sales_invoice_date,
		"sales_invoice_is_estimate" => $sales_invoice_is_estimate,
		"sales_invoice_is_vat_applicable" => $sales_invoice_is_vat_applicable,
		"sales_discount_amount" => $sales_discount_amount,
		"sales_invoice_manufacturer" => $sales_invoice_manufacturer,
		"sales_invoice_status" => $sales_invoice_status,
		"sales_invoice_is_generated" => $sales_invoice_is_generated,
		"sales_invoice_vat" => $sales_invoice_vat,	
		"sales_invoice_bank_account" => $sales_invoice_bank_account,
		"sales_invoice_currency" => $sales_invoice_currency,
		"sales_invoice_dispatched_store" => $sales_invoice_dispatched_store,
		"sales_payment_paid_amount" => $sales_payment_paid_amount,
		"sales_invoice_is_dummy" => $sales_invoice_is_dummy,
		"sales_invoice_margin_vat_label" => $sales_invoice_margin_vat_label,
		"sales_invoice_last_updated_by" => getLoginId(),
		"sales_invoice_last_updated_on" => 'NOW()',
    ));
	
	if($prevData['sales_invoice_is_paid'] == 0 && $sales_invoice_is_paid == 1){
		$SalesInvoice->update(
								array(
									"sales_invoice_mark_paid_by" => getLoginId(),
									"sales_invoice_mark_paid_on" => 'NOW()'
									)
							);
	}
		
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
			$_sales_purchase_amount 	= $sales_purchase_amount[$index];
			$_sales_purchase_source 	= $sales_purchase_source[$index];
			if($_sales_product_quantity>0)
			{
				$product = new Product(0);
				if(!$sipd_product_id = $product->isHardwareProductExist($_sales_product_name, $_sales_product_type_id)){
					$sipd_product_id = $product->add($_sales_product_name, $_sales_product_type_id, $_sales_product_price, $sales_invoice_is_vat_applicable, 1);	
				}
				//echo $sipd_product_id."<br/>";
				if(in_array($sipd_product_id, $invoiceSavedProductsArray))
				{					
					$SalesInvoice->updateProduct($sipd_product_id, $_sales_product_quantity, $_sales_product_sku, $_sales_product_batch_code, $_sales_product_price, $_sales_purchase_amount, $_sales_purchase_source);
					if (($key = array_search($sipd_product_id, $invoiceSavedProductsArray)) !== false) {
						unset($invoiceSavedProductsArray[$key]);
					}
				}
				else
					$SalesInvoice->addProducts($sales_invoice_id, $sipd_product_id, $_sales_product_quantity, $_sales_product_sku, $_sales_product_batch_code, $_sales_product_price, $_sales_purchase_amount, $_sales_purchase_source);				
			}
			else
				$isProductSkipped = true;			
		}
		//print_r($invoiceSavedProductsArray);
		if(!$prevData['sales_invoice_is_generated'] && count($invoiceSavedProductsArray)){
			foreach($invoiceSavedProductsArray as $sipd_product_id){
				$SalesInvoice->removeProduct($sipd_product_id);
			}				
		}		
		
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
		
		if($prevData['sales_invoice_is_paid'] == 0 && $sales_invoice_is_paid == 1){
		$SalesInvoice->update(
								array(
									"sales_payment_paid_amount" => $sales_invoice_total_amount - $sales_discount_amount
									)
							);
		}
		
		
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

?>