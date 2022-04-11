<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$SalesInvoice = new SalesInvoice($id);
	if($SalesInvoice->isExist())
	{
		$record = $SalesInvoice->getDetails();
		if($record['sales_invoice_is_cancelled']==0)
		{
			$SalesInvoice->update(
									array(
										"sales_invoice_status" => 3,
										"sales_invoice_is_cancelled" => 1,
										"sales_invoice_cancel_user" => getLoginId(),
										"sales_invoice_cancel_time" => "NOW()"
										)
								);
			Activity::add("Cancelled|^|{$record['sales_invoice_number']}","S", $id);
			echo json_encode(array("200",  "success|Sales Invoice cancelled Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Sales Invoice allready cancelled."));
	}
	else
		echo json_encode(array("300",  "warning|Sales Invoice record not found."));

?>