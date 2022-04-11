<?php
	$customer = new Customer(0);
    echo json_encode(array("200",  "success|Chat Customer Loaded Successfully",
        $customer->getChatCustomer()
    ));

?>