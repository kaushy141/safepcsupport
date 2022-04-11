<?php
$lot_id = 0;
	$data  = sanitizePostData($_POST);	
	extract($data);	
	Modal::load(array('Lot'));	
	$lot = new Lot($lot_id);
	if($lot->isExist()){
		$_SESSION['LOT']['PRODUCT'] = $lot->getProducts();
		$_SESSION['LOT']['ID'] = $lot_id;
		echo json_encode(array("200",  "success|Lot loaded to add products", count($_SESSION['LOT']['PRODUCT']), $lot_id));
	}
	else{
		echo json_encode(array("300",  "warning|No Lot found."));
	}

?>