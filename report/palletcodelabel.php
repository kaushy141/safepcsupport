<?php
if(isset($_GET['id'])){
	$pallet = new Pallet($_GET['id']);
	if($pallet->isExist())
	{
		$record = $pallet->getDetails();
		$barcode = new Barcode();
		$barcode->addAssetCode($record['pallet_code'], $record['pallet_name'], $record['pallet_location'].' - '.$record['pallet_type']);
		$barcode->printBarcode();
	}
}
?>