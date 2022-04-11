<?php 
if(isset($parameter1))
{
	$attribute_id = $parameter1;
	$wcitem = new WcItem();
	$attribute = new Attribute($attribute_id);
	if($attribute->getDetails())
	{		
		$action	=	"collection/saveattributeoption";
		$formHeading	=	"Collection Attribute option";
		$btnText	=	"Update Attribute Option";
		include("engine/inc/addcollectionattributeoptionform.php");
	}
	else
		include("engine/404.php");
}
?>