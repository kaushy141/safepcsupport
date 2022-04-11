<?php	
	$data  = sanitizePostData($_POST);
	extract($data);
	if(isAdmin()){
		$salesInvoice = new SalesInvoice();
		$value_key_id = 'last_paid_invoice_seen_'.getLoginId();
		$sales_invoice_mark_paid_on = Values::getKeyValues($value_key_id);
		$invoiceData = $salesInvoice->getSalesInvoiceFromInvoiceID($sales_invoice_mark_paid_on);	
		Values::saveKeyValues($value_key_id, $salesInvoice->getLastTimePaidMark());
		echo json_encode(array("200",  "success|Sales invoice fetched", $invoiceData));		
	}
	else	
		echo json_encode(array("300",  "danger|Permission deniend"));

?>