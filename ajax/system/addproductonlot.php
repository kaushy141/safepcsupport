<?php

	$data  = sanitizePostData($_POST);
	extract($data);
	if(!isset($_SESSION['LOT'])){
		$_SESSION['LOT'] = array();
		$_SESSION['LOT']['PRODUCT'] = array();				
	}
	
	if(!isset($_SESSION['LOT']['PRODUCT']))
		$_SESSION['LOT']['PRODUCT'] = array();
	if(!in_array($product, $_SESSION['LOT']['PRODUCT'])){
		$_SESSION['LOT']['PRODUCT'][] = $product;
		echo json_encode(array("200", "success|Product added to lot", count($_SESSION['LOT']['PRODUCT'])));
	}
	else{
		echo json_encode(array("200", "danger|Product already in lot", count($_SESSION['LOT']['PRODUCT'])));
	}

?>