<?php
	$sales_invoice_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($sales_invoice_id != 0 )
	{
		$salesinvoice = new SalesInvoice($sales_invoice_id);
		if($salesinvoice->isExist())
		{
			$salesinvoice->update(array('sales_invoice_commission_processed'=>1));
			echo json_encode(array("200",  "success|Sales Invoice Removed from Commission"));
		}			
		else
			echo json_encode(array("300", "danger|No Invoice found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid invoice."));

?>