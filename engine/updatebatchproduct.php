<?php
if(isset($parameter1))
{
	$product_id = $parameter1;
	$batchProduct = new BatchProduct($product_id);
	$data = $batchProduct->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"product/savebatchproductrecord";
		$formHeading	=	"Update Product #$data[product_code]";
		$btnText	=	"Save Product";
		include("engine/inc/addbatchproductform.php");
	}
	else
		include("engine/404.php");
}
?>