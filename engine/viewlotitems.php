<?php 
$action	=	"product/savelot";
if(isset($parameter1))
{
	Modal::load(array('Lot'));
	$lot_id = $parameter1;
	$lot = new Lot($lot_id);
	$data = $lot->getDetails();
	if($data)
	{
		extract($data);
		$formHeading	=	"Update Lot #$data[lot_code]";	
		include("engine/inc/viewlotitemslist.php");
	}
	else
		include("engine/404.php");
}
else{
	$formHeading	=	"Lot Products";
	include("engine/inc/viewlotitemslist.php");
}

?>