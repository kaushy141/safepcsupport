<?php

	$po_status = 0;
	$po_id = "";
	Modal::load(array('Po'));

    $data           = sanitizePostData($_POST);
	extract($data);
	$po = new Po($po_id);
	if($po->isExist()){
		$po->update(array('po_status'=> $po_status));
		echo json_encode(array("200",  "success|Purchase order ".($po_status ? "closed":"open")." now"));
	}
	else
		echo json_encode(array("300",  "warning|Purchase order not Found"));


?>