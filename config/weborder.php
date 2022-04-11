<?php
function updatewopaymentstatus(){
	$web_order_id = $web_order_is_paid = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$websiteOrder = new WebsiteOrder($web_order_id);
	if($websiteOrder->isExist() && in_array($web_order_is_paid, array("Yes","No")))
	{
		$websiteOrder->update(array("web_order_is_paid" => $web_order_is_paid));		
		echo json_encode(array("200",  "success|Weborder payment status updated successfully", $web_order_is_paid));
	}
	else
		echo json_encode(array("300",  "warning|No weborder found.", $web_order_is_paid));	
}

function updateweborder(){
	$web_order_id = $web_order_status = $web_order_is_picked = $web_order_is_packed = $web_order_is_processed =$web_order_picking_user = $web_order_packing_user = $web_order_process_user = $web_order_assign_technician = $web_order_assign_technician_chk = 0;
	$web_order_picking_time =  $web_order_packing_time = $web_order_process_time = $web_order_assign_technician_date = "";
	
	$wo_process_code = array();
	$data  = sanitizePostData($_POST);
	extract($data);	
	
	if($web_order_assign_technician_chk){
		if($web_order_assign_technician == 0 || $web_order_assign_technician_date == "")
		{
			echo json_encode(array("300",  "warning|Technician details required."));	
			die;
		}	
	}
	
	if(!$web_order_is_picked){
		$web_order_picking_user = 0;
		$web_order_picking_time = 'NULL';
	}
	if(!$web_order_is_packed){
		$web_order_packing_user = 0;
		$web_order_packing_time = 'NULL';
	}
	if(!$web_order_is_processed){
		$web_order_process_user = 0;
		$web_order_process_time = 'NULL';
	}
	
	//`web_order_picking_user`, `web_order_picking_time`, `web_order_packing_user`, `web_order_packing_time`, `web_order_process_user`, `web_order_process_time`
	$websiteOrder = new WebsiteOrder($web_order_id);
	if($websiteOrder->isExist())
	{
		$websiteOrder->update(array(
						"web_order_picking_user" => $web_order_picking_user,
						"web_order_picking_time" => $web_order_picking_time,
						"web_order_packing_user" => $web_order_packing_user,
						"web_order_packing_time" => $web_order_packing_time,
						"web_order_process_user" => $web_order_process_user,
						"web_order_process_time" => $web_order_process_time,
						"web_order_status" 		 => $web_order_status,
						"web_order_assign_technician" => $web_order_assign_technician,
						"web_order_assign_technician_date" => $web_order_assign_technician_date
								)
							 );
		if($web_order_status == 3){
			$websiteOrder->update(array(
									"web_order_cancel_user"=> getLoginId(),
									"web_order_cancel_time" => 'NOW()'
								)
							 );
			Activity::add("<font class='text-danger'>Cancelled</font> Web Order", "O", $web_order_id);
		}
		else{
			$websiteOrder->update(array(
									"web_order_cancel_user"=> 0,
									"web_order_cancel_time" => 'NULL'
								)
							 );
		}
		if($web_order_status == 1){
			Activity::add("<font class='text-success'>Completed</font> Web Order", "O", $web_order_id);
		}
		if(count($wo_process_code) > 0)
		{
			foreach($wo_process_code as $wo_id => $wo_process_code_value)
			{
				$wo_product_srno_code = $wo_product_srno[$wo_id];
				$websiteOrderProduct = new WebsiteOrderProduct($wo_id);
				$websiteOrderProduct->update(array("wo_process_code"=>$wo_process_code_value, "wo_product_srno"=>$wo_product_srno_code));
			}
		}
		echo json_encode(array("200",  "success|Weborder updated successfully"));
	}
	else
		echo json_encode(array("300",  "warning|No weborder found."));	
}

function removewebordermedia(){
	$record_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	if($record_id){
		$wopm = new WebsiteOrderProductMedia($record_id);
		$wopm->update(array("wpoi_status"=>0));
		echo json_encode(array("200",  "success|Weborder product image removed"));
	}
	else
		echo json_encode(array("300",  "warning|No weborder image found."));	
}

