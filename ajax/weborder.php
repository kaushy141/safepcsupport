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
	$web_order_id = $web_order_status = $web_order_is_picked = $web_order_is_packed = $web_order_is_processed =$web_order_picking_user = $web_order_packing_user = $web_order_process_user = $web_order_assign_technician = $web_order_assign_technician_chk = $web_order_commission_user_chk = $web_order_commission_user = 0;
	$web_order_picking_time =  $web_order_packing_time = $web_order_process_time = $web_order_assign_technician_date = $web_order_dispatched_store = "";
	
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
		$orderDetails = $websiteOrder->load();
		if($web_order_status != $orderDetails['web_order_status'] && ($orderDetails['web_order_status'] == 1 || $orderDetails['web_order_status'] == 3))
		{
			if(isAdminRole()){
				if($orderDetails['web_order_status'] == 1){
					$websiteOrder->update(array(
							"web_order_complete_user"=> 0,
							"web_order_complete_time" => 'NULL'
						)
					 );
				}
				elseif($orderDetails['web_order_status'] == 3){
					$websiteOrder->update(array(
							"web_order_cancel_user"=> 0,
							"web_order_cancel_time" => 'NULL'
						)
					 );
				}
			}
			else{
				echo json_encode(array("300",  "warning|Weborder Already marked {$orderDetails['wc_status_name']}. Can't change status."));
				die;
			}
		}
		$websiteOrder->update(array(
						/*"web_order_picking_user" => $web_order_picking_user,
						"web_order_picking_time" => $web_order_picking_time,
						"web_order_packing_user" => $web_order_packing_user,
						"web_order_packing_time" => $web_order_packing_time,
						"web_order_process_user" => $web_order_process_user,
						"web_order_process_time" => $web_order_process_time,*/
						"web_order_status" 		 => $web_order_status,
						"web_order_assign_technician" => $web_order_assign_technician,
						"web_order_assign_technician_date" => $web_order_assign_technician_date,
						"web_order_dispatched_store" => $web_order_dispatched_store
						
								)
							 );
		if($web_order_status == 1 || $web_order_status == 3)
		{
			if($web_order_status == 1 && $orderDetails['web_order_status'] != $web_order_status)
			{
				$websiteOrder->update(array(
									"web_order_complete_user"=> getLoginId(),
									"web_order_complete_time" => 'NOW()'
								)
							 );
			}
			elseif($web_order_status == 3)
			{
				$websiteOrder->update(array(
									"web_order_cancel_user"=> getLoginId(),
									"web_order_cancel_time" => 'NOW()'
								)
							 );
			}
			
		}
		else{
			$websiteOrder->update(array(
									"web_order_cancel_user"=> 0,
									"web_order_cancel_time" => 'NULL',
									"web_order_complete_user"=> 0,
									"web_order_complete_time" => 'NULL'
								)
							 );
		}
		
		if($web_order_commission_user != 0 && $web_order_commission_user_chk == 1){
			$websiteOrder->update(array(
									"web_order_commission_user"=> $web_order_commission_user
								)
							 );
			$commUser = new Employee($web_order_commission_user);
			$commUserDetails = $commUser->getDetails();
			Activity::add("added commission user <b>$commUserDetails[user_fname] $commUserDetails[user_lname]</b> on |^|{$orderDetails['web_order_number']}", "O", $web_order_id);
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
		if($web_order_status != $orderDetails['web_order_status'])
		{
			$tectClass= '';
			$newOrderDetails = $websiteOrder->load();
			if($web_order_status == 1)
			$tectClass= 'text-success';
			elseif($web_order_status == 3)
			$tectClass= 'text-danger';
			
			Activity::add("<font class='$tectClass'>{$newOrderDetails['wc_status_name']}</font> Web Order |^|{$orderDetails['web_order_number']}", "O", $web_order_id);
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

function undoaction(){
	$web_order_id = 0;
	$step = '';
	$data  = sanitizePostData($_POST);
	
	extract($data);
	if($web_order_id){
		$websiteOrder = new WebsiteOrder($web_order_id);
		if($websiteOrder->isExist())
		{
			$action_user_id = getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $websiteOrder->getDetails();
			if($step == "unpick")
			{
				if($details['web_order_cancel_user'] == 0 || $details['web_order_status'] != 3 )
				{
					if($details['web_order_picking_user'] != 0 && $details['web_order_packing_user'] == 0)
					{						
						$websiteOrder->update(array(
							"web_order_picking_user"=> 0,
							"web_order_picking_time" => 'NULL',
							"web_order_status" => 2
						));
						Activity::add("<b>Step back order</b> removing assigned Picking user from  |^|{$details['web_order_number']}", "O", $web_order_id);
						echo json_encode(array("200",  "success|Picking user removed."));
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder is Processed. Can't remove Packing user"));
					}				
				}
				else
					echo json_encode(array("300",  "warning|Weborder is Cancelled or Completed. Can't remove Packing user"));
			}
			elseif($step == "unpack")
			{
				if($details['web_order_cancel_user'] == 0 || $details['web_order_status'] != 3 )
				{
					if($details['web_order_packing_user'] != 0 && $details['web_order_process_user'] == 0)
					{						
						$websiteOrder->update(array(
							"web_order_packing_user"=> 0,
							"web_order_packing_time" => 'NULL',
							"web_order_status" => 2
						));
						Activity::add("<b>Step back order</b> removing assigned Packing user from  |^|{$details['web_order_number']}", "O", $web_order_id);
						echo json_encode(array("200",  "success|Packing user removed."));
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder is Processed. Can't remove Packing user"));
					}				
				}
				else
					echo json_encode(array("300",  "warning|Weborder is Cancelled or Completed. Can't remove Packing user"));
			}
			elseif($step == "unprocess")
			{
				if($details['web_order_cancel_user'] == 0 || $details['web_order_status'] != 3 )
				{
					if($details['web_order_process_user'] != 0 && $details['web_order_complete_user'] == 0)
					{						
						$websiteOrder->update(array(
							"web_order_process_user"=> 0,
							"web_order_process_time" => 'NULL',
							"web_order_status" => 2
						));
						Activity::add("<b>Step back order</b> by removing assigned Processing user from |^|{$details['web_order_number']}", "O", $web_order_id);
						echo json_encode(array("200",  "success|Processing user removed."));
					}
					else
					{
						echo json_encode(array("300",  "warning|Weborder is Processed. Can't remove Packing user"));
					}				
				}
				else
					echo json_encode(array("300",  "warning|Weborder is Cancelled or Completed. Can't remove Packing user"));
			}
		}
		else
			echo json_encode(array("300",  "warning|Weborder not found"));
	}
	else
		echo json_encode(array("300",  "warning|Not a valid Web order"));
}

function viewwebsiteorderreportlist(){
	$data  = sanitizePostData($_POST);
	extract($data);
	$salesMapColumnArray = array(
		'web_order_website_id' => 'sales_invoice_store_id',
		'web_order_status' => 'sales_invoice_status',
		'web_order_payment_method' => NULL,
		'web_order_currency' => 'sales_invoice_currency'
	);
	
	
	$orderTotalConditionArray = array();
	$salesTotalConditionArray = array();
	if($filter != NULL && count($filter)){
		foreach($filter as $field=>$values){
			$orderConditionArray = array();
			$salesConditionArray = array();
			if($field == "duration"){
				if($values == "today"){
				$orderConditionArray[] = " DATE(a.web_order_created_date) = CURRENT_DATE ";
				$salesConditionArray[] = " DATE(a.sales_invoice_created_date) = CURRENT_DATE ";
				}
				elseif($values == "month"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ";
				}
				elseif($values == "quarter"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) ";
				}
				elseif($values == "halfyear"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) ";
				}
				elseif($values == "year"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ";
				}
				elseif($values == "custom"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
				}
			}
			else
			{
				if(is_array($values)){
					foreach($values as $_val){
						$orderConditionArray[] = array("a.".$field, "=", sanitizePostData($_val));
						if(isset($salesMapColumnArray[$field]))
						$salesConditionArray[] = array("a.".$salesMapColumnArray[$field], "=", sanitizePostData($_val));
					}
				}
				else{
					$orderConditionArray[] = array("a.".$field, "=", sanitizePostData($values));
					if(isset($salesMapColumnArray[$field]))
					$salesConditionArray[] = array("a.".$salesMapColumnArray[$field], "=", sanitizePostData($values));
				}
				
				
			}
			$orderTotalConditionArray[] = $orderConditionArray;	
			$salesTotalConditionArray[] = $salesConditionArray;
		}
	}
	$websiteOrder = new WebsiteOrder();
	$websiteOrder->condition = $orderTotalConditionArray;
	$reportData['website_order'] = $websiteOrder->getWebsiteOrderStatistics($websiteOrder->getCondition(), $filter['web_order_currency'] ?: 'GBP');
	
	$salesInvoice = new SalesInvoice();
	$salesInvoice->condition = $salesTotalConditionArray;
	
	$reportData['website_sales'] = $salesInvoice->getSalesInvoiceStatistics($salesInvoice->getCondition(), $filter['web_order_currency'] ?: 'GBP');
	
	
	echo json_encode(array("200",  "success|Weborder report loaded", $reportData, $filter['web_order_currency'] ?: 'GBP'));
	
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
	$user_id = 0;
	$user_reset = 0;
	$web_order_is_outsourced = 0;
	$same_user_to_pack_order = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($web_order_id){
		$websiteOrder = new WebsiteOrder($web_order_id);
		if($websiteOrder->isExist())
		{
			$action_user_id = $user_id ?  $user_id : getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $websiteOrder->getDetails();
			if($details['web_order_cancel_user'] == 0 || $details['web_order_status'] != 3 )
			{
				if($order_action == 'pick')
				{
					$updateArry = array(
					"web_order_picking_user"=> $action_user_id,
					"web_order_picking_time" => 'NOW()');
					if($same_user_to_pack_order == 1){
						$updateArry["web_order_packing_user"] = $action_user_id;
						$updateArry["web_order_packing_time"] = 'NOW()';
						$updateArry["web_order_is_outsourced"]= 0;
					}
					
					if($action_user_id != getLoginId())
					{
						$userTag = new UserTag();
						$userTag->insert(array(
								"tag_mentioner_id" 	=> getLoginId(), 
								"tag_user_id" 		=> $action_user_id, 
								"tag_module_code"	=> 'O', 
								"tag_module_id"		=> $web_order_id, 
								"tag_time"			=> 'NOW()', 
								"tag_is_readed"		=> 0, 
								"tag_type"			=> 'assigned picking by', 
								"tag_text"			=> $details['web_order_number'],
								"tag_log_id"		=> 0
							)
						);
					}
					
					$websiteOrder->update($updateArry);
					Activity::add("marked <b>Picked</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
					if($same_user_to_pack_order == 1){
						
						if($action_user_id != getLoginId())
						{
							$userTag = new UserTag();
							$userTag->insert(array(
									"tag_mentioner_id" 	=> getLoginId(), 
									"tag_user_id" 		=> $action_user_id, 
									"tag_module_code"	=> 'O', 
									"tag_module_id"		=> $web_order_id, 
									"tag_time"			=> 'NOW()', 
									"tag_is_readed"		=> 0, 
									"tag_type"			=> 'assigned packing by', 
									"tag_text"			=> $details['web_order_number'],
									"tag_log_id"		=> 0
								)
							);
						}
							
						Activity::add("marked <b>Packed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
					}
					echo json_encode(array("200",  "success|Weborder marked as Picked".($same_user_to_pack_order ? " and Packed":"")));
				}
				elseif($order_action == 'pack')
				{
					if($details['web_order_picking_user'] != 0)
					{
						if($details['web_order_packing_user'] == 0 || ($user_reset == md5($details['web_order_packing_user']))){
							$websiteOrder->update(array(
							"web_order_packing_user"=> $action_user_id,
							"web_order_packing_time" => 'NOW()',
							"web_order_is_outsourced" => $web_order_is_outsourced
							));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'O', 
										"tag_module_id"		=> $web_order_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned packing by', 
										"tag_text"			=> $details['web_order_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add(($user_reset ? "Remarked":"marked")." <b>Packed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
							echo json_encode(array("200",  "success|Weborder ".($user_reset ? "Remarked":"marked")." as Packed"));
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
							"web_order_process_user"=> $action_user_id,
							"web_order_process_time" => 'NOW()',
							"web_order_status" => 4));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'O', 
										"tag_module_id"		=> $web_order_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned processing by', 
										"tag_text"			=> $details['web_order_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add("marked <b>Processed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
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
				elseif($order_action == 'cancel')
				{				
					$websiteOrder->update(array(
					"web_order_cancel_user"=> $action_user_id,
					"web_order_cancel_time" => 'NOW()',
					"web_order_status" => 3
					));
					Activity::add("marked <b>Cancelled</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['web_order_number']}", "O", $web_order_id);
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

function downloadweborderlabel(){
	global $app;
	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('WeborderLabels'));
		$weborderLabels = new WeborderLabels();		
		if($labelData = $weborderLabels->loadByMd5($label_id)){
			$weborderLabels->updateDownloadCount();	
			echo json_encode(array("200", "success|Label Downloaded", $app->basePath($labelData['label_path']), $labelData['label_downloads']+1));
		}
		else{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Label not found"));
	}
}

function webordercancellabel(){
	global $app;
	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('WeborderLabels'));
		$weborderLabels = new WeborderLabels();		
		if($labelData = $weborderLabels->loadByMd5($label_id)){
			$weborderLabels->update(array("label_status" => 0));
			Activity::add("Cancelled Label for", "O", $labelData['label_order_id']);
			unlinkFile($labelData['label_path']);
			echo json_encode(array("200", "success|Label Cancelled successfully"));
		}
		else{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Label not found"));
	}
}

function updateweborderimagemetadetails(){
	global $app;
	$image_id = 0;
	$image_title = "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if($image_id){		
		$wopm = new WebsiteOrderProductMedia($image_id);
		$wopm->update(array("wpoi_title"=>$image_title));
		echo json_encode(array("200",  "success|Weborder product image details updated"));
	}
	else{
		echo json_encode(array("300",  "warning|Image not found"));
	}
}

function sendweborderimagetocustomer(){
	global $app;
	$web_order_id = 0;
	$order_product_images = array();
	$data  = sanitizePostData($_POST);
	extract($data);
	$websiteOrder = new WebsiteOrder($web_order_id);
	if(count($order_product_images) && $websiteOrder->isExist())
	{
		$order = $websiteOrder->load();
		$email = new Email("$order[store_name] Order #$order[web_order_number] Products image");		
        $email->to($order['customer_email'], $order['customer_fname'].' '.$order['customer_lname'], $order['customer_image']);
		//$email->to("kaushyedu@gmail.com", "Kaushal Sachan", $order['customer_image']);
		$tableHtml = '<table widht="100%">';		
		$websiteOrderProduct = new WebsiteOrderProduct();
        $productList = $websiteOrderProduct->getList($web_order_id);
		$productCounter = 1;
		$totalImageCounter = 0;
		foreach($productList as $_product)
		{
			$wopm = new WebsiteOrderProductMedia(0);
			$productImages = $wopm->getImageList($_product['wo_id']);
			if(isset($order_product_images[$_product['wo_id']]) && count($productImages)){
				$tableHtml .= '<tr><td colspan="3">'.($productCounter++).'. '.$_product['wo_product_name'].'</td></tr>';
				$tableHtml .= '<tr>
				<td><a href="'.$_product['wo_product_url'].'"><img height="80px" src="'.$_product['wo_product_image'].'"></a></td>
				<td>SKU<br/><a href="'.$_product['wo_product_url'].'"><strong>'.$_product['wo_product_sku'].'"</strong></a></td>
				<td>SR/No<br/>'.($_product['wo_product_srno'] ? $_product['wo_product_srno'] : "N/A").'</td>
				</tr>';
				$tableHtml .= '<tr><td colspan="3">Media images</td></tr>';
				$tableHtml .= '<tr><td colspan="3">';
				$tableHtml .= '<table widht="100%">';
				$imageCounter = 1;
				$selectedImages = $order_product_images[$_product['wo_id']];
				foreach($productImages as $_image){
					if(in_array($_image['wpoi_id'], $selectedImages))
					{
						$wopi = new WebsiteOrderProductMedia($_image['wpoi_id']);
						$wopi->update(array('wpoi_sent_to_customer' => 1));
						$tableHtml .= '<tr>
						<td width="180px"><img width="180px" src="'.$app->siteUrl($_image['wpoi_image_path']).'"></td>
						<td align = "right">'.($_image['wpoi_title'] ? $_image['wpoi_title'] : "Other").'</td>
						</tr>';
						$email->addFile($_image['wpoi_image_path'], "$_product[wo_product_sku]_$productCounter"."_".$imageCounter."_".basename($_image['wpoi_image_path']));
						$totalImageCounter++;
					}
				}
				$tableHtml .= '</table>';
				$tableHtml .= '</td></tr>';
			}
		}
		$tableHtml .= '</table>';
		$templateData = array(
			"store_name" => $order['store_name'],
			"order_number" => $order['web_order_number'],
			"customer_name" => $order['customer_fname'].' '.$order['customer_lname'],
			"table_html" => $tableHtml
		);
		$email->template('weborder_images_sent', $templateData)->send();
		echo json_encode(array("200",  "success|$totalImageCounter Weborder product image sent to customer"));
	}
	else{
		echo json_encode(array("300",  "warning|Image not found"));
	}
}

function fetchstoreorder(){
	global $app;
	$order_id = $store_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	$store = new Store($store_id);
	if($store->isExist()){
		$websiteOrder =  new WebsiteOrder();
		if(!$websiteOrder->isOrderExist($order_id, $store_id))
		{
			$storeData = $store->getDetails();
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, $storeData['store_link'].'orderpush.php?order_id='.$order_id );
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true);
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			$response = curl_exec($ch );	
			curl_close( $ch );
			if($response == "Order Pushed"){
				echo json_encode(array("300",  "success|Order imported. Order may take 1 minute to visible"));
			}
			else{
				echo json_encode(array("300",  "warning|Order import failed"));
			}
		}
		else{
			echo json_encode(array("300",  "warning|Order alreay exist on support system"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Store not found"));
	}
}
?>