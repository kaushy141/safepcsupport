<?php
//error_reporting(1);
if(isset($_REQUEST['id']))
{
	Modal::load(array('Po', 'PoInvoice'));	
	$po_id	= $_REQUEST['id'];
	$poInvoice = new PoInvoice($po_id);
	$data = $poInvoice->getDetails();
	
	if($data['po_is_approved'] == 1)
	{		
		$data['supplier_address'] = $data['supplier_name'].'<br/>'.$data['supplier_address'];
				
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['pob_code'], $generatorSVG::TYPE_CODE_128));
				
		$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
				
		$appInfo = 	new AppInfo();
		$info = $appInfo->getDetails();	
		
		$currency = $data['po_currency'];
		
		$data['invoice_query']		= 	$poInvoice->getPobItemQuery();
		$data['pob_invoice_date'] 	= 	date("d/m/Y", strtotime($data["pob_invoice_date"]));	
		
		
		$sumAmount = $poInvoice->getPoItemSum();
		$data['pob_sub_total']	= "$currency ".number_format($sumAmount['pob_sub_total'],2);
		$data['pob_vat_total']	= "$currency ".number_format($sumAmount['pob_vat_total'],2);
		$data['pob_grand_total']	= "$currency ".number_format(($sumAmount['pob_sub_total'] + $sumAmount['pob_vat_total']),2);		
		$data['pob_after_discount_total']	= "$currency ".number_format((($sumAmount['pob_sub_total'] + $sumAmount['pob_vat_total']) - $data['pob_amount_discount'] ),2);	
		$data['pob_amount_discount']	= "$currency ".number_format($data['pob_amount_discount'],2);		
		$qrCodeData = array(
			"Supplier" => $data['supplier_name'],
			"Number" => $data['pob_code'],
			"Date" => $data["pob_order_date"],
			"Value" => number_format(($sumAmount['pob_sub_total'] + $sumAmount['pob_vat_total']),2),
			"Currency" => $currency,
			"Items" => $sumAmount['pob_total_quantity']
		);
		$data['qrcode'] = INVOICE_QR_CODE_IMAGE_PATH;
		copy($app->basePath('qrcode.php?data='.urlencode(json_encode($qrCodeData))), INVOICE_QR_CODE_IMAGE_PATH);
		
		$report			= new Report("Purchase order {$data['po_code']}-{$data['pob_code']}",true);	
		$report->headerimage = $app->sitePath($data['store_logo']);
		$report->address = $data['store_address'];
		$report->info_app_contact = $data['store_contact'];
		$report->setFilename("Purchase Order Invoice {$data['po_code']}_{$data['pob_code']}");
		$report->addData($data);
		$report->setJRXML("purchase_order_invoice")->generate();
	}
	else{
		echo 'Not Approved yet';
	}
	
}
?>