<?php
admin();
if(isset($parameter1))
{
	$wc_id = $parameter1;
	$collection = new Collection($wc_id);
	$data = $collection->load();
	if($data)
	{
		extract($data);
		$action	=	"collection/addcollectionmedia";
		$formHeading	=	"Update Collection #$wc_code Media";
		$btnText	=	"SAVE MEDIA FILES";
		include("engine/inc/viewcollectionmediafiles.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>