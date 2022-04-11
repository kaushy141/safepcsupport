<?php

	$signature_code = "";
	$signature_class = "";
	$signature_column = "";
	$signature_record_id = "";
	$signature_title = "";
	$signature_validity_second = SIGNATURE_LINK_VALIDITY_TIME;
	$signature_created_date = 'NOW()';
	$signature_is_used = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($signature_class != "" && $signature_column != "" && $signature_record_id != "" ){
		$signature_code = md5(strtolower(trim($signature_class).trim($signature_column).trim($signature_record_id)));
		$signature = new Signature(0);
		$signature_id = $signature->insert(compact('signature_code', 'signature_class', 'signature_column', 'signature_record_id', 'signature_title', 'signature_validity_second', 'signature_created_date', 'signature_is_used'));
		$signature_link = $app->basePath("signature.php?c=".md5($signature_code)."&i=".md5($signature_id)."&v=".SIGNATURE_LINK_VALIDITY_TIME."&t=".time());
		echo json_encode(array("200", "success|Signature link generated successfully", $signature_link));
	}else
		echo json_encode(array("300",  "danger|Invalid input found."));

?>