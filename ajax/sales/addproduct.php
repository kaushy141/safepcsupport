<?php

    $product_id     = $product_hardware_id = $product_price = $product_inc_vat = 0;
	$product_quantity = 1;
    $product_status = 1;
    $product_name   = "";
    $data           = sanitizePostData($_POST);
    $product        = new Product(0);
    extract($data);
	if($product_inc_vat == 0)
	{
	   $product_price = $product_price - round((($product_price * SALES_VAT_PERCENTAGE) / 100),2);
	   $product_inc_vat = 1;
	}
    if (!$product->isproductexist($product_name)) {
        $product_id = $product->add($product_name, $product_hardware_id, $product_quantity, $product_price, $product_inc_vat, $product_status);
        echo json_encode(array("200",  "success|Product Added Successfully"
        ));
    } else
        echo json_encode(array("200",  "warning|Product with same name allready exist"
        ));

?>