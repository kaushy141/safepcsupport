<?php
function updatecustomerstatus()
{
    global $app;
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Customer      = new Customer($idvalue);
        $Customer_name = $Customer->get('customer_fname');
        $status ? $Customer->Activate() : $Customer->Deactivate();
        Activity::add(status($status) . " Customer <b>" . $Customer_name . "</b> staus");
        echo json_encode(array("200",  "success|Customer $Customer_name " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Customer " . status($status));
        $email->send("Customer \"" . $Customer_name . "\" " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Customer Status not found."
        ));
}

function updateustomerpassword()
{
    global $app;
    $old_password = $new_password = $conf_password = "";
    $data         = sanitizePostData($_POST);
    extract($data);
    if ($old_password != "" && $new_password != "" && $conf_password != "") {
        $length          = strlen($new_password);
        $approval_length = APP_PASSWORD_MIN_LENGTH;
        if ($length - $approval_length >= 0) {
            $cust          = new Customer($_SESSION['customer_id']);
            $customer      = $cust->getDetails();
            $cust_password = $customer["customer_password"];
            if ($cust_password == $old_password) {
                $cust->update(array("customer_password" => $new_password
                ));
                new SMS($customer['customer_phone'], "Hi, $customer[customer_fname] Your Password updated successfully.");
                Activity::add("changed Password");
                echo json_encode(array("200",  "success|Your Password updated successfully"
                ));
                $email = new Email($app->siteName . " : " . "Password Changed");
                $email->send("You have successfully chnaged your Password on " . $app->siteName . ". If you have not then contact to Administration immediately.");
            } else
                echo json_encode(array("300", "danger|Old Password not Matched."
                ));
        } else
            echo json_encode(array("300", "danger|Password must have atleast " . APP_PASSWORD_MIN_LENGTH . " Character"
            ));
    } else
        echo json_encode(array("300", "danger|Old password or New Password can't Blank"
        ));
}

function loadchatcustomer()
{
    $customer = new Customer(0);
    echo json_encode(array("200",  "success|Chat Customer Loaded Successfully",
        $customer->getChatCustomer()
    ));
}

function updatecustomer()
{
	global $app;
    $customer_fname = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $customer_remark = $customer_address_id = 0;
    $data           = sanitizePostData($_POST);
    extract($data);
    $customer_email = strtolower($customer_email);
    $Customer       = new Customer($customer_id);
	$customerOldData  = $Customer->getDetails();
    $Customer->update(array("customer_fname" => $customer_fname,
        "customer_lname" => $customer_lname,
        "customer_email" => $customer_email,
        "customer_phone" => $customer_phone,
        "customer_type_id" => $customer_type_id,
		"customer_company" => $customer_company,
		"customer_tax_number" => $customer_tax_number,
		"customer_remark" => $customer_remark
    ));
	
	if ($customerOldData['customer_email'] != $customer_email) {
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
        $email           = new Email("New Customer Account on " . $app->siteName);
        $email->to("$customer_email", $customer_fname)->template('customer_update', $dataArray)->send();
    }
	$customerAddressData = array("customer_id" => $customer_id,
            "customer_address_street_number" => $customer_address_street_number,
            "customer_address_route" => $customer_address_route,
            "customer_address_locality" => $customer_address_locality,
            "customer_address_administrative_area" => $customer_address_administrative_area,
            "customer_address_country" => $customer_address_country,
            "customer_address_postcode" => $customer_address_postcode,
            "customer_address_geo_location" => $customer_address_geo_location,
            "customer_address_status" => 1
        );
    $CustomerAddress = new CustomerAddress($customer_address_id);
    if ($customer_address_id != 0 || $customer_address_id = $CustomerAddress->isCustomerAddressExists($customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country)){
        $CustomerAddress->update($customerAddressData);
	}
	else{
		$customer_address_id = $CustomerAddress->insert(array_merge($customerAddressData, array("customer_address_created_date"=>"NOW()")));
	}
    if (isset($_SESSION['UPLOAD'][$field_handler]['PIC'])) {
        $image_name     = pathinfo($_SESSION['UPLOAD'][$field_handler]['PIC']);
        $extension      = strtolower($image_name['extension']);
        $customer_image = "upload/customer/customer_" . time() . "_" . $customer_id . ".$extension";
        if (rename($_SESSION['UPLOAD'][$field_handler]['PIC'], $customer_image)) {
            $Customer->update(array("customer_image" => $customer_image
            ));
        }
        unset($_SESSION['UPLOAD'][$field_handler]['PIC']);
    }
    Activity::add("updated Customer <b>$customer_fname $customer_lname</b> Information");
    echo json_encode(array("200",  "success|Customer Details Updated Successfully",
        $customer_id,
        $customer_address_id
    ));
}

function savecustomer()
{
	global $app;
    $customer_fname = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_tax_number = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = $customer_remark = '';
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
        if (rename($_SESSION['UPLOAD'][$field_handler]['PIC'], $customer_image)) {
            $Customer->update(array("customer_image" => $customer_image
            ));
        }
        unset($_SESSION['UPLOAD'][$field_handler]['PIC']);
    }
    Activity::add(($customerIsExist ? "Updated":"Added")." Customer <b>$customer_fname $customer_lname</b> Information");
    echo json_encode(array("200",  "success|Customer Details ".($customerIsExist ? "Updated":"Added")." Successfully",
        $customer_id,
        $customer_address_id
    ));
}


function iscustomeremailavailable(){
	global $app;
    $customer_email = "";
	$customer_id = 0;
    $data         = sanitizePostData($_POST);
    extract($data);
	$customer = new Customer($customer_id);
	if(!$customer->isEmailExists($customer_email, $customer_id == 0 ? NULL : $customer_id)){
		echo json_encode(array("200",  "success|Email id is available to use"));
	}
	else{
		echo json_encode(array("300",  "warning|Email id already exist"));
	}
}
?>