<?php
$PhysicalAsset	= new PhysicalAsset(intval($_REQUEST['id']));
if($PhysicalAsset->isExist())
{
	$phycData = $PhysicalAsset->getDetails();
	$generatorSVG = new Picqer\Barcode\BarcodeGeneratorPNG();			
	file_put_contents(INVOICE_BAR_CODE_IMAGE_PATH, $generatorSVG->getBarcode($phycData['asset_tag'], $generatorSVG::TYPE_CODE_128));
	$carrier = new Carrier(1);
	$carrierData = $carrier->getDetails();
	$report	= new Report("Physical Assets $phycData[asset_tag]",true);
	$data = array(
		"asset_id" => intval($_REQUEST['id']),
		"barcode" => $app->sitePath(INVOICE_BAR_CODE_IMAGE_PATH),
	);
	$report->addData(array_merge($data, $carrierData));
	$report->setJRXML("print-physical-assets")->generate();
}
?>