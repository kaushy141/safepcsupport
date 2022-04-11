<?php
$report			= new Report("Product Single Slip",true);
$Product	= new Product(isset($_REQUEST['id'])?$_REQUEST['id']:0);
$data  = $Product->load();
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($data['product_id'], $generatorSVG::TYPE_CODE_128));
$data['barcode']		= 	$app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH);
$report->query 	= $Product->getReportSqlSlip(1);
$report->addData($data);
$report->setJRXML("product-single-label-print")->generate();
?>