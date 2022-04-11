<?php
$ContractInsurance	= new ContractInsurance(intval($_REQUEST['id']));
if($ContractInsurance->isExist())
{
	$carrier = new Carrier(1);
	$carrierData = $carrier->getDetails();
	$report	= new Report("Software Assets ",true);
	$data = array(
		"cont_ins_id" => intval($_REQUEST['id'])
	);
	$report->addData(array_merge($data, $carrierData));
	$report->setJRXML("print-contract-insurance")->generate();
}
?>