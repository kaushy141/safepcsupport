<?php
if(isset($parameter1))
{
	Modal::load(array('Po'));
	$po_id = $parameter1;
	$po = new Po($po_id);
	$data = $po->getDetails();
	if($data)
	{
		$poItems = new PoItems($po->id);
		$poItemsRecord = $poItems->getAllPoItems();		
		extract($data);
		$action	=	"po/savepurchaseorder";
		$formHeading	=	"Purchase order #$po_code";
		include("engine/inc/addpurchaseorderform.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>