<?php
	Modal::load(array('ChaseCustomer'));
	$keyword = $type = "";
	$data           = sanitizePostData($_POST);
    extract($data);
	$chaseCustomer = new ChaseCustomer(0);
	$specific_chase_customer = 0;
	if(isset($_POST['app_url']) && $keyword == ""){
		$appUrl = parse_url($_POST['app_url']);
		$path = explode("/", $appUrl['path']);
		$specific_chase_customer = isset($path[2]) ? intval($path[2]) : 0;
		$specific_chase_customer = $chaseCustomer->getCustomerIdByRecord($specific_chase_customer);
	}
    echo json_encode(array("200",  "success|Customer Loaded Successfully",
        $chaseCustomer->getChaseCustomer($keyword, $type, $specific_chase_customer), $specific_chase_customer
    ));

?>