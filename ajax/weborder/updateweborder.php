<?php
	$web_order_id = $web_order_status = $web_order_is_picked = $web_order_is_packed = $web_order_is_processed =$web_order_picking_user = $web_order_packing_user = $web_order_process_user = $web_order_assign_technician = $web_order_assign_technician_chk = $web_order_commission_user_chk = $web_order_commission_user = 0;
	$web_order_picking_time =  $web_order_packing_time = $web_order_process_time = $web_order_assign_technician_date = $web_order_dispatched_store = "";
	
	$wo_process_code = $wo_shipping_charges = array();
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
		
		if($web_order_commission_user_chk == 1 && $web_order_commission_user != $orderDetails['web_order_commission_user']){
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
				$wo_product_purchase_amount = $wo_purchase_amount[$wo_id];
				$wo_product_purchase_source = $wo_purchase_source[$wo_id];
				$wo_product_shipping_price = $wo_shipping_charges[$wo_id];
				$websiteOrderProduct = new WebsiteOrderProduct($wo_id);
				$websiteOrderProduct->update(array("wo_process_code"=>$wo_process_code_value, "wo_product_srno"=>$wo_product_srno_code, "wo_product_purchase_amount"=>$wo_product_purchase_amount, "wo_product_purchase_source"=>$wo_product_purchase_source, "wo_product_shipping_price" => $wo_product_shipping_price));
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

?>