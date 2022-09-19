<?php
if(isset($_REQUEST['id']))
{
	$sales_invoice_id	= intval($_REQUEST['id']);
	$salesinvoice		= new SalesInvoice($sales_invoice_id);
	$data 				= $salesinvoice->load();
	if(1 || $data['sales_invoice_is_generated'] == 1 || $data['sales_invoice_is_estimate'])
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

		$data['margin_vat_label'] = $data['sales_invoice_margin_vat_label'] ? "(Margin VAT Scheme)" : "";

		$appInfo = 	new AppInfo();
		$info = $appInfo->getDetails();
		if($data['sales_invoice_bank_account']){
			if($bankDetails = $store->getBankDetails($data['sales_invoice_bank_account'])){
				$data['bank_detail']	= 	nl2br($bankDetails["account_name"]);
			}
		}
		else if(trim($storeData['store_bank_details']) != "")
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
		$data['invoice_date'] 		= 	date("d/m/Y", strtotime($data["sales_invoice_date"]));
		$data['sales_payment_method'] = $data['sales_invoice_payment_mode'];
		$data['invoice_vat'] = $storeData['store_vat_percent'];
		//echo $data['product_query'];die;
		$sumAmount = $salesinvoice->getInvoiceSum($storeData['store_vat_percent']);
		//echo $storeData['store_vat_percent'];
		//print_r($sumAmount);die;
		$data['product_sum_amount']	= "$currency ".number_format($storeData['store_is_vat_disabled'] ? ($sumAmount['product_sum_amount'] + $sumAmount['product_sum_vat']) : $sumAmount['product_sum_amount'],2);
		$data['product_sum_vat']	= "$currency ".number_format($sumAmount['product_sum_vat'],2);
		$data['product_discount']	= "$currency ".number_format($data['sales_discount_amount'],2);
		$data['product_sum_total']	= "$currency ".number_format(($sumAmount['product_sum_vat'] + $sumAmount['product_sum_amount'] - $data['sales_discount_amount']),2);
		$data['sales_payment_remain_amount']	= "$currency ".number_format(($sumAmount['product_sum_vat'] + $sumAmount['product_sum_amount'] - ($data['sales_discount_amount'] + $data['sales_payment_paid_amount'])),2);
		$data['sales_payment_paid_amount']	= "$currency ".number_format($data['sales_payment_paid_amount'],2);

		$qrCodeData = array(
			"Tax Id of supplier" => $storeData['store_tax_number'],
			"Tax Id of Recipient" => $data['customer_tax_number'],
			"Invoice number" => $data['invoice_number'],
			"Date of generation of invoice" => date("Y-m-d", strtotime($data["sales_invoice_date"])),
			"Invoice value" => $data['product_sum_total'],
			"Invoice in currency" => $currency,
			"Number of line items" => $sumAmount['items'],
			"HSN Code of main item" => null,
			"Unique Invoice Reference Number" => $data['invoice_number'].'-'.$currency
		);
		$data['qrcode'] = INVOICE_QR_CODE_IMAGE_PATH;
		copy($app->basePath('qrcode.php?data='.urlencode(json_encode($qrCodeData))), INVOICE_QR_CODE_IMAGE_PATH);
		$report			= new Report("Sales $invoice_type_name",true);
		$report->headerimage = $app->sitePath($storeData['store_logo']);
		$report->address = $storeData['store_address'];
		$report->info_app_contact = $storeData['store_contact'];
		$report->setFilename("Sales Invoice ".$data["sales_invoice_number"]);
		$report->addData($data);
		//$report->setPassword('123');

		if($storeData['store_official_name'] != ""){
			$report->addCopyRight($storeData['store_official_name']);
		}

		if($data['sales_invoice_is_generated'] == 0 && $data['sales_invoice_is_estimate'] == 0)
			$report->watermark	=	$app->sitePath("/img/report-preview.png");
		else
			$report->watermark 	= 	null;
		if($storeData['store_is_vat_disabled'] == 1 || $storeData['store_vat_percent'] == 0)
			$report->setJRXML("sales-invoice-no-vat")->generate();
		else
			$report->setJRXML("sales-invoice")->generate();
	}

}
?>