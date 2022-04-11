<?php
if(isset($_SESSION['COLL-PROD']['PRINT'][$_REQUEST['id']])){
	$collProductsItems = $_SESSION['COLL-PROD']['PRINT'][$_REQUEST['id']];
	$barcode = new Barcode();
	if(count($collProductsItems))
	{
		foreach($collProductsItems as $item)		
		$barcode->addAssetCode($item["code"], $item["title"], $item["subtitle"]);
	}
	$barcode->printBarcode();
}
?>