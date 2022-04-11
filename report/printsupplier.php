<?php
$supplier_id = intval($_REQUEST['id']);
$supplier	= new Supplier($supplier_id);
if($supplier->isExist()){
	$supplierData = $supplier->getDetails();			
	$report	= new Report("Supplier $supplierData[supplier_name]", true);			
	$report->addData(array("supplier_id"=>$supplier_id));
	$report->setJRXML("print-supplier")->generate();
}
?>