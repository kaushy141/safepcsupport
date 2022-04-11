<?php
if(isset($parameter1))
{
	$leave_id = $parameter1;
	$employee = new EmployeeLeave($leave_id);
	$data = $employee->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"employee/saveempleave";
		$formHeading	=	"Update Employee #$data[leave_employee_name] leave";
		$btnText	=	"Save Employee Leave";
		include("engine/inc/employeeleaveform.php");
	}
	else
		include("engine/404.php");
}
?>