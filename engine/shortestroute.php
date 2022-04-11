<?php
admin();	
$action	=	"shortestroute";
$collection = new Collection(0);
$collectionData = $collection->getTodayCollectionRoute(date("Y-m-d")); 
//$collectionData = $collection->getTodayCollectionRoute("2018-11-06"); 
if($collectionData)
{
	$wayPoints = $wayPointsName = $wayPointsGeoName = array();
	foreach($collectionData as $colData)
	{
		$wayPoints[] = "'".$colData['customer_full_address']."'";
		$wayPointsName[] = $colData['customer_full_address'];
		$wayPointsGeoName[] = array(
										"customer_name"=>$colData['customer_name'],
										"customer_image"=>$colData['customer_image'],
										"geo_location"=>$colData['customer_address_geo_location'],
										"customer_address"=>$colData['customer_full_address'],
										"collection_code"=>$colData['wc_code'],
									);
	}
	$appInfo = new AppInfo();
	$appData = $appInfo->getDetails();
	$route_origin_point 		= $appData['info_app_address'];
	$route_destination_point 	= $appData['info_app_address'];
	$formHeading	=	"Today's Shortest Routs";
	include("engine/inc/shortestroutefiles.php");
}
?>