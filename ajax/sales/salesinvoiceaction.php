<?php

	$order_action = '';
	$sales_invoice_id = 0;
	$invoice_user_id = 0;
	$user_reset = 0;
	$sales_invoice_is_outsourced = 0;
	$same_user_to_pack_order = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($sales_invoice_id){
		$salesInvoice = new SalesInvoice($sales_invoice_id);
		if($salesInvoice->isExist())
		{
			$action_user_id = $invoice_user_id ?  $invoice_user_id : getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $salesInvoice->getDetails();
			if($details['sales_invoice_cancel_user'] == 0 || $details['sales_invoice_status'] != 3 )
			{
				if($order_action == 'pick')
				{
					$updateArry = array(
					"sales_invoice_picking_user"=> $action_user_id,
					"sales_invoice_picking_time" => 'NOW()');
					if($same_user_to_pack_order == 1){
						$updateArry["sales_invoice_packing_user"] = $action_user_id;
						$updateArry["sales_invoice_packing_time"] = 'NOW()';
						$updateArry["sales_invoice_is_outsourced"]= 0;
					}
					$salesInvoice->update($updateArry);
					
					if($action_user_id != getLoginId())
					{
						$userTag = new UserTag();
						$userTag->insert(array(
								"tag_mentioner_id" 	=> getLoginId(), 
								"tag_user_id" 		=> $action_user_id, 
								"tag_module_code"	=> 'S', 
								"tag_module_id"		=> $sales_invoice_id, 
								"tag_time"			=> 'NOW()', 
								"tag_is_readed"		=> 0, 
								"tag_type"			=> 'assigned picking by', 
								"tag_text"			=> $details['sales_invoice_number'],
								"tag_log_id"		=> 0
							)
						);
					}
					
					Activity::add("marked <b>Picked</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
					if($same_user_to_pack_order == 1){
						$userTag = new UserTag();
						if($action_user_id != getLoginId())
						{
							$userTag->insert(array(
									"tag_mentioner_id" 	=> getLoginId(), 
									"tag_user_id" 		=> $action_user_id, 
									"tag_module_code"	=> 'S', 
									"tag_module_id"		=> $sales_invoice_id, 
									"tag_time"			=> 'NOW()', 
									"tag_is_readed"		=> 0, 
									"tag_type"			=> 'assigned packing by', 
									"tag_text"			=> $details['sales_invoice_number'],
									"tag_log_id"		=> 0
								)
							);
						}
						Activity::add("marked <b>Packed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
					}
					echo json_encode(array("200",  "success|Sales Invoice marked as Picked".($same_user_to_pack_order ? " and Packed":"")));
				}
				elseif($order_action == 'pack')
				{
					if($details['sales_invoice_picking_user'] != 0)
					{
						if($details['sales_invoice_packing_user'] == 0 || ($user_reset != 0 && $user_reset == md5($details['sales_invoice_packing_user']))){
							$salesInvoice->update(array(
							"sales_invoice_packing_user"=> $action_user_id,
							"sales_invoice_packing_time" => 'NOW()',
							"sales_invoice_is_outsourced" => $sales_invoice_is_outsourced
							));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'S', 
										"tag_module_id"		=> $sales_invoice_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned packing by', 
										"tag_text"			=> $details['sales_invoice_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add(($user_reset ? "Remarked":"marked")." <b>Packed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
							echo json_encode(array("200",  "success|Sales Invoice ".($user_reset ? "Remarked":"marked")." as Packed"));
						}
						else{
							$employee = new Employee($details['sales_invoice_packing_user']);
							echo json_encode(array("300",  "warning|Sales Invoice already Packed by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|Sales Invoice should be Picked first"));
					}
				}
				elseif($order_action == 'process')
				{
					if($details['sales_invoice_packing_user'] != 0)
					{
						if($details['sales_invoice_process_user'] == 0){
							$salesInvoice->update(array(
							"sales_invoice_process_user"=> $action_user_id,
							"sales_invoice_process_time" => 'NOW()',
							"sales_invoice_status" => 4));
							
							if($action_user_id != getLoginId())
							{
								$userTag = new UserTag();
								$userTag->insert(array(
										"tag_mentioner_id" 	=> getLoginId(), 
										"tag_user_id" 		=> $action_user_id, 
										"tag_module_code"	=> 'S', 
										"tag_module_id"		=> $sales_invoice_id, 
										"tag_time"			=> 'NOW()', 
										"tag_is_readed"		=> 0, 
										"tag_type"			=> 'assigned processing by', 
										"tag_text"			=> $details['sales_invoice_number'],
										"tag_log_id"		=> 0
									)
								);
							}
							
							Activity::add("marked <b>Processed</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
							echo json_encode(array("200",  "success|Sales Invoice marked as Process"));
						}
						else{
							$employee = new Employee($details['sales_invoice_process_user']);
							echo json_encode(array("300",  "warning|Sales Invoice already Process by ".$employee->get('user_fname')));
						}
					}
					else
					{
						echo json_encode(array("300",  "warning|Sales Invoice should be Packed first"));
					}
				}
				elseif($order_action == 'cancel')
				{				
					$salesInvoice->update(array(
					"sales_invoice_cancel_user"=> $action_user_id,
					"sales_invoice_cancel_time" => 'NOW()',
					"sales_invoice_status" => 3
					));
					Activity::add("marked <b>Cancelled</b> Order by <b>$actionUserData[user_name]</b>|^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
					echo json_encode(array("200",  "success|Sales Invoice marked as Cancel"));
				}
				else
				{
					echo json_encode(array("300",  "warning|Invalid Action Found"));
				}				
			}
			else
			{				
				$employee = new Employee($details['sales_invoice_cancel_user']);
				echo json_encode(array("300",  "warning|Sales Invoice already Cancel by ".$employee->get('user_fname')));
			}
		}
		else
		{
			echo json_encode(array("300",  "warning|Sales Invoice not exist"));
		}
	}
	else
	{
		echo json_encode(array("300",  "warning|Sales Invoice not found"));
	}

?>