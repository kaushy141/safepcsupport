<?php
	$sales_invoice_id = 0;
	$duplicate_customer_email_id = "";
	$data = sanitizePostData($_POST);
	extract($data);	
	$salesinvoice = new SalesInvoice($sales_invoice_id);
	if($salesinvoice->isExist())
	{		
		$sales_invoice_id_new = $salesinvoice->duplicateInvoice($duplicate_customer_email_id, $duplicate_customer_address_id);
		echo json_encode(array("200",  "success|Duplicate sales invoice created", $sales_invoice_id_new));
	}
	else
		echo json_encode(array("300", "danger|Requested sales invoice not found."));	

?>