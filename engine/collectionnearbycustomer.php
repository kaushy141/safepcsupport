<?php
admin();
if (isset($parameter1)) {
	$wc_id = $parameter1;
	$collection = new Collection($wc_id);
	$data = $collection->load();
	$customer = new Customer($data['wc_customer_id']);
	$customerData = $customer->load();
	if ($data) {
		extract($customerData);
		extract($data);
		$wc_collection_destination = $customer_full_address;
		$wc_collection_source = trim(substr($carrier_address, 0, strpos($carrier_address, "Tel")));
		$action	=	"collectionnearbucustomer";
		$formHeading	=	"Get Collection #$wc_code Near by customer";
		$btnText	=	"Get Route";


		include("engine/inc/collectionnearbycustomerview.php");
	} else
		include("engine/404.php");
} else
	include("engine/404.php");
