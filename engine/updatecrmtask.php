<?php 
admin();
if(isset($parameter1))
{
	$crm_task_id = $parameter1;
	$CrmTask = new CrmTask($crm_task_id);
	$data = $CrmTask->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"emailtemplate/updatecrmtask";
		$formHeading	=	"Update CRM";
		$btnText	=	"Update CRM TAsk";
		include("engine/inc/addcrmtaskfrom.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");