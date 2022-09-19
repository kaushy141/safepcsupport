<?php
	$store_id = 0;
	$data = sanitizePostData($_POST);
    extract($data);
	$store = new Store($store_id);
	if($store->isExist())
	{
		echo json_encode(array("200",  "success|Accounts loaded Successfully", $store->getBankAccounts()));
	}
	else
		echo json_encode(array("300",  "warning|Sales Invoice record not found."));

?>