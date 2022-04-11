<?php

if(isset($parameter1))
{
	$complaint_tax_id = $parameter1;
	$ComplaintTax = new ComplaintTax($complaint_tax_id);
	$data = $ComplaintTax->getDetails();
	if($data)
	{
		extract($data);
		$action = "system/updatecomplainttaxinfo"; 
		$btnText= "Update";
		$formHeading	=	"Update Tax Information";
	}
	else
		include("engine/404.php");
}
?>
<?php include("engine/inc/addcomplainttaxinfoform.php"); ?>