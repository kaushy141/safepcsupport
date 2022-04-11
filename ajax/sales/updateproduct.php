<?php

    $product_id     = $product_hardware_id = $product_price = $product_id = $product_inc_vat = 0;
    $product_status = 1;
	$product_quantity = 1;
    $product_name   = "";
    $data           = sanitizePostData($_POST);
    extract($data);
    $product        = new Product($product_id);
	if($product_inc_vat == 0)
	{
	   $product_price = $product_price - round((($product_price * SALES_VAT_PERCENTAGE) / 100),2);
	   $product_inc_vat = 1;
	}
	
    if (!$product->isproductexist($product_name, $product_id)) {
        $product->update(array(
								"product_name"=>$product_name, 
								"product_hardware_id"=>$product_hardware_id, 
								"product_price"=>$product_price,
								"product_inc_vat"=>$product_inc_vat,
								"product_quantity"=>$product_quantity
								)
						);
        echo json_encode(array("200",  "success|Product Updated Successfully"
        ));
    } else
        echo json_encode(array("200",  "warning|Product with same name allready exist"
        ));

?>