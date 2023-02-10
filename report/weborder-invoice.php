<?php
if(isset($_REQUEST['id']))
{
	$wo_invoice_id	= intval($_REQUEST['id']);
	$weborder		= new WebsiteOrder($wo_invoice_id);
	$data 			= $weborder->load();
	//print_r($data);
	if(1 || $data['web_order_is_paid'] == 1)
	{
		$data['invoice_number'] = $data['web_order_number'];
		$data['invoice_to'] = $data['customer_name']."<br/>Email: ".$data['customer_email']." | Mob: ".$data['customer_phone']. (($data['customer_company']!="")? "<br/>Company: $data[customer_company]":"");
		$data['ship_to'] 	=	$data['customer_full_address'];
		$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();
		file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['web_order_number'], $generatorSVG::TYPE_CODE_128));

		$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);

		$invoice_type_name = "Invoice";

		if($data['web_order_is_paid'] == 1)
		$data['paidimage'] = $app->sitePath(INVOICE_PAID_IMAGE_PATH);

		$store = new Store($data['web_order_website_id']);
		$storeData = $store->getDetails();		

		$storeData['store_vat_percent'] = $storeData['store_vat_percent'] ? $storeData['store_vat_percent'] : SALES_VAT_PERCENTAGE;
		$storeData['store_is_vat_disabled'] = 0;

		$data['margin_vat_label'] = "";

		$appInfo = 	new AppInfo();
		$info = $appInfo->getDetails();
		if(trim($storeData['store_bank_details']) != "")
			$data['bank_detail']	= 	nl2br($storeData["store_bank_details"]);
		else
			$data['bank_detail']	= 	nl2br($info["info_app_bank_details"]);

		$currency = $data['web_order_currency'] ? $data['web_order_currency'] : 'GBP';

		$data['acknowledge'] 		= 	$info["info_app_invoice_acknowledge"];
		$data['telephone']			= 	$info["info_app_contact"];
		$data['happytext']			=	$info["info_app_invoice_happytext"];
		$data['terms']				= 	$info["info_app_invoice_terms"];
		$data['product_query']		= 	$weborder->geInvoiceProductsQuery($currency, $storeData['store_vat_percent']);
		$data['invoice_type_name']	= 	$invoice_type_name;
		$data['invoice_date'] 		= 	date("d/m/Y", strtotime($data["web_order_created_date"]));
		$data['wo_payment_method']  =   $data['web_order_payment_method'];
		$data['shipping_charges']  =   "$currency ".number_format($data['web_order_total_shipping'],2);;
		$data['invoice_vat'] = $storeData['store_vat_percent'];
		//echo $data['product_query'];die;
		$sumAmount = $weborder->getInvoiceSum($storeData['store_vat_percent']);
		//echo $storeData['store_vat_percent'];
		//print_r($sumAmount);die;
		$data['wo_discount_amount'] = 0;
		$data['product_sum_amount']	= "$currency ".number_format($storeData['store_is_vat_disabled'] ? ($sumAmount['product_sum_amount'] + $sumAmount['product_sum_vat']) : $sumAmount['product_sum_amount'],2);
		$data['product_sum_vat']	= "$currency ".number_format($sumAmount['product_sum_vat'],2);
		$data['product_discount']	= "$currency ".number_format($data['wo_discount_amount'],2);
		$data['product_sum_total']	= "$currency ".number_format(($sumAmount['product_sum_vat'] + $sumAmount['product_sum_amount'] + $data['web_order_total_shipping'] - $data['wo_discount_amount']),2);
		$data['wo_payment_remain_amount']	= "$currency ".number_format(0,2);
		$data['wo_payment_paid_amount']	= "$currency ".number_format($data['web_order_total_amount'],2);

		$qrCodeData = array(
			"Tax Id of supplier" => $storeData['store_tax_number'],
			"Tax Id of Recipient" => $data['customer_tax_number'],
			"Invoice number" => $data['web_order_number'],
			"Date of generation of invoice" => date("Y-m-d", strtotime($data["web_order_created_date"])),
			"Invoice value" => $data['product_sum_total'],
			"Invoice in currency" => $currency,
			"Number of line items" => $sumAmount['items'],
			"HSN Code of main item" => null,
			"Unique Invoice Reference Number" => $data['web_order_number'].'-'.$currency
		);
		$data['qrcode'] = INVOICE_QR_CODE_IMAGE_PATH;
		copy($app->basePath('qrcode.php?data='.urlencode(json_encode($qrCodeData))), INVOICE_QR_CODE_IMAGE_PATH);
		$report			= new Report("Order $invoice_type_name",true);
		$report->headerimage = $app->sitePath($storeData['store_logo']);
		$report->address = $storeData['store_address'];
		$report->info_app_contact = $storeData['store_contact'];
		$report->setFilename("Order $invoice_type_name ".$data["web_order_number"]);
		$report->addData($data);
		//$report->setPassword('123');

		if($storeData['store_official_name'] != ""){
			$report->addCopyRight($storeData['store_official_name']);
		}

		$report->watermark 	= 	null;
		if($storeData['store_is_vat_disabled'] == 1 || $storeData['store_vat_percent'] == 0)
			$report->setJRXML("weborder-invoice")->generate();
		else
			$report->setJRXML("weborder-invoice")->generate();
	}

}
?>