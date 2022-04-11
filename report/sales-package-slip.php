<?php
if(isset($_REQUEST['id']))
{			
	$sales_invoice_id	= intval($_REQUEST['id']);
	$salesinvoice		= new SalesInvoice($sales_invoice_id);
	$data 				= $salesinvoice->load();
	if($data['sales_invoice_is_generated'] == 1 || $data['sales_invoice_is_estimate'])
	{
		$data['invoice_number'] = $data['sales_invoice_number'];
		$data['invoice_to'] = $data['customer_name']."<br/>Email: ".$data['customer_email']." | Mob: ".$data['customer_phone']. (($data['customer_company']!="")? "<br/>Company: $data[customer_company]":"");
		$data['ship_to'] 	=	$data['customer_full_address'];
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['sales_invoice_number'], $generatorSVG::TYPE_CODE_128));
		$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
		
		$invoice_type_name = $data['sales_invoice_is_estimate'] ? "Estimate" : "Invoice";			
		
		if($data['sales_invoice_is_paid'] == 1)
		$data['paidimage'] = $app->sitePath(INVOICE_PAID_IMAGE_PATH);
		
		$store = new Store($data['sales_invoice_store_id']);
		$storeData = $store->getDetails();
		
		if($data['sales_invoice_is_vat_applicable'] == 1)
		{
			$storeData['store_vat_percent'] = $data['sales_invoice_vat'] ? $data['sales_invoice_vat'] : ($storeData['store_vat_percent'] ? $storeData['store_vat_percent'] : SALES_VAT_PERCENTAGE);
			$storeData['store_is_vat_disabled'] = 0;
		}
		else
		{
			$storeData['store_vat_percent'] = 0;
			$storeData['store_is_vat_disabled'] = 1;
		}
		
		$appInfo = 	new AppInfo();
		$info = $appInfo->getDetails();	
		
		if(trim($storeData['store_bank_details']) != "")
			$data['bank_detail']	= 	nl2br($storeData["store_bank_details"]);
		else
			$data['bank_detail']	= 	nl2br($info["info_app_bank_details"]);			
		
		$currency = $data['sales_invoice_currency'] ? $data['sales_invoice_currency'] : 'GBP';
		
		$data['acknowledge'] 		= 	$info["info_app_invoice_acknowledge"];
		$data['telephone']			= 	$info["info_app_contact"];
		$data['happytext']			=	$info["info_app_invoice_happytext"];			
		$data['terms']				= 	$info["info_app_invoice_terms"];
		$data['product_query']		= 	$salesinvoice->geInvoiceProductsQuery($currency, $storeData['store_vat_percent']);
		$data['invoice_type_name']	= 	$invoice_type_name;
		$data['invoice_date'] 		= 	date("d/m/Y", strtotime($data["sales_invoice_created_date"]));
		
		
	//echo $data['product_query'];die;
		$sumAmount = $salesinvoice->getInvoiceSum($storeData['store_vat_percent']);
		$data['product_sum_amount']	= "$currency ".number_format($sumAmount['product_sum_amount'],2);
		$data['product_sum_vat']	= "$currency ".number_format($sumAmount['product_sum_vat'],2);
		$data['product_discount']	= "$currency ".number_format($data['sales_discount_amount'],2);
		$data['product_sum_total']	= "$currency ".number_format(($sumAmount['product_sum_vat'] + $sumAmount['product_sum_amount'] - $data['sales_discount_amount']),2);
		
		
		
		$report			= new Report("Sales $invoice_type_name",true);	
		$report->headerimage = $app->sitePath($storeData['store_logo']);
		$report->address = $storeData['store_address'];
		$report->info_app_contact = $storeData['store_contact'];
		$report->setFilename("Sales Invoice ".$data["sales_invoice_number"]);
		$report->addData($data);
		$report->setJRXML("sales-packaging-slip")->generate();
	}
	
}
?>