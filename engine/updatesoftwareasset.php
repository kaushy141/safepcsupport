<?php
if(isset($parameter1))
{
	$soft_asset_id = $parameter1;
	$SoftwareAssets = new SoftwareAssets($soft_asset_id);
	$data = $SoftwareAssets->load();
	if($data)
	{
		extract($data);
		$action	=	"company_resource/updatesoftwareasset";
		$formHeading	=	"Update Software Asset #$data[soft_asset_type]";
		$btnText	=	"Update Software Asset";
		include("engine/inc/softwareassetsform.php");
	}
	else
		include("engine/404.php");
}
?>