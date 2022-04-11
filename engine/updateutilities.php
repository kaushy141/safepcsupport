<?php
if(isset($parameter1))
{
	$utility_id = $parameter1;
	$Utilities = new Utilities($utility_id);
	$data = $Utilities->load();
	if($data)
	{
		extract($data);
		$action	=	"company_resource/updateutilities";
		$formHeading	=	"Update Utility #$data[utility_description]";
		$btnText	=	"Update Utility";
		include("engine/inc/utilitiesform.php");
	}
	else
		include("engine/404.php");
}
?>