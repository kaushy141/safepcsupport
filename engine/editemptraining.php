<?php
if(isset($parameter1))
{
	$training_id = $parameter1;
	$training = new Training($training_id);
	$data = $training->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"employee/saveemptraining";
		$formHeading	=	"Update training details of  #$data[training_trainee_name]";
		$btnText	=	"Save training record";
		include("engine/inc/emptrainingform.php");
	}
	else
		include("engine/404.php");
}
?>