<?php
$code = isset($_GET['type']) ? $_GET['type'] : 0;
$CollectionItems = CollectionProcess::getPrintProcessCode($_GET['id'], $code);
$barcode = new Barcode();
if($CollectionItems)
{
	foreach($CollectionItems as $item)		
	$barcode->addAssetCode($item["code"], $item["title"], $item["subtitle"]);
}
$barcode->printBarcode();
?>