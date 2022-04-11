<?php
	$product_sku = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($product_sku)
	{
		$products = WebsiteOrderProduct::checkProductAvailability($product_sku, true);
		if($products)
		{
			echo json_encode(array("200",  "success|Products loaded", $products));
		}
		else
			echo json_encode(array("300",  "warning|No product found."));
	}
	else
		echo json_encode(array("300",  "warning|Invalid product SKU."));

?>