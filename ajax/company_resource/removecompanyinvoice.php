<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$CompanyInvoice = new CompanyInvoice($id);
	if($CompanyInvoice->isActive())
	{
		$record = $CompanyInvoice->getDetails();
		$file_extension = pathinfo(BP.$record['company_invoice_file_path'], PATHINFO_EXTENSION);	
		$company_invoice_file_path = DOC_UPLOAD_DIR."company/invoice/del/invoice-".time().".$file_extension";
		if(move_file(BP.$record['company_invoice_file_path'], BP.$company_invoice_file_path))
		{
			unlink(BP.$record['company_invoice_file_path']);
			$CompanyInvoice->update(array("company_invoice_file_path" => $company_invoice_file_path));
			$CompanyInvoice->Deactivate();
			Activity::add("Removed Company Invoice <b>$record[company_invoice_name]</b>","", $id);
			echo json_encode(array("200",  "success|Company Invoice file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete Company Invoice file."));
	}
	else
		echo json_encode(array("300",  "warning|Company Invoice not found."));

?>