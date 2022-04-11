<?php
if(isset($parameter1))
{
	$rfc_id = $parameter1;
	$Rfc = new Rfc($rfc_id);
	$data = $Rfc->load();
	if($data)
	{
		extract($data);
		$action	=	"company_resource/updaterfcrecord";
		$formHeading	=	"Update Change Management Request";
		$btnText	=	"Update";
		include("engine/inc/addrfcform.php");
	}
	else
		include("engine/404.php");
}
?>