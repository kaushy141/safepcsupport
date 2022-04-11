<?php

	$searchKeyword = '';
	$data = sanitizePostData($_POST);
	if(isset($data['filter']) && !empty($data['filter'])){
		$filter = $data['filter'];
		$searchKeyword = $data['searchKeyword'];
		if(isset($filter['wc_process_item_id']))
		{
			$condition = "WHERE 1";
			foreach($filter as $field=>$values){
				if($field == 'attribute'){
					if(is_array($values)){
						
						foreach($values as $attribute_id => $_val){
							$subValues = array();
							$subcondition = "(`values_attribute_id`='".sanitizePostData($attribute_id)."' AND ";
							if(is_array($_val))
							{						
								foreach($_val as $_v)
								{
									$subValues[] = "`values_data` = '".sanitizePostData($_v)."'";
								}
							}else{
								$subValues[] = "`values_data` = '".sanitizePostData($_val)."'";
							}
							$conditionArray[] = $subcondition. "(".implode(" OR ", $subValues)."))";
						}						
					}
					else{
							$conditionArray[] = array("`values_attribute_id`", "=", sanitizePostData($attribute_id));
							$conditionArray[] = array("`values_data`", "=", sanitizePostData($values));
					}	
				}
				elseif($field != "")
				{
					$filedCondArray = array();
					if(is_array($values)){
						foreach($values as $_val)
							$filedCondArray[] = array($field, "=", sanitizePostData($_val));
					}
					else
						$filedCondArray[] = array($field, "=", sanitizePostData($values));
					$conditionArray[] = $filedCondArray;	
				}
			}
			
			//Sprint_r($conditionArray);
			
			$collectionProcess = new CollectionProcess();
			$collectionProcess->condition = $conditionArray;
			$condition = $collectionProcess->getCondition();
			
			$sqlParam = CollectionProcess::getFilTerJoinCondition($filter, $searchKeyword);
		
			if(isset($_SESSION['COLL-PROD']['PRINT']))
				unset($_SESSION['COLL-PROD']['PRINT']);
			
			if($items = collectionProcess::getFilteredPrintProductCode($sqlParam)){
				$time = time();
				$_SESSION['COLL-PROD']['PRINT'][md5($time)] = $items;
				echo json_encode(array("200", "success|Product print to csv intilized", md5($time), $items));
			}
			else
				echo json_encode(array("300",  "danger|Products not available to print label.", $condition));
		}
		else
			echo json_encode(array("300",  "danger|Product type filter reqired."));
	}
	else
	echo json_encode(array("300",  "danger|Please apply filter."));

?>