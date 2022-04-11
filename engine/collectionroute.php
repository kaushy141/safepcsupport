<?php
admin();
if(isset($parameter1))
{
	$wc_id = $parameter1;
	$collection = new Collection($wc_id);
	$data = $collection->load();	
	if($data)
	{
		extract($data);
		$wc_collection_destination = $customer_full_address;
		$wc_collection_source = trim(substr($carrier_address,0,strpos($carrier_address,"Tel")));
		$action	=	"collectionroute";
		$formHeading	=	"Get Collection #$wc_code Route";
		$btnText	=	"Get Route";
		
		
		include("engine/inc/collectionroutefiles.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>