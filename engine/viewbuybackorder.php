<?php
admin();
Modal::load(array('BuybackOrder'));
if(isset($parameter1))
{
	$id = $parameter1;
	$buybackOrder = new BuybackOrder($id);
	$data = $buybackOrder->load();
	if($data)
	{
		extract($data);
		$action	=	"bborder/updatebuybackorder";
		$formHeading	=	"Buyback Order #$order_number ";
		$btnText	=	"Update Buyback Order";
		include("engine/inc/bborderform.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>