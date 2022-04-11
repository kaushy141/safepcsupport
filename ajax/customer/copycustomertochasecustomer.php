<?php
Modal::load(array('ChaseCustomer'));
	$customer_id = 0;
    $data           = sanitizePostData($_POST);
    extract($data);    
    $customer       = new Customer($customer_id);
	if($customer->isExist())
	{
		$customerData  = $customer->getDetails();
		$chaseCustomer = new ChaseCustomer();
		if($chaseCustomer->isChaseCustomerExist($customerData['customer_email'])){
			echo json_encode(array("300",  "warning|Email already exist with another customer"));
			die;
		}
		//`customer_code`, `customer_fname`, `customer_lname`, `customer_email`, `customer_phone`, `customer_company`, `customer_type_id`, `customer_image`, `customer_tax_number`, `customer_status`, `customer_password`, `customer_remark`, `customer_created_date`, `customer_created_by`, `customer_is_mobile_verified`, `customer_is_email_verified`, `customer_is_live`, `customer_fcm_token`, `customer_trade_licence` 
		$updateData = array( 
			'chase_customer_name' => $customerData['customer_fname'].' '.$customerData['customer_lname'],  
			'chase_customer_email' => $customerData['customer_email'],  
			'chase_customer_contact' => $customerData['customer_phone'],  
			'chase_customer_company' => $customerData['customer_phone'], 
			'chase_customer_type' => $customerData['customer_type_name'],	
			'chase_customer_about' => 'Customer added by '.$_SESSION['user_name'].' from regular customer list',  
			'chase_customer_joining_date' => $customerData['customer_created_date'], 
			'chase_customer_updated_date' => 'NOW()',  
			'chase_customer_updated_by' => getLoginId(),  
			'chase_customer_status' => ChaseCustomer::$CHASE_CUSTOMER_ACTIVE,
			
		);
		$customerAddress = new CustomerAddress(0);
		$customerAddressArray = $customerAddress->getCustomerAddress($customer_id);
		if($customerAddressArray){
			$customerAddressData = $customerAddressArray[0];
			$updateData['chase_customer_address'] = $customerAddressData['customer_address_street_number'] .' '.$customerAddressData['customer_address_route'] ;
			$updateData['chase_customer_city'] = $customerAddressData['customer_address_locality'];
			$updateData['chase_customer_state'] = $customerAddressData['customer_address_administrative_area'];
			$updateData['chase_customer_zipcode'] = $customerAddressData['customer_address_postcode'];
			$updateData['chase_customer_country'] = $customerAddressData['customer_address_country'];
		}
		$chase_customer_id = $chaseCustomer->insert($updateData);
		Activity::add("add new|^|{$customerData['chase_customer_name']}", "H", $chase_customer_id);
		echo json_encode(array("200",  "success|Customer Details copied to chase customer Successfully"));
	}
	else
	{
		echo json_encode(array("300",  "success|Requested customer not found"));
	}

?>