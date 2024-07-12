<?php
$wc_id	= intval($_REQUEST['id']);
$type	= isset($_REQUEST['type']) && $_REQUEST['type'] == 'N' ? false : true;
$collection			= new Collection($wc_id);
$wcData = $collection->getDetails();

$carrier = new Carrier($wcData['wc_carrier_id']);
$carrierData	= $carrier->getDetails();

$customer = new Customer($wcData['wc_customer_id']);
$customerData = $customer->getDetails();

$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($wcData['wc_code'], $generatorSVG::TYPE_CODE_128));
$barcode		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);	

$collMgr = $collection->getCollectionManager($wcData['wc_manager_id']);

$customerAddress = new CustomerAddress($wcData['wc_customer_address_id']);
$customerAddressData = $customerAddress->getDetails();

$company = new Company(10000001);
$companyData = $company->getDetails();

$appInfo = 	new AppInfo();
$info = $appInfo->getDetails();	
		
$company_details_data = $companyData['company_name']. ", ".$carrierData['carrier_address']
	. "<br/>".str_replace("|", "<br/>", $companyData['company_contact'])
	. "<br/>"."Registered in England and Wales : ".$companyData['company_registered_in_england_and_wales']
	. "<br/>"."Environment Agency Registered Firm : ".$carrierData['carrier_environment_number']
	. "<br/>"."Waste Carrier's License : ".$carrierData['carrier_licence_number']
	. "<br/>"."VAT No : ".$carrierData['carrier_vat_number'];

$report			= new Report("Collection Certificate $wcData[wc_code]",true);
$report->addData($collMgr);
$report->addData(array("consigner_name"=>$customerData['customer_name'], "company_name"=>$customerData['customer_company'], "consigner_address"=>$customerAddressData['full_address'],"collection_wc_code"=>$wcData['wc_code'],"certificate_representative"=>"Gemma Worsell","collection_date"=>date("dS M Y", strtotime($wcData['wc_due_date'])), "barcode"=>$barcode, "company_details_data"=>$company_details_data));
//$report->watermark	=	$app->sitePath("/img/spd_report_background.png");
if($type)
$report->setPassword(getPdfPassword($customerAddressData['customer_address_postcode']));
//$report->setPassword('123');
$report->watermark	=	$app->sitePath("/img/spd-collection-purge-certificate.png");
$report->setJRXML("collection-certificate")->generate();
?>