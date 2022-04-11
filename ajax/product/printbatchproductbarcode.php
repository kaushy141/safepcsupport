<?php

	$data = sanitizePostData($_POST);
	if(isset($data['filter']) && !empty($data['filter'])){
		$condition = "WHERE 1";
		foreach($data['filter'] as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					$condition .= " AND ( ";
					$conditionSubArray = array();
					foreach($values as $_val){
						$conditionSubArray[] = " `$filed` = '".sanitizePostData($_val)."'";
					}
					$condition .= implode(" OR ", $conditionSubArray);
					$condition .= " ) ";
				}
				else
					$condition .= " AND `$filed` = '".sanitizePostData($values)."'";
			}
		$batchProduct = new BatchProduct();
		if(isset($_SESSION['BATCH-PROD']['PRINT']))
			unset($_SESSION['BATCH-PROD']['PRINT']);
		if($product_codes = $batchProduct->getPrintProductCode($condition)){
			$time = time();
			Activity::add("Printed Batch Product barcodes");
			$_SESSION['BATCH-PROD']['PRINT'][md5($time)] = $product_codes;
			echo json_encode(array("200", "success|Product label print intilized", md5($time)));
		}
		else
			echo json_encode(array("300",  "danger|Products not available to print label."));
	}
	else
	echo json_encode(array("300",  "danger|Please apply filter."));

?>