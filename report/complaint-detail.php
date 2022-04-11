<?php
if(isset($_REQUEST['id']))
{
	$cmt			= new Complaint($_REQUEST['id']);	
	$data 			= $cmt->loadInvoice();	
	$generatorSVG   = new Picqer\Barcode\BarcodeGeneratorPNG();			
	file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['complaint_ticket_number'], $generatorSVG::TYPE_CODE_128));
	$barcode		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
	
	$store = new Store($data['complaint_store_id']);
	$storeData = $store->getDetails();
	
	$report			= new Report($cmt->get("complaint_ticket_number")." - Repair Request Detail");
	$report->headerimage = $app->sitePath($storeData['store_logo']);
	$report->query 	= $cmt->getReportComplaintDetials();		
	$jrxml = isset($_REQUEST['type'])?"complaint-detail-multi":"complaint-detail-single";
	$report->addData(array("barcode"=>$barcode));			
	$report->setJRXML($jrxml)->generate();
}
?>