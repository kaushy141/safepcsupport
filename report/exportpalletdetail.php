<?php
if(isset($_GET['id'])){
	$filename = "pallet_items_" . date('Y-m-d-H-i-s') . ".csv";
	$palletItems = new PalletItems($_GET['id']);
	$palletProductsItems = $palletItems->getPalletItemExport($_GET['id']);
	$f = fopen('php://memory', 'w');
	$delimiter = ",";
	$itemsArray = array();
	if(count($palletProductsItems))
	{
		foreach($palletProductsItems as $item){	
			if(!in_array($item['Item'], $itemsArray))	{
				array_push($itemsArray, $item['Item']);
				$fields = array_keys($item);
				fputcsv($f, $fields, $delimiter);
			}
			fputcsv($f, array_values($item), $delimiter);
		}
	}
	fseek($f, 0);
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
	fpassthru($f);
}
?>