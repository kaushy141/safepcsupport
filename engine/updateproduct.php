<?php
if(isset($parameter1))
{
	$product_id = $parameter1;
	$product = new Product($product_id);
	$data = $product->load();
	if($data)
	{
		extract($data);
		$action	=	"sales/updateproduct";
		$formHeading	=	"Update Product #$data[product_id]";
		$btnText	=	"UPDATE";
		include("engine/inc/addproductform.php");
	}
	else
		include("engine/404.php");
}
?>