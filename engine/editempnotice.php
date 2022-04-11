<?php
if(isset($parameter1))
{
	$notice_id = $parameter1;
	$notice = new EmployeeNotice($notice_id);
	$data = $notice->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"employee/saveempnotice";
		$formHeading	=	"Update Notice details";
		$btnText	=	"Save notice record";
		include("engine/inc/employeenoticeform.php");
	}
	else
		include("engine/404.php");
}
?>