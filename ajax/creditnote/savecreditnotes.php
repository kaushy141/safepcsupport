<?php
	Modal::load(array('CreditNote', 'Refund'));

    $credit_note_id = $credit_note_refund_id = $credit_note_created_by = 0;
	$credit_note_date = $credit_note_reference = $credit_note_item_description = $credit_note_quantity = $credit_note_amount = $credit_note_currency = $credit_note_vat = $credit_note_remaining = $credit_note_created_date = '';
    $data           = sanitizePostData($_POST);
    extract($data);
    
	$creditNote = new CreditNote($credit_note_id);
	
	if($credit_note_id == 0 && $creditNote->isCreditNoteExist($credit_note_refund_id))
	{
		echo json_encode(array("300",  "warning|Credit not already exist for this refund"));
		die;
	}
	$credit_note_amount_total = ($credit_note_quantity * $credit_note_amount) + ($credit_note_quantity * (($credit_note_amount * $credit_note_vat)/100));
	$creditData = array(
		"credit_note_date" 					=> $credit_note_date,
		"credit_note_reference" 			=> $credit_note_reference,
		"credit_note_item_description" 		=> $credit_note_item_description,
		"credit_note_quantity"				=> $credit_note_quantity,
		"credit_note_currency" 				=> $credit_note_currency,
		"credit_note_amount"				=> $credit_note_amount,
		"credit_note_amount_total"			=> $credit_note_amount_total,
		"credit_note_vat"					=> $credit_note_vat,
		"credit_note_remaining"				=> $credit_note_remaining
		);
		
	if($credit_note_id == 0){
		$refund  = new Refund($credit_note_refund_id);
		$refundData = $refund->getDetails();
		$credit_note_code = $creditNote->getCreditNoteCode();
		$creditData = array_merge($creditData, array(
			"credit_note_code"				=> $credit_note_code,
			"credit_note_refund_id" 		=> $credit_note_refund_id,
			"credit_note_store_id" 			=> $refundData['refund_store_id'],
			"credit_note_created_by"		=> getLoginId(),
			"credit_note_created_date"		=> 'NOW()'
		));
		$credit_note_id = $creditNote->insert($creditData);
		Activity::add("Initiated Credit Note for $credit_note_reference|^|$credit_note_code", "N", $credit_note_id);
		echo json_encode(array("200",  "success|Credit note initiated", $credit_note_id));	
	}
	else{
		$credit_note_code = $creditNote->get('credit_note_code');
		$creditNote->update($creditData);
		Activity::add("Updated Credit Note for $credit_note_reference|^|$credit_note_code", "N", $credit_note_id);
		echo json_encode(array("200",  "success|Credit note updated", $credit_note_id));	
	}	

?>