<?php

    $key  = "";
    $data           = sanitizePostData($_POST);
	$databaseColumNameArray = array(
		'Sku' => 'product_sku',
		'Serial' => 'product_serial_number'
	);
    extract($data);
	if($key != "" && strlen($key) == 32){
		if(isset($_SESSION['BATCH-PROD'][$key]))
		{
			$count = count($_SESSION['BATCH-PROD'][$key]);
			if(isset($_SESSION['BATCH-PROD'][$key]) && $count){
				
				$batchProduct = new BatchProduct();
				$_SESSION['BATCH-PROD'][$key]  = sanitizePostData($_SESSION['BATCH-PROD'][$key]);
				foreach($_SESSION['BATCH-PROD'][$key] as $product){
					$product['product_code'] = $batchProduct->getBatchProductCode();
					$product['product_created_by'] = getLoginId();
					$batchProduct->insert($product);
				}
				unset($_SESSION['BATCH-PROD'][$key]);
				Activity::add("Imported $count batch product successfully");
				echo json_encode(array("200", "success|$count Product imported successfully"));
			}
			else
				echo json_encode(array("300",  "danger|Products not available."));
		}
		elseif(isset($_SESSION['BATCH-PROD-UPDATE'][$key]))
		{
			$count = count($_SESSION['BATCH-PROD-UPDATE'][$key]);
			if(isset($_SESSION['BATCH-PROD-UPDATE'][$key]) && $count){
				
				$_SESSION['BATCH-PROD_UPDATE'][$key]  = sanitizePostData($_SESSION['BATCH-PROD-UPDATE'][$key]);
				//$data = $_SESSION['BATCH-PROD-UPDATE'][$key];
				foreach($_SESSION['BATCH-PROD-UPDATE'][$key] as $product){
					if(isset($product[$_SESSION['BATCH-PROD-UPDATE']['COLUMN']]))
					{					
						$batchProduct = new BatchProduct();
						$batchProduct->loadByColumn($_SESSION['BATCH-PROD-UPDATE']['COLUMN'], $product[$_SESSION['BATCH-PROD-UPDATE']['COLUMN']]);
						$batchProduct->update($product);
					}
				}
				unset($_SESSION['BATCH-PROD'][$key]);
				Activity::add("Imported $count batch product successfully");
				echo json_encode(array("200", "success|$count Product updated successfully"));
			}
			else
				echo json_encode(array("300",  "danger|Products not available."));
		}
	}
	else
		echo json_encode(array("300",  "danger|Security key missmatch."));

?>