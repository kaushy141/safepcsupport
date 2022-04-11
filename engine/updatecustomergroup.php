<?php 
admin();
if(isset($parameter1))
{
	$crm_group_id = $parameter1;
	$CustomerGroup = new CustomerGroup($crm_group_id);
	$data = $CustomerGroup->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"updatecustomergroup";
		$formHeading	=	"Update Group";
		$btnText	=	"Update Customer Group";
		include("engine/inc/addcustomergroupfrom.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");