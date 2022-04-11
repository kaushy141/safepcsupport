<?php
	$duplicate_customer_email_id = "";
	$data = sanitizePostData($_POST);
	extract($data);		
	if($duplicate_customer_email_id != "")
	{
		$customer = new Customer();
		if($new_customer_id = $customer->getIdByEmail(md5($duplicate_customer_email_id)))
		{
			$customerAddress = new CustomerAddress();
			$addressList = $customerAddress->getCustomerAddress($new_customer_id);
			echo json_encode(array("200",  "success|Customer address loaded", $addressList));
		}
		else
			echo json_encode(array("200", "danger|No Address found with this email address.", array()));
	}
	else
		echo json_encode(array("300", "danger|Not a valid commission."));

?>