<?php
$Utilities	= new Utilities(intval($_REQUEST['id']));
if($Utilities->isExist()){
	$carrier = new Carrier(1);
	$carrierData = $carrier->getDetails();
	$report	= new Report("Utilities document",true);
	$data = array(
		"utility_id" => intval($_REQUEST['id'])
	);
	$report->addData(array_merge($data, $carrierData));
	$report->setJRXML("print-utilities")->generate();
}
?>