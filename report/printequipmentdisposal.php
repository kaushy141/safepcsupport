<?php
$DestructionMethod	= new DestructionMethod(intval($_REQUEST['id']));
if($DestructionMethod->isExist())
{
	$DestructionMethodData = $DestructionMethod->getDetails();
	$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
	file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($DestructionMethodData['eqipment_disposal_log_no'], $generatorSVG::TYPE_CODE_128));
	$carrier = new Carrier(1);
	$carrierData = $carrier->getDetails();
	$report	= new Report("Equiment Disposal $DestructionMethodData[eqipment_disposal_log_no]",true);
	$data = array(
		"eqipment_disposal_id" => intval($_REQUEST['id']),
		"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
	);
	$report->addData(array_merge($data, $carrierData));
	$report->setJRXML("print-equipment-disposal")->generate();
}
?>