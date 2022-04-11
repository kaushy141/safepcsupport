<?php
admin();
if(isset($parameter1))
{
	$user_id = $parameter1;	
	$employee = new Employee($user_id);
	
	if($employee->isExist())
	{
		$data = $employee->getDetails();
		if($releaving_id = EmployeeReleaving::isExpLetterExist($data['user_id'])){
			$employeeReleaving = new EmployeeReleaving($releaving_id);
			$releavingData = $employeeReleaving->getDetails();
			extract($releavingData);
		}
		else{
			$releaving_id = 0;
		}
		extract($data);
		$action	=	"employee/savemployeereleavingletter";
		$formHeading	=	"Employee $user_fname $user_lname experience letter";
		include("engine/inc/addexperienceletterform.php");
	}
	else{
		include("engine/404.php");
	}
}
?>