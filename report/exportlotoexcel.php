<?php
if(isset($_REQUEST['id']))
{	
	Modal::load(array('Lot'));
	$collectionProcessItem = new CollectionProcessItem();
	$batchProduct = new BatchProduct();
	$lot = new Lot($_REQUEST['id']);
	if($lot->isExist()){
		$lotData = $lot->getDetails();
		$products = $lot->getProducts();
		$collectionProducts = $collectionProcessItem->getCollectionItemsByIds($products);
		$batchProducts = $batchProduct->getBatchProductItemsByIds($products);		
	}
	$filename = "lot_$lotData[lot_code]_product_" . date('Y-m-d-H-i-s') . ".csv";		
	$f = fopen('php://memory', 'w');
	$delimiter = ",";
		
	if(count($collectionProducts) || count($batchProducts))
	{
		$complaintLog = new ComplaintLog();
		if(count($collectionProducts))
		{
			fputcsv($f, array("Collection Product"), $delimiter);			
			$collectionItems = array();
			foreach($collectionProducts as $item)
			{
				$comments = '';
				if($logs = $complaintLog->getLog($item['wc_process_asset_code'], 'P')){
					foreach($logs as $_log){
						$comments .= "$_log[complaint_log_text]\n";
					}
				}
				$item['comments'] = trim($comments, "\n");
				unset($item['wc_process_id']);
				if(!in_array($item['Item'], $collectionItems)){							
					$collectionItems[] = $item['Item'];
					fputcsv($f, array_keys($item), $delimiter);
				}
				fputcsv($f, array_values($item), $delimiter);
			}			
		}
		
		if(count($batchProducts))
		{
			fputcsv($f, array(), $delimiter);
			fputcsv($f, array("Batch Product"), $delimiter);
			$labelArray = array(
			"product_code" => "Code", 
			"product_order_number" => "Order No", 
			"product_reg_id" => "Reg.Id", 
			"product_type" => "Type", 
			"product_name" => "Name", 
			"product_serial_number" => "Sr.No.", 
			"product_sku" => "SKU", 
			"product_model" => "Model", 
			"product_condition" => "Condition", 
			"product_processor" => "Processor", 
			"product_processor_speed" => "Processor Speed", 
			"product_screen_size" => "Screen Size", 
			"product_ram" => "RAM", 
			"product_ssd" => "SSD", 
			"product_hdd" => "HDD", 
			"product_fusion_drive" => "Fusion Drice", 
			"product_release" => "Release Year", 
			"product_reason" => "Reason", 
			"product_battery_cycle" => "Battery Cycle", 
			"product_operating_system" => "Operating System", 
			"product_grade" => "Grade", 
			"product_batch_type" => "Batch Type", 
			"product_batch_code" => "Batch Code", 
			"product_in_stock" => "In Stock", 
			"product_under_technician" => "Under Technician"
			);
			$i = 0;
			foreach($batchProducts as $item)
			{
				$comments = '';
				if($logs = $complaintLog->getLog($item['product_id'], 'B')){
					foreach($logs as $_log){
						$comments .= "$_log[complaint_log_text]\n";
					}
				}
				$item['product_in_stock'] = $item['product_in_stock'] ? "Yes":"No";
				$item['product_under_technician'] = $item['product_under_technician'] ? "Yes":"No";
				$item['comments'] = trim($comments, "\n");
				unset($item['product_id']);
				if(++$i == 1){
					$keyLabels = array();
					foreach(array_keys($item) as $keyValue){
						$keyLabels[] = isset($labelArray[$keyValue]) ? $labelArray[$keyValue] : $keyValue;
					}
					fputcsv($f, $keyLabels, $delimiter);
				}
				fputcsv($f, array_values($item), $delimiter);
			}
		}		
	}
	fseek($f, 0);
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
	fpassthru($f);
}
?>