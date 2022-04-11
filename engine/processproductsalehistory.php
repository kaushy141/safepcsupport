<?php
if(isset($parameter1))
{
	$wpca_process_code = $parameter1;
	$collectionProcess = new CollectionProcess($wpca_process_code);
	$data = $collectionProcess->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"collection/saveprocessproductsalehistory";
		$formHeading	=	"Product #$data[wc_process_asset_code] Sale History";
		$btnText	=	"Save Record";
		include("engine/inc/processproductsalehistoryform.php");
	}
	else
		include("engine/404.php");
}
?>