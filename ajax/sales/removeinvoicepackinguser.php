<?php
	$sales_invoice_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($sales_invoice_id){
		$salesInvoice = new SalesInvoice($sales_invoice_id);
		if($salesInvoice->isExist())
		{
			$action_user_id = getLoginId();
			$employee = new Employee($action_user_id);
			$actionUserData = $employee->getDetails();
			$details = $salesInvoice->getDetails();
			if($details['sales_invoice_cancel_user'] == 0 || $details['sales_invoice_status'] != 3 )
			{
				if($details['sales_invoice_packing_user'] != 0 && $details['sales_invoice_process_user'] == 0)
				{						
					$salesInvoice->update(array(
						"sales_invoice_packing_user"=> 0,
						"sales_invoice_packing_time" => 'NULL'
					));
					Activity::add("Removed assigned Packing user from |^|{$details['sales_invoice_number']}", "S", $sales_invoice_id);
					echo json_encode(array("200",  "success|Packing user removed."));
				}
				else
				{
					echo json_encode(array("300",  "warning|Sales Invoice is Processed. Can't remove Packing user"));
				}				
			}
			else
				echo json_encode(array("300",  "warning|Sales Invoice is Cancelled or Completed. Can't remove Packing user"));
		}
		else
			echo json_encode(array("300",  "warning|Sales Invoice not found"));
	}
	else
		echo json_encode(array("300",  "warning|Not a valid Sales Invoice"));

?>