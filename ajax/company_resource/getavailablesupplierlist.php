<?php
	$section_code = "";
	$product_id = 1;
    $data           = sanitizePostData($_POST);    
    extract($data);
	$supplier = new Supplier(0);
	echo json_encode(array("200",  "success|Supplier list loaded", $supplier->getOptions(), date('Y-m-d H:i')));

?>