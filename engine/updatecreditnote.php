<?php
admin();
$md5_credit_note_id = isset($parameter1) ? $parameter1 : 0;
Modal::load(array('CreditNote'));

$formData = array();
$credit_note_id = 0;
$credit_note_vat = 20;
if($md5_credit_note_id){
	$creditNote = new CreditNote();
	$data = $creditNote->loadByMd5($md5_credit_note_id);	
	extract($data);
	$action	=	"creditnote/savecreditnotes";
	$formHeading	=	"#$credit_note_code Update Credit Note";
}
?>
<?php include("engine/inc/addcreditnoteform.php"); ?>