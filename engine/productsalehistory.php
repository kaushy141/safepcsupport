<?php
if(isset($parameter1))
{
	$bpca_product_id = $parameter1;
	$batchProduct = new BatchProduct($bpca_product_id);
	$data = $batchProduct->load();
	if($data)
	{
		extract($data);
		$action	=	"product/saveproductsalehistory";
		$formHeading	=	"Product #$data[product_code] Sale History";
		$btnText	=	"Save Record";
		include("engine/inc/productsalehistoryform.php");
	}
	else
		include("engine/404.php");
}
?>