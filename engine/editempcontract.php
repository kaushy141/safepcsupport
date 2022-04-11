<?php
if(isset($parameter1))
{
	$employee_id = $parameter1;
	$employee = new ContractEmployee($employee_id);
	$data = $employee->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"employee/saveempcontract";
		$formHeading	=	"Update Employee #$data[employee_name]";
		$btnText	=	"Save Employee Record";
		include("engine/inc/empcontractform.php");
	}
	else
		include("engine/404.php");
}
?>