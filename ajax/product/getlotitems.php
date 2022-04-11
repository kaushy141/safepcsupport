<?php
	$lot_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($lot_id){
		Modal::load(array('Lot'));
		$lot = new Lot($lot_id);
		$lot_products = $lot->getProducts();
		if(isset($_SESSION['LOT']['PRODUCT']) && count($_SESSION['LOT']['PRODUCT']))
			$lot_products = array_merge($lot_products, $_SESSION['LOT']['PRODUCT']);
	}else{
		$lot_products = $_SESSION['LOT']['PRODUCT'];
	}
	$products = Product::getLotProducts($lot_products);
	if($products)
	{
		echo json_encode(array("200",  "success|Products loaded", $products));
	}
	else
		echo json_encode(array("300",  "warning|No product found.", $_SESSION));

?>