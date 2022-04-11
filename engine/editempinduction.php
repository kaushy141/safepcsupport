<?php
if(isset($parameter1))
{
	$eic_id = $parameter1;
	$eic = new EmployeeInduction($eic_id);
	$data = $eic->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"employee/saveempinduction";
		$formHeading	=	"Update Induction details of  #$data[eic_employee_name]";
		$btnText	=	"Save Induction record";
		$ChecklistItem = new ChecklistItem();
		$itemList = $ChecklistItem->getCheckListItemsByEic(1, $data['eic_id']);
		include("engine/inc/employeeinductionform.php");
	}
	else
		include("engine/404.php");
}
?>