<?php
if(isset($_SESSION['COLL-PROD']['EXPORT'][$_REQUEST['id']]))
{
	$filename = "product_collection_" . date('Y-m-d-H-i-s') . ".csv";
	$collProductsItems = $_SESSION['COLL-PROD']['EXPORT'][$_REQUEST['id']];
	$f = fopen('php://memory', 'w');
	$delimiter = ",";
	if(count($collProductsItems))
	{
		$counter = 0;
		foreach($collProductsItems as $item){	
			if($counter++ == 0)	{
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