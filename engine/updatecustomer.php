<?php
if(isset($parameter1))
{
	$customer_id = $parameter1;
	$customer = new Customer($customer_id);
	$data = $customer->load();
	if($data)
	{
		$consolidate = $customer->getConsolidateRecord();
		$addresses = CustomerAddress::getCustomerAddress($customer_id);
		extract($data);
		$customer_image = "<img src='".$app->imagePath($customer_image)."' height='34px' width='34px' />";
		$action	=	"customer/savecustomer";
		$formHeading	=	"Update Customer #$data[customer_fname] $data[customer_lname] ";
		$btnText	=	"UPDATE";
		include("engine/inc/addcustomerform.php");
	}
	else
		include("engine/404.php");
}
?>