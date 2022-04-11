<?php
if(isset($_REQUEST['id']))
{
	Modal::load(array('CreditNote'));
	$md5_credit_note_id	= $_REQUEST['id'];
	$creditNote = new CreditNote();
	$data = $creditNote->loadByMd5($md5_credit_note_id);	
	if($data)
	{
		//SELECT `credit_note_id`, `credit_note_code`, `credit_note_refund_id`, `credit_note_date`, `credit_note_reference`, `credit_note_item_description`, `credit_note_quantity`, `credit_note_amount`, `credit_note_currency`, `credit_note_vat`, `credit_note_remaining`, `credit_note_created_by`, `credit_note_created_date` FROM `app_refund_credit_notes` WHERE 1	
		
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['credit_note_code'], $generatorSVG::TYPE_CODE_128));
		$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
		
		$store = new Store($data['credit_note_store_id']);
		$storeData = $store->getDetails();		
				
		$appInfo = 	new AppInfo();
		$info = $appInfo->getDetails();	
		
		$currency = $data['credit_note_currency'];
		$data['credit_note_query']	= 	$creditNote->getCreditNoteQuery();		
		$data['credit_date'] 		= 	date("d/m/Y", strtotime($data["credit_note_date"]));
		$data['credit_reference']	= $data['credit_note_reference'];
		$data['credit_code']	= $data['credit_note_code'];
		
		$data['sub_total']	= "$currency ".number_format($data['credit_note_amount'],2);
		$data['vat_percent']	= $data['credit_note_vat'];
		$data['vat_total']	= "$currency ".number_format($data['credit_note_quantity']*(($data['credit_note_amount'] * $data['credit_note_vat'])/100),2);
		$data['grand_total']	= "$currency ".number_format($data['credit_note_amount'],2);
		$data['remaing_credit']	= "$currency ".number_format($data['credit_note_remaining'],2);
		
		$data['address_from'] = $storeData['store_contact']."<br/>".$storeData['store_address'];
		
		
		$report			= new Report("Credit Note $data[credit_note_code]",true);	
		$report->headerimage = $app->sitePath($storeData['store_logo']);
		$report->setFilename("Credit Note $data[credit_note_code]");
		$report->addData($data);
		$report->setJRXML("credit-note")->generate();
	}
	else
		echo "NO Data";
	
}
?>