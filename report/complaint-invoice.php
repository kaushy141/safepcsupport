<?php
if(isset($_REQUEST['id']))
{			
	$complaint_id	= intval($_REQUEST['id']);
	$cmt			= new Complaint($complaint_id);
	$data 			= $cmt->loadInvoice();
	$data['description']	.=	"<br/>".$data['problem_list'];
	$data['quantity']		= 	max($data['quantity'],1);	
	$data['amount']			= 	number_format($data['amount'],2);
	$data['rate']			= 	$data['amount'];
	
	$store = new Store($data['complaint_store_id']);
	$storeData = $store->getDetails();
	
	$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
	file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['complaint_ticket_number'], $generatorSVG::TYPE_CODE_128));
	$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
	
	$appInfo = 	new AppInfo();
	$info = $appInfo->getDetails();	
	
	if(trim($storeData['store_bank_details']) != "")
		$data['bank_detail']	= 	nl2br($storeData["store_bank_details"]);
	else
		$data['bank_detail']	= 	nl2br($info["info_app_bank_details"]);		
		
	$data['acknowledge'] 	= 	$info["info_app_invoice_acknowledge"];
	$data['telephone']		= 	$info["info_app_contact"];
	$data['happytext']		=	$info["info_app_invoice_happytext"];			
	$data['terms']			= 	$info["info_app_invoice_terms"];
	
	$complaitTax = new ComplaintTax($data['complaint_tax_id']);
	$taxData = $complaitTax->getDetails();

	$taxData['complaint_vat_tax']	= number_format($taxData['complaint_vat_tax'],2);
	$taxData['complaint_e_tax']		= number_format($taxData['complaint_e_tax'],2);
	$taxData['complaint_z_tax']		= number_format($taxData['complaint_z_tax'],2);
	$taxData['complaint_n_tax']		= number_format($taxData['complaint_n_tax'],2);
	$taxData['complaint_r_tax']		= number_format($taxData['complaint_r_tax'],2);
	$taxData['complaint_s_tax']		= number_format($taxData['complaint_s_tax'],2);

	$taxData['complaint_vat_tax_middle']	= number_format(($data['amount']*$taxData['complaint_vat_tax'])/100,2);
	$taxData['complaint_e_tax_middle']		= number_format(($data['amount']*$taxData['complaint_e_tax'])/100,2);
	$taxData['complaint_z_tax_middle']		= number_format(($data['amount']*$taxData['complaint_z_tax'])/100,2);
	$taxData['complaint_n_tax_middle']		= number_format(($data['amount']*$taxData['complaint_n_tax'])/100,2);
	$taxData['complaint_r_tax_middle']		= number_format(($data['amount']*$taxData['complaint_r_tax'])/100,2);
	$taxData['complaint_s_tax_middle']		= number_format(($data['amount']*$taxData['complaint_s_tax'])/100,2);
	
	$data['sub_total']			= number_format(($data['amount']+$taxData['complaint_vat_tax_middle']),2);
	$data['vat_total']			= number_format(($taxData['complaint_e_tax_middle']+$taxData['complaint_z_tax_middle']+$taxData['complaint_n_tax_middle']+$taxData['complaint_r_tax_middle']+$taxData['complaint_s_tax_middle']),2);
	
	$data['total']				= number_format(($data['amount'] +$taxData['complaint_vat_tax_middle']+ $data['vat_total']),2);	
	$report			= new Report("Complaint Invoice",true);	
	$report->headerimage = $app->sitePath($storeData['store_logo']);
	$report->address = $storeData['store_address'];
	$report->info_app_contact = $storeData['store_contact'];
	$report->setFilename("Repair Request Invoice ".$data["invoice_ticket"]);
	$report->addData($data);
	$report->addData($taxData);
	$report->setJRXML("complaint-invoice")->generate();
}
?>