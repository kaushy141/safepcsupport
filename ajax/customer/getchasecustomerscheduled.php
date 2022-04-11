<?php
	Modal::load(array('ChaseCustomer'));
	$data           = sanitizePostData($_POST);
    extract($data);
    echo json_encode(array("200",  "success|Scheduled customer loaded",
        ChaseCustomer::getScheduledCustomer()
    ));

?>