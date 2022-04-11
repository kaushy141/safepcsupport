<?php
if(isset($parameter1))
{
	$asset_id = $parameter1;
	$PhysicalAsset = new PhysicalAsset($asset_id);
	$data = $PhysicalAsset->load();
	if($data)
	{
		extract($data);
		$action	=	"company_resource/updatephysicalasset";
		$formHeading	=	"Update Physical Asset #$data[asset_tag]";
		$btnText	=	"UPDATE Physical Asset";
		include("engine/inc/physicalassetregform.php");
	}
	else
		include("engine/404.php");
}
?>