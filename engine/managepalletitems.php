<?php 
if(isset($parameter1))
{
	$pallet_id = $parameter1;
	$pallet = new Pallet($pallet_id);
	$data = $pallet->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"collection/managepalletitems";
		$formHeading	=	"Manage Pallet #$data[pallet_code] items";
		$btnText	=	"Update Pallet Items";
		include("engine/inc/managepalletitemsform.php");
	}
	else
		include("engine/404.php");
}
?>