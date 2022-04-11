<?php 
if(isset($parameter1))
{
	$pallet_id = $parameter1;
	$pallet = new Pallet($pallet_id);
	$data = $pallet->getDetails();
	if($data)
	{
		extract($data);
		
		$action	=	"collection/updatepallet";
		$formHeading	=	"Update Pallet #$data[pallet_code]";
		$btnText	=	"UPDATE";
		include("engine/inc/addpalletform.php");
	}
	else
		include("engine/404.php");
}
?>