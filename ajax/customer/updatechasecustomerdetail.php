<?php
	Modal::load(array('ChaseCustomer'));
	$customer_id = $chase_customer_is_prime = 0;
	$chase_customer_name = $chase_customer_email = $chase_customer_type = $chase_customer_contact = $chase_customer_company = $chase_customer_address = $chase_customer_country = $chase_customer_about = $chase_customer_joining_date = $chase_customer_created_date = $chase_customer_last_email_on = $chase_customer_last_call_on = $chase_customer_last_time = $chase_customer_schedule_date =  $chase_customer_status = "";
	$data           = sanitizePostData($_POST);
    extract($data);
	$chaseCustomer = new ChaseCustomer($customer_id);
	if($chaseCustomer->isChaseCustomerExist($chase_customer_email, $customer_id)){
		echo json_encode(array("300",  "warning|Email already exist with another customer"));
		die;
	}
	
	$updateData = array( 
		'chase_customer_name' => $chase_customer_name,  
		'chase_customer_email' => $chase_customer_email,  
		'chase_customer_contact' => $chase_customer_contact,  
		'chase_customer_company' => $chase_customer_company,  
		'chase_customer_job_level' => $chase_customer_job_level, 
		'chase_customer_type' => $chase_customer_type,
		'chase_customer_job_title' => $chase_customer_job_title,
		'chase_customer_job_function' => $chase_customer_job_function,
		'chase_customer_job_department' => $chase_customer_job_department,
		'chase_customer_job_division' => $chase_customer_job_division,
		'chase_customer_job_sic2_code' => $chase_customer_job_sic2_code,
		'chase_customer_job_sic2_details' => $chase_customer_job_sic2_details,
		'chase_customer_sic4_code' => $chase_customer_sic4_code,
		'chase_customer_job_sic4_details' => $chase_customer_job_sic4_details,		
		'chase_customer_byd_industries' => $chase_customer_byd_industries,		
		
		'chase_customer_address' => $chase_customer_address, 

		'chase_customer_city' => $chase_customer_city,
		'chase_customer_state' => $chase_customer_state,
		'chase_customer_zipcode' => $chase_customer_zipcode,
		'chase_customer_employee' => $chase_customer_employee,
		'chase_customer_revenue' => $chase_customer_revenue,
		'chase_customer_founded' => $chase_customer_founded,
		'chase_customer_company_type' => $chase_customer_company_type,
		'chase_customer_fax' => $chase_customer_fax,
		'chase_customer_website' => $chase_customer_website,
		
		'chase_customer_country' => $chase_customer_country,  
		'chase_customer_about' => $chase_customer_about,  
		'chase_customer_joining_date' => $chase_customer_joining_date == "" ? "NULL" : $chase_customer_joining_date, 
		'chase_customer_updated_date' => 'NOW()',  
		'chase_customer_updated_by' => getLoginId(),
		'chase_customer_last_email_on' => $chase_customer_last_email_on == "" ? "NULL" : $chase_customer_last_email_on,   
		'chase_customer_last_call_on' => $chase_customer_last_call_on == "" ? "NULL" : $chase_customer_last_call_on,  
		'chase_customer_last_time' => $chase_customer_last_time == "" ? "NULL" : $chase_customer_last_time,   
		'chase_customer_schedule_date' => $chase_customer_schedule_date == "" ? "NULL" : $chase_customer_schedule_date,  
		'chase_customer_is_prime' => $chase_customer_is_prime,  
		'chase_customer_status' => $chase_customer_status,
		
	);
	if($customer_id > 0){
		$chaseCustomer->update($updateData);
		echo json_encode(array("200",  "success|Chase Customer updated Successfully", $customer_id));
	}
	else{
		$customer_id = $chaseCustomer->insert($updateData);
		echo json_encode(array("200",  "success|Chase Customer created Successfully", $customer_id));
	}

?>