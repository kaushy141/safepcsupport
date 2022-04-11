<?php

	Modal::load(array('Po'));
	$po_id = 0;
	$data           = sanitizePostData($_POST);
	extract($data);
	$po = new Po($po_id);
	if($po->isExist()){
		$new_po_id = $po->createCopy();
		echo json_encode(array("200",  "success|New Purchase order created", $new_po_id));
	}
	else{
		echo json_encode(array("300",  "warning|Purchase order not Found", $po_id));
	}
	

?>