<?php
	$columns = array();
	$data = sanitizePostData($_POST);
	if(isset($data['filter']) && !empty($data['filter'])){
		
		$condition = "WHERE 1";				
		foreach($data['filter'] as $filed=>$values){
			if(is_array($values)){
				$condition .= " AND ( ";
				$conditionSubArray = array();
				foreach($values as $_val){
					$_val_arr = explode("|||", $_val);
					$_val = $_val_arr[0];
					$operator = isset($_val_arr[1]) ? $_val_arr[1] : "=";
					$conditionSubArray[] = getConditionFormatString($filed, $operator, $_val);
				}
				$condition .= implode(" OR ", $conditionSubArray);
				$condition .= " ) ";
			}
			else{
				$values_arr = explode("|||", $values);
				$values = $values_arr[0];
				$operator = isset($values_arr[1]) ? $values_arr[1] : "=";
				$condition .= " AND ".getConditionFormatString($filed, $operator, $values);
			}
			
		}
		$batchProduct = new BatchProduct();
		if(isset($_SESSION['BATCH-PROD']['EXPORT']))
			unset($_SESSION['BATCH-PROD']['EXPORT']);
		if($sql = $batchProduct->getExportProductSql($condition, $data['columns'])){
			$time = time();
			Activity::add("Exported Batch Products list");
			$_SESSION['BATCH-PROD']['EXPORT'][md5($time)]['sql'] = $sql;
			$_SESSION['BATCH-PROD']['EXPORT'][md5($time)]['col'] = $data['columns'];
			echo json_encode(array("200", "success|Product excel export intilized", md5($time), $_SESSION['BATCH-PROD']['EXPORT'][md5($time)]));
		}
		else
			echo json_encode(array("300",  "danger|Products not available to export."));
	}
	else
	echo json_encode(array("300",  "danger|Please apply filter."));

?>