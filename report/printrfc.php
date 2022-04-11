<?php
$Rfc	= new Rfc(intval($_REQUEST['id']));
if($Rfc->isExist())
{
	$RfcData = $Rfc->getDetails();
	$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
	file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($RfcData['rfc_code'], $generatorSVG::TYPE_CODE_128));
	$carrier = new Carrier(1);
	$carrierData = $carrier->getDetails();
	$report	= new Report("RFC $RfcData[rfc_code]",true);
	$data = array(
		"rfc_id" => intval($_REQUEST['id']),
		"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
	);
	$report->addData(array_merge($data, $carrierData));
	$report->setJRXML("print-rfc")->generate();
}
?>