<?php
if(isset($parameter1))
{
	$supplier_id = $parameter1;
	$supplier = new Supplier($supplier_id);
	$data = $supplier->load();
	if($data)
	{
		extract($data);
		$action	=	"company_resource/updatesupplierrecord";
		$formHeading	=	"Update Supplier #$data[supplier_name]";
		$btnText	=	"Update supplier record";
		include("engine/inc/addsupplierform.php");
	}
	else
		include("engine/404.php");
}
?>