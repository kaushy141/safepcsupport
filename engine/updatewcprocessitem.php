<?php
if(isset($parameter1))
{
	$wc_process_asset_code = $parameter1;
	$collectionProcess = new CollectionProcess($wc_process_asset_code);
	$data = $collectionProcess->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"collection/savecollectionprocessitem";
		$formHeading	=	"Update Item #$wc_process_asset_code details";
		$btnText	=	"Save Record";
		include("engine/inc/updatewcprocessitemform.php");
	}
	else
		include("engine/404.php");
}
?>