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
		$action	=	"collection/updatecollection";
		$formHeading	=	"Update Collection #$wc_code ";
		$btnText	=	"UPDATE";
		include("engine/inc/addcollectionform.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>