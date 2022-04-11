<?php
admin();
$md5_refund_id = isset($parameter1) ? $parameter1 : 0;
Modal::load(array('CreditNote', 'Refund'));

$formData = array();
$credit_note_id = 0;
$credit_note_vat = 20;
$credit_note_quantity = 1;
if($md5_refund_id){
	$refund = new Refund();
	$data = $refund->loadByMd5($md5_refund_id);
	$formData = array_merge($formData, array(
		'credit_note_refund_id'			=> $data['refund_id'],
		'credit_note_item_description' 	=> $data['refund_reference'],
		'credit_note_quantity' 			=> $credit_note_quantity,
		'credit_note_reference'			=> $data['refund_reference'],
		'credit_note_currency'			=> $data['refund_amount_currency'],
		'credit_note_amount'			=> $data['refund_amount'],
		'credit_note_vat'				=> $credit_note_vat,
		'credit_note_amount_total'		=> $credit_note_quantity * round($data['refund_amount'] + (($data['refund_amount'] * $credit_note_vat)/100), 2)
	));	
	//prd($formData);
	extract($formData);
}
$action	=	"creditnote/savecreditnotes";
$formHeading	=	"Create Credit Note";
?>
<?php include("engine/inc/addcreditnoteform.php"); ?>