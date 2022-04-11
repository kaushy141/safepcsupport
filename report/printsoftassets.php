<?php
$SoftwareAssets	= new SoftwareAssets(intval($_REQUEST['id']));
if($SoftwareAssets->isExist()){
	$carrier = new Carrier(1);
	$carrierData = $carrier->getDetails();
	$report	= new Report("Software Assets ",true);
	$data = array(
		"soft_asset_id" => intval($_REQUEST['id'])
	);
	$report->addData(array_merge($data, $carrierData));
	$report->setJRXML("print-software-assets")->generate();
}
?>