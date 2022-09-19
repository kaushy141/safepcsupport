<?php
if(isset($_REQUEST['id']))
{
	//error_reporting(E_ALL);
	//ini_set("display_errors", 1);
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
		//print_r($storeData);die;

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

		$data['tarrif_number']		= 'N/A';
	//echo $data['product_query'];die;
		$sumAmount = $salesinvoice->getInvoiceSum($storeData['store_vat_percent']);
		$data['product_sum_amount']	= "$currency ".number_format($sumAmount['product_sum_amount'],2);
		$data['product_sum_vat']	= "$currency ".number_format($sumAmount['product_sum_vat'],2);
		$data['product_discount']	= "$currency ".number_format($data['sales_discount_amount'],2);
		$data['product_sum_total']	= "$currency ".number_format(($sumAmount['product_sum_vat'] + $sumAmount['product_sum_amount'] - $data['sales_discount_amount']),2);

		$exp_taxid = $storeData['store_tax_number'];
		$exp_taxname = $storeData['store_tax_name'];
		$exp_eorino = $storeData['store_eori_number'];
		$exp_contact_name = $storeData['store_official_name'] ? $storeData['store_official_name'] : $storeData['store_name'];
		$exp_tel_no = $storeData['store_contact'];
		$exp_email = "";
		$exp_address = trim(preg_replace('/\s\s+/', ' ', $storeData['store_address']));
		$exp_country = StoreLocation::getStoreNameByKey($data['sales_invoice_dispatched_store']);
		$manufacturer = $data['sales_invoice_manufacturer'] ?: "N/A";
		$exp_payment_terms = "";
		$exp_purpose_shipment = "";
		$ship_date = $data['invoice_date'];
		$airway_billno = "";
		$invoice_no = $data['invoice_number'];
		$purchase_orderno = $data['invoice_number'];
		$con_taxid = $data['customer_tax_number'];
		$con_contact_name = $data['customer_name'];
		$con_tel_no = $data['customer_phone'];
		$con_email = $data['customer_email'];
		$con_address = $data['customer_full_address'];
		$con_country = $data['customer_address_country'];
		$sold_to_taxid = "";
		$sold_to_address = $data['customer_full_address'];
		$sold_to_country = $data['customer_address_country'];
		$broker_name = "N/A";
		$broker_tel_no = "N/A";
		$broker_contact_name = "N/A";
		$total_no_packages = "1";
		$total_weight = "";
		$terms_of_sale = "";
		$subtotal = $data['product_sum_amount'];
		$insurance = "";
		$freight = "0.00";
		$packing = "0.00";
		$handling = "0.00";
		$other = "0.00";
		$invoice_total = $data['product_sum_total'];
		$special_instructions = "";
		$originator = "";
		$currency_code = "$currency";
		$current_date = date('d/M/Y');
		$data = array_merge($data, compact('exp_taxid', 'exp_taxname', 'exp_eorino',  'exp_contact_name', 'exp_tel_no', 'exp_email', 'exp_address', 'exp_country', 'exp_payment_terms', 'exp_purpose_shipment', 'manufacturer', 'ship_date', 'airway_billno', 'invoice_no', 'purchase_orderno', 'con_taxid', 'con_contact_name', 'con_tel_no', 'con_email', 'con_address', 'con_country', 'sold_to_taxid', 'sold_to_address', 'sold_to_country', 'broker_name', 'broker_tel_no', 'broker_contact_name', 'total_no_packages', 'total_weight', 'terms_of_sale', 'subtotal', 'insurance', 'freight', 'packing', 'handling', 'other', 'invoice_total', 'special_instructions', 'originator', 'currency_code', 'current_date'));

		$report			= new Report("Sales Invoice Commercial",true);
		$report->headerimage = $app->sitePath($storeData['store_logo']);
		$report->address = $storeData['store_address'];
		$report->info_app_contact = $storeData['store_contact'];
		$report->setFilename("Sales Commercial Invoice ".$data["sales_invoice_number"]);
		if($data['sales_invoice_manufacturer'] == ''){
		$report->rightcheck = null;
		}

		if($storeData['store_official_name'] != ""){
			$report->addCopyRight($storeData['store_official_name']);
		}

		$report->addData($data);
		//$report->setPassword('123');
		if($storeData['store_is_vat_disabled'] == 1 || $storeData['store_vat_percent'] == 0)
			$report->setJRXML("sales-invoice-commercial")->generate();
		else
			$report->setJRXML("sales-invoice-commercial")->generate();
	}

}
?>