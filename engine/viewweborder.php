<?php
admin();
Modal::load(array('ProductAvailbility'));
if(isset($parameter1))
{
	$web_order_id = $parameter1;
	$weborder = new WebsiteOrder($web_order_id);
	$data = $weborder->load();
	if($data)
	{
		extract($data);
		$action	=	"weborder/updateweborder";
		$formHeading	=	"WebOrder #$web_order_number ";
		$btnText	=	"Update Order";
		include("engine/inc/weborderform.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>