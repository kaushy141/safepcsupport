<?php
	$sales_commission_user_id = $sales_commission_month = 0;
	$chkinvoice = $commission_percent = array();
	$data = sanitizePostData($_POST);
	Modal::load(array('SalesCommission'));
	extract($data);	
	//print_r($data);die;
	if($sales_commission_user_id != "" && $sales_commission_month != "")
	{
		$salesCommission = new SalesCommission();		
		if(!$salesCommission->isCommissionExist($sales_commission_user_id, $sales_commission_month))
		{	if(count($chkinvoice) > 0 && count($chkinvoice) == count($commission_percent))	
			{
				$processCommisionIds = array();
				foreach($chkinvoice as $code => $commisionArray)
				{
					$processCommisionIds = array_merge($processCommisionIds, $commisionArray);
				}
				if(!$salesCommission->isSalesCommisionIssued($processCommisionIds))
				{
					$sales_commission_batch = $salesCommission->getSalesCommissionBatchCode();
					foreach($chkinvoice as $module_code => $commisionArray)
					{
						foreach($commisionArray as $key => $sales_commission_sales_id)
						{
							$sales_commission_percentage = $commission_percent[$module_code][$key];
							$sales_invoice_amount = SalesCommission::getSalesInvoiceAmount($module_code, $sales_commission_sales_id, $sales_commission_user_id);
							$sales_commission_amount = round(($sales_invoice_amount * $sales_commission_percentage)/100, 2);
							if($salesCommission->insert(array(
								"sales_commission_sales_id" 	=> $sales_commission_sales_id, 
								"sales_commission_type_code" 	=> $module_code, 
								"sales_commission_created_date" => "NOW()",
								"sales_commission_user_id" 		=> $sales_commission_user_id,
								"sales_commission_month" 		=> $sales_commission_month, 
								"sales_commission_invoice_amount"	=> $sales_invoice_amount,
								"sales_commission_percentage" 	=> $sales_commission_percentage,
								"sales_commission_amount" 		=> $sales_commission_amount,
								"sales_commission_batch" 		=> $sales_commission_batch,
								"sales_commission_status" 		=> 1
								))
							){
							SalesCommission::markCommissionProcessed($module_code, $sales_commission_sales_id);
							}
							//update `app_sales_invoice_commission` set `sales_commission_invoice_amount` = (SELECT (`sales_invoice_total_amount` - `sales_discount_amount`) FROM `app_sales_invoice` WHERE `sales_invoice_id` = `sales_commission_sales_id`) WHERE `sales_commission_type_code` = 'S' AND `sales_commission_invoice_amount` = '0.00'
							
							//update `app_sales_invoice_commission` SET `sales_commission_amount` = ROUND((`sales_commission_invoice_amount` * `sales_commission_percentage`)/100, 2) WHERE `sales_commission_amount` = 0
						}
					}
					SalesCommission::fixedCommissionAmountIssue();
					echo json_encode(array("200",  "success|Sales Commision genarated"));
				}
				else
					echo json_encode(array("300", "danger|Some invoices already commisioned to employee."));
			}
			else
				echo json_encode(array("300", "danger|Invoices and their commission not matched"));
		}
		else
			echo json_encode(array("300", "danger|User already alloted commission for this month."));
	}
	else
		echo json_encode(array("300", "danger|No user found."));

?>