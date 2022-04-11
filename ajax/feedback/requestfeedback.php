<?php


    $feedback_customer_id = $feedback_module_id = 0;
    $feedback_module_code = "";
    $feedback_store       = DEFAULT_FEEDBACK_STORE;
    $feedback_status      = 1;
    $data                 = sanitizePostData($_POST);
    extract($data);
    $customerData = array();
    if ($feedback_customer_id != 0) {
        $customer     = new Customer($feedback_customer_id);
        $customerData = $customer->getDetails();
    }
	if(!$customerData)
	{
		echo json_encode(array("300", "danger|Customer not found"
        ));
		exit();
	}
    $moduleData = array();
    if ($feedback_module_id != 0) {
		if (!$customerData) {
            $customer     = new Customer($feedback_customer_id);
            $customerData = $customer->getDetails();
        }
		
        if ($feedback_module_code == "C") {
            $complaint            = new Complaint($feedback_module_id);
            $moduleData           = $complaint->getDetails();
            $feedback_customer_id = $moduleData['complaint_customer_id'];
			$module_section_name  = "Repair";
			$module_section_code  = $moduleData['complaint_ticket_number'];
        }
        if ($feedback_module_code == "W") {
            $collection           = new Collection($feedback_module_id);
            $moduleData           = $collection->getDetails();
            $feedback_customer_id = $moduleData['wc_customer_id'];
			$module_section_name  = "Collection";
			$module_section_code  = $moduleData['wc_code'];
        }
		if ($feedback_module_code == "S") {
            $salesinvoice         = new SalesInvoice($feedback_module_id);
            $moduleData           = $salesinvoice->getDetails();
            $feedback_customer_id = $moduleData['sales_invoice_customer_id'];
			$module_section_name  = "Sales";
			$module_section_code  = $moduleData['sales_invoice_number'];
        }
		if ($feedback_module_code == "O") {
            $websiteOrder         = new WebsiteOrder($feedback_module_id);
            $moduleData           = $websiteOrder->getDetails();
            $feedback_customer_id = $moduleData['web_order_customer_id'];
			$module_section_name  = "Website Order";
			$module_section_code  = $moduleData['web_order_number'];
        }
		if ($feedback_module_code == "U") {
			$moduleData = $customerData;
            $feedback_customer_id = $moduleData['customer_id'];
			$module_section_name  = "Customer";
			$module_section_code  = $moduleData['customer_name'];
        }
        
    }
	$feedback          = new Feedback();
	$submited_feedback = $feedback->getSendedFeedback($feedback_customer_id, $feedback_module_id, $feedback_module_code);
	if($submited_feedback !=NULL && in_array($feedback_store, $submited_feedback))
	{
		echo json_encode(array("300", "danger|Feedback allready requested for this store. Multiple request for same job not allowed"
        ));
		exit();
	}
    if ($customerData && $moduleData) {
        
        $feedback_id       = $feedback->add($feedback_customer_id, $feedback_module_id, $feedback_module_code, $feedback_status, $feedback_store);
        $feedback_hashcode = strtoupper("FD" . "-" . md5($feedback_id) . "-" . md5($feedback_customer_id) . "-" . $feedback_module_code . "-" . md5($feedback_module_id));
        $feedback_link     = $app->basePath("feedback.php?feedback_hashcode=$feedback_hashcode&aouthv1=" . strtoupper(md5(strtoupper($feedback_hashcode))) . "&likes");
        $store             = new Store();
        if ($storeData = $store->getDetailsByKey($feedback_store)) {
            $store_google_feedback_url     = $storeData['store_google_feedback_url'];
            $store_trustpilot_feedback_url = $storeData['store_trustpilot_feedback_url'];
            $store_facebook_feedback_url   = $storeData['store_facebook_feedback_url'];
			$store_logo_url   = $app->imagePath($storeData['store_logo']);
        }
        $data = array(
            "customer_name" => $customerData['customer_name'],
            "feedback_link" => $feedback_link,
            "message" => "",
            "store_google_feedback_url" => $store_google_feedback_url,
            "store_trustpilot_feedback_url" => $store_trustpilot_feedback_url,
            "store_facebook_feedback_url" => $store_facebook_feedback_url,
			"module_section_name" =>$module_section_name,
			"module_section_code" => $module_section_name,
			"store_logo_url"=>$store_logo_url
        );
        Activity::add("requested $module_section_name <b>#$module_section_code</b>", "F", $feedback_customer_id);
        $email = new Email($app->siteName . " : " . "Feedback for $module_section_name #$module_section_code on  $storeData[store_name]");
		if($storeData)
		{
			$email->setEmailLogo($storeData['store_logo']);
			$email->setStoreName($storeData['store_title']);
		}
        $email->to($customerData['customer_email'], $customerData['customer_name'], $customerData['customer_image']);
        $email->template("customerfeedback", $data);
        $email->send("Thankyou for giving us chance for service on " . $app->siteName);
        if ($store_google_feedback_url == "")
            echo json_encode(array("300", "warning|Google Feedback Url is not available. Application Feedback Url send",
                $_POST
            ));
        else
            echo json_encode(array("200",  "success|Feedback request for $module_section_name #$module_section_code submitted Successfully",
                $_POST
            ));
    } else
        echo json_encode(array("300", "warning|Couldn't send feedback Request."
        ));

?>