<?php
if(isset($_REQUEST['id']))
{			
	$complaint_id	= intval($_REQUEST['id']);
	$cmt			= new Complaint($complaint_id);
	$data 			= $cmt->loadPrint();
	$data['complaint_description']	.=	"<br/>".$data['problem_list'];
	
	$store = new Store($data['complaint_store_id']);
	$storeData = $store->getDetails();
	
	$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
	file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['complaint_ticket_number'], $generatorSVG::TYPE_CODE_128));
	$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
	
	$appInfo = 	new AppInfo();
	$info = $appInfo->getDetails();	
	$data['info_app_address']		= 	$storeData['store_address'];
	$data['info_disclaimer']		=	nl2br(evalString($info['info_app_disclaimer'], "store_name", $storeData['store_title'],"b"));
	$data['print_date']				=	date('d M-Y');	
	$data['under_waranty']			= 	$data['under_waranty']? "Yes" : "No";
	$data['disk_provided']			= 	$data['disk_provided']? "Yes" : "No";
	$data['complaint_is_backup']	= 	$data['complaint_is_backup']? "Yes" : "No";
	$data['estimated_cost'] = CURRENCY.' '.$data['estimated_cost'];
	
	$hardwareT = new HardwareType();
	$data['hardware_not_working']	=	$hardwareT->simplelistName(array_filter(explode(",",$data["complaint_product_hardware_not_working"])));	
				
	$report			= new Report("Complaint Print Details",true);	
	$report->headerimage = $app->sitePath($storeData['store_logo']);
	$report->address = $storeData['store_address'];
	$report->setFilename("Complaint Print Details ".$data["invoice_ticket"]);
	$report->addData($data);
	$report->setJRXML("complaint-print")->generate();
}
?>