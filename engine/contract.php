<?php 
	$user_id = $parameter1;
	$employee = new Employee($user_id);
	$data = $employee->load();
	if($data)
	{
		extract($data);
		if($data['user_status']==1)
		{	
			$btnText	=	"Save Appointment";
			$user_id = $parameter1;
			extract($data);
			$contract = new Contract();
			$contractData = $contract->getDetailsByUser($user_id);
			if($contractData)
			extract($contractData);
			$user_image = "<img src='".$app->imagePath($user_image)."' height='34px' width='34px' />";
			$action	=	"employee/addcontract";
			$formHeading	=	"Appointment for #$data[user_fname] $data[user_lname] ";
			include("engine/inc/cntractform.php");
		}
		else{
			$message = "$user_fname $user_lname is not a active user. Please Activate before updating Appointment information.";
			include("engine/404.php");
		}
	}
	else
		include("engine/404.php");
?>