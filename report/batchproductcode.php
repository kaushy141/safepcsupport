<?php
$batchProduct = new BatchProduct($_REQUEST['id']);
if($item = $batchProduct->getDetails())
{	
	$barcode = new Barcode();
	//$barcode->addAssetCode($item['product_code'], $item['product_type']." / ".$item['product_processor']." / ".$item['product_model']." / ".$item['product_screen_size']."\"", $item['product_release']." / ".$item['product_serial_number']." / ".$item['product_ram']." / ".($item['product_ssd'] ? $item['product_ssd']."-SSD" : $item['product_hdd']."-HDD"));
	$heading = trim($item['product_type']) !="" ? trim($item['product_type'])." / " : "";
	$heading .= trim($item['product_processor']) !="" ? trim($item['product_processor'])." / " : "";
	$heading .= trim($item['product_model']) !="" ? trim($item['product_model'])." / " : "";
	$heading .= trim($item['product_screen_size']) !="" ? trim($item['product_screen_size'])."\" / " : "";
	$heading = rtrim(trim($heading), "/");
	
	$subheading = trim($item['product_release']) !="" ? trim($item['product_release'])." / " : "";
	$subheading .= trim($item['product_serial_number']) !="" ? trim($item['product_serial_number'])." / " : "";
	$subheading .= trim($item['product_processor_speed']) !="" ? trim($item['product_processor_speed'])."Ghz / " : "";
	$subheading .= trim($item['product_ram']) !="" ? trim($item['product_ram'])." / " : "";
	$subheading .= intval(trim($item['product_ssd'])) != 0 ? trim($item['product_ssd'])."-SSD" : "";
	$subheading .= intval(trim($item['product_hdd'])) != 0 ? trim($item['product_hdd'])."-HDD" : "";
	$subheading = substr(rtrim(trim($subheading), "/"), 0, 50);
	$barcode->addAssetCode($item['product_code'], $heading, $subheading);
	
	$barcode->printBarcode();
}
?>