function viewwebsiteorderreportlist(){
	$data  = sanitizePostData($_POST);
	extract($data);
	$conditionArray = array();
	if($filter != NULL && count($filter)){
		foreach($filter as $field=>$values){
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
	}
	$websiteOrder = new WebsiteOrder();
	$websiteOrder->condition = $conditionArray;
	echo json_encode(array("200",  "success|Weborder report loaded", $websiteOrder->getWeborderReportData($websiteOrder->getCondition())));
	
}

function processmarktracking(){
	global $app;
	$order_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($order_id){
		$websiteOrder = new WebsiteOrder($order_id);
		if($websiteOrder->isExist()){
			$details = $websiteOrder->getDetails();
			if($details['web_order_tracking_mark_by'] == 0){
				$websiteOrder->update(array("web_order_tracking_mark_by"=> getLoginId()));
				echo json_encode(array("200",  "success|Weborder marked as tracked", "<img src=\"".$app->imagePath('img/track.png')."\" style=\"height:20px; width:24px;\">"));
			}
			else{
				$employee = new Employee($details['web_order_tracking_mark_by']);
				echo json_encode(array("300",  "warning|Weborder already marked as Tracked by ".$employee->get('user_fname')));
			}
		}
		else{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Weborder not found"));
	}
}

function weborderaction(){
	global $app;
	$order_action = '';
	$web_order_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($web_order_id){
		$websiteOrder = new WebsiteOrder($web_order_id);
		if($websiteOrder->isExist())
		{
			$details = $websiteOrder->getDetails();
			if($details['web_order_cancel_user'] == 0 || $details['web_order_status'] != 3 )
			{
				if($order_action == 'pick')
				{
					if($details['web_order_picking_user'] == 0){
						$websiteOrder->update(array(
						"web_order_picking_user"=> getLoginId(),
						"web_order_picking_time" => 'NOW()'));
						Activity::add("<b>Picked</b> Order", "O", $web_order_id);
						echo json_encode(array("200",  "success|Weborder marked as Picked"));
					}
					else{
						$employee = new Employee($details['web_order_picking_user']);
						echo json_encode(array("300",  "warning|Weborder already picked by ".$employee->get('user_fname')));
					}
				}
				elseif($order_action == 'pack')
				{
					if($details['web_order_picking_user'] != 0)
					{
						if($details['web_order_packing_user'] == 0){
							$websiteOrder->update(array(
							"web_order_packing_user"=> getLoginId(),
							"web_order_packing_time" => 'NOW()'
							));
							Activity::add("<b>Packed</b> Order", "O", $web_order_id);
							echo json_encode(array("200",  "success|Weborder marked as Packed"));
						}
						else{
							$employee = new Employee($details['web_order_packing_user']);
							echo json_encode(array("300",  "warning|Weborder already Packed by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder should be Picked first"));
					}
				}
				elseif($order_action == 'process')
				{
					if($details['web_order_packing_user'] != 0)
					{
						if($details['web_order_process_user'] == 0){
							$websiteOrder->update(array(
							"web_order_process_user"=> getLoginId(),
							"web_order_process_time" => 'NOW()',
							"web_order_status" => 4));
							Activity::add("<b>Processed</b> Order", "O", $web_order_id);
							echo json_encode(array("200",  "success|Weborder marked as Process"));
						}
						else{
							$employee = new Employee($details['web_order_process_user']);
							echo json_encode(array("300",  "warning|Weborder already Process by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder should be Packed first"));
					}
				}
				if($order_action == 'cancel')
				{				
					$websiteOrder->update(array(
					"web_order_cancel_user"=> getLoginId(),
					"web_order_cancel_time" => 'NOW()',
					"web_order_status" => 3
					));
					Activity::add("<b>Canceled</b> Order", "O", $web_order_id);
					echo json_encode(array("200",  "success|Weborder marked as Cancel"));
				}
				else
				{
					echo json_encode(array("300",  "warning|Invalid Action Found"));
				}				
			}
			else
			{				
				$employee = new Employee($details['web_order_cancel_user']);
				echo json_encode(array("300",  "warning|Weborder already Cancel by ".$employee->get('user_fname')));
			}
		}
		else
		{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else
	{
		echo json_encode(array("300",  "warning|Weborder not found"));
	}
}
function exportpendingordertoexcel(){
	global $app;
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
}

function checkorderfulfillment(){
	global $app;
	$web_order_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($web_order_id){
		
		$websiteOrder = new WebsiteOrder($web_order_id);
		if($websiteOrder->isExist()){
			$details = $websiteOrder->getDetails();
			$weborderProducts = new WebsiteOrderProduct();
			$productList = $weborderProducts->getList($details['web_order_id']);
			$fulfillmentList = array();
			if($productList)
			{
				foreach($productList as $_product){
					$fulfillmentList[] = array(
						'product' => $_product,
						'fulfill'=> WebsiteOrderProduct::checkProductAvailability($_product['wo_product_sku'])
					);
				}
			}
			echo json_encode(array("200", "success|Order FulFillment checked", $fulfillmentList));
		}
		else{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Weborder not found"));
	}
}

function runfulfillordercheck(){
	if(WebsiteOrder::checkFulfillment())
		echo json_encode(array("200", "success|Order FulFillment checked"));
	else
		echo json_encode(array("300",  "warning|Order FulFillment check failed"));
}
?>