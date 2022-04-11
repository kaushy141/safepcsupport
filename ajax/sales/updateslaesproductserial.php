<?php
	$srno = $type = $sipd_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($srno != "" )
	{
		$salesinvoice = new SalesInvoice();
		$salesinvoice->updateProductSerials($sipd_id, $srno, $type);
		echo json_encode(array("200",  "success|Product serial number ".($type ? "added":"removed")));
	}
	else
		echo json_encode(array("300", "danger|Not a valid serial number."));	

?>