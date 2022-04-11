<?php
admin();
if(isset($parameter1))
{
	$user_id = $parameter1;
	$Employee = new Employee($user_id);
	
	if($Employee->isExist())
	{
		$data = $Employee->load();
		extract($data);
		$customer_image = "<img src='".$app->imagePath($user_image)."' height='34px' width='34px' />";
		$action	=	"employee/employeerecords";
		$formHeading	=	"Employee #$data[user_fname] $data[user_lname] ";
		include("engine/inc/employeerecordform.php");
	}
	else
		include("engine/404.php");
}
?>