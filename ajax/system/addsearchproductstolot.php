<?php
	$products = array();
	$data  = sanitizePostData($_POST);
	extract($data);	
	if(!count($products))
	{
		echo json_encode(array("300",  "warning|No product found."));
		die;
	}
	if(!isset($_SESSION['LOT'])){
		$_SESSION['LOT'] = array();
		$_SESSION['LOT']['PRODUCT'] = array();				
	}
	
	if(!isset($_SESSION['LOT']['PRODUCT']))
		$_SESSION['LOT']['PRODUCT'] = array();
	
	foreach($products as $_product){
		$_SESSION['LOT']['PRODUCT'][] = $_product;
	}
	echo json_encode(array("200", "success|Products added to current lot", count($_SESSION['LOT']['PRODUCT'])));

?>