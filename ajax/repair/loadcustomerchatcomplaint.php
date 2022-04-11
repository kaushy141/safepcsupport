<?php

	$customer_id = 0;
    $data        = sanitizePostData($_POST);
    extract($data);
    if ($customer_id != 0) {
        $FcmManager        = new FcmManager();
        $fcmConId          = $FcmManager->setFCMCustomer($customer_id);
        $Complaint         = new Complaint($customer_id);
        $CustomerComplaint = $Complaint->getCustomerComplaint($customer_id);
        echo json_encode(array("200",  "success|User's Repair Request Loaded Successfully",
            $CustomerComplaint,
            $fcmConId
        ));
    } else
        echo json_encode(array("300", "warning|No Customer Found"
        ));

?>