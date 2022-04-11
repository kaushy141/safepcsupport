<?php
admin();
if(isset($parameter1))
{
	$wc_id = $parameter1;
	$collection = new Collection($wc_id);
	if($collection->isExist())
	{
		$data = $collection->load();
		$wcrItem = new WcrItem();
		$data['saved_items_array'] = $wcrItem->getAllItemDetailsArray($wc_id);
		extract($data);
		$action	=	"collection/managecollection";
		$formHeading	=	"Update Collection #$wc_code Items";
		$btnText	=	"UPDATE";
		include("engine/inc/managecollectionitems.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>