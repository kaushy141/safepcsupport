<?php
if(isset($parameter1))
{
	$user_id = $parameter1;
	$employee = new Employee($user_id);
	$data = $employee->load();
	if($data)
	{
		extract($data);
		$user_image = "<img src='".$app->imagePath($user_image)."' height='34px' width='34px' />";
		$action	=	"employee/updateemployee";
		$formHeading	=	"Update Employee #$data[user_fname] $data[user_lname] ";
		$btnText	=	"UPDATE";
		include("engine/inc/addemployeeform.php");
	}
	else
		include("engine/404.php");
}
?>