<?php

	$data = sanitizePostData($_POST);
	if(isset($data['filter']) && !empty($data['filter'])){
		$condition = "WHERE 1";
		foreach($data['filter'] as $field=>$values)
		{
			$filedCondArray = array();
			if($field == "duration"){
				if($values == "today")
				$filedCondArray[] = " DATE(a.web_order_created_date) = CURRENT_DATE ";
				elseif($values == "month")
				$filedCondArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ";
				elseif($values == "quarter")
				$filedCondArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) ";
				elseif($values == "halfyear")
				$filedCondArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) ";
				elseif($values == "year")
				$filedCondArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ";
				elseif($values == "custom")
				$filedCondArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
			}
			else
			{
				if(is_array($values)){
					foreach($values as $_val)
						$filedCondArray[] = array("a.".$field, "=", sanitizePostData($_val));
				}
				else
					$filedCondArray[] = array("a.".$field, "=", sanitizePostData($values));
			}
			$conditionArray[] = $filedCondArray;	
		}
		$conditionArray[] = array("a.web_order_status", "!=", 3);
		$conditionArray[] = array("a.web_order_status", "!=", 1);
		$websiteOrder = new WebsiteOrder();
		$websiteOrder->condition = $conditionArray;
		if(isset($_SESSION['WEB-ORDER']['EXPORT']))
			unset($_SESSION['WEB-ORDER']['EXPORT']);
		if($sql = $websiteOrder->getPendingOrderExportSql($websiteOrder->getCondition())){
			$time = time();
			$_SESSION['WEB-ORDER']['EXPORT'][md5($time)] = $sql;
			echo json_encode(array("200", "success|Pending Order Export initialized", md5($time)));
		}
		else
			echo json_encode(array("300",  "danger|Pending Order Export not available."));
	}
	else
	echo json_encode(array("300",  "danger|Please apply filter."));

?>