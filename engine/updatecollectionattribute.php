<?php 
if(isset($parameter1))
{
	$attribute_id = $parameter1;
	$Attribute = new Attribute($attribute_id);
	$data = $Attribute->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"collection/saveattribute";
		$formHeading	=	"Update Collection Attribute";
		$btnText	=	"Update Attribute";
		include("engine/inc/addcollectionattributeform.php");
	}
	else
		include("engine/404.php");
};?>