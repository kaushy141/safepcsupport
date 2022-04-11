<?php
if(isset($parameter1))
{
	$eqipment_disposal_id = $parameter1;
	$DestructionMethod = new DestructionMethod($eqipment_disposal_id);
	$data = $DestructionMethod->load();
	if($data)
	{
		extract($data);
		$action	=	"updateequipmentdisposal";
		$formHeading	=	"Update Equipment Disposal/Destruction Log";
		$btnText	=	"Update Equipment Disposal Log";
		include("engine/inc/destructionmethodform.php");
	}
	else
		include("engine/404.php");
}
?>