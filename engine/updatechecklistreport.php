<?php
if(isset($parameter1))
{
	$checklist_id = $parameter1;
	$ChecklistReport = new ChecklistReport($checklist_id);
	$data = $ChecklistReport->load();
	if($data)
	{
		extract($data);
		$action	=	"company_resource/updatechecklistreport";
		$formHeading	=	"Update Checklist Report";
		$btnText	=	"Update Checklist Report";
		include("engine/inc/checklistreportform.php");
	}
	else
		include("engine/404.php");
}
?>