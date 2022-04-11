<?php
error_reporting(1);
if(isset($_GET['id']))
{
	Modal::load(array('Po'));	
	$po_id	= $_GET['id'];
	$po = new Po($po_id);
	$data = $po->getDetails();
	
		$supplier = new Supplier($data['po_suplier_id']);
		$supplierData = $supplier->getDetails();
		
		$data['po_shipping_address'] = $supplierData['supplier_name'].'<br/>'.$supplierData['po_shipping_address'];
		$data['store_address'] = $data['store_name'].'<br/>'.$supplierData['store_address'];
		
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['po_code'], $generatorSVG::TYPE_CODE_128));
				
		$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);		
		
		$store = new Store($data['po_store_id']);
		$storeData = $store->getDetails();
		
		$appInfo = 	new AppInfo();
		$info = $appInfo->getDetails();	
		
		$currency = $data['po_currency'];
		
		$data['invoice_query']		= 	$po->getPoItemQuery();
		$data['po_order_date'] 		= 	date("d/m/Y", strtotime($data["po_order_date"]));	
		
		
		$sumAmount = $po->getPoItemSum();
		$data['po_sub_total']	= "$currency ".number_format($sumAmount['po_sub_total'],2);
		$data['po_vat_total']	= "$currency ".number_format($sumAmount['po_vat_total'],2);
		$data['po_grand_total']	= "$currency ".number_format(($sumAmount['po_sub_total'] + $sumAmount['po_vat_total']),2);		
		$data['po_after_discount_total']	= "$currency ".number_format((($sumAmount['po_sub_total'] + $sumAmount['po_vat_total']) - $data['po_amount_discount'] ),2);	
		$data['po_amount_discount']	= "$currency ".number_format($data['po_amount_discount'],2);		
		$qrCodeData = array(
			"Supplier" => $supplierData['supplier_name'],
			"Number" => $data['po_code'],
			"Date" => $data["po_order_date"],
			"Value" => number_format(($sumAmount['po_sub_total'] + $sumAmount['po_vat_total']),2),
			"Currency" => $currency,
			"Items" => $sumAmount['po_total_quantity']
		);
		$data['qrcode'] = INVOICE_QR_CODE_IMAGE_PATH;
		copy($app->basePath('qrcode.php?data='.urlencode(json_encode($qrCodeData))), INVOICE_QR_CODE_IMAGE_PATH);
		
		$report			= new Report("Purchase order {$data['po_code']}",true);	
		$report->headerimage = $app->sitePath($storeData['store_logo']);
		$report->address = $storeData['store_address'];
		
		if($data['po_is_approved'] == 0)
		$report->watermark	=	$app->sitePath("/img/report-preview.png");
		
		$report->info_app_contact = $storeData['store_contact'];
		$report->setFilename("Purchase Order {$data['po_code']}");
		$report->addData($data);
		$report->setJRXML("purchase_order")->generate();
	
	
}
?>