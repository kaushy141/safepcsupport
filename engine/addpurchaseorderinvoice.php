<?php
if(isset($parameter1))
{
	Modal::load(array('Po'));
	$po_id = $parameter1;
	$po = new Po($po_id);
	$data = $po->getDetails();
	//print_r($data);
	if($data)
	{		
		extract($data);
		$poItems = new PoItems($po_id);
		$poItemsRecord = $poItems->getAllPoItems();	
		$action	=	"po/savepurchaseorderinvoice";
		$formHeading	=	"Add Purchase order #$po_code Invoice";
		include("engine/inc/addpurchaseorderinvoiceform.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>