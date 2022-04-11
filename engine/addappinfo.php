<?php
	$appinfo = new AppInfo();
	$data = $appinfo->getDetails();
	if($data)
	{
		extract($data);
		$action = "system/updateappinfo"; 
		$btnText= "Update";
		$formHeading	=	"Update Application Information ";
	}
	else
	{
		$action = "system/addappinfo"; 
		$btnText= "Add";
		$formHeading	=	"Add Application Information";
	}
?>
<?php include("engine/inc/addappinfoform.php"); ?>