<?php
if(isset($parameter1))
{
	$wci_id = $parameter1;
	$WcItem = new WcItem($wci_id);
	$data = $WcItem->getDetails();
	if($data)
	{
		extract($data);
		
		$action	=	"collection/updatewcitem";
		$formHeading	=	"Update Collection Item #$data[wci_name]";
		$btnText	=	"UPDATE";
		include("engine/inc/addwcitemform.php");
	}
	else
		include("engine/404.php");
}
?>