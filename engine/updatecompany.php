<?php
if(isset($parameter1))
{
	$company_id = $parameter1;
	$compnay = new Company($company_id);
	$data = $compnay->load();
	if($data)
	{
		extract($data);
		$action	=	"company_resource/updatecompanyrecord";
		$formHeading	=	"Update Company #$data[company_name]";
		$btnText	=	"Update compnay record";
		include("engine/inc/addcompanyform.php");
	}
	else
		include("engine/404.php");
}
?>