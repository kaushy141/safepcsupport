<?php
if(isset($parameter1))
{
	$eil_id = $parameter1;
	$eil = new EmployeeLeaver($eil_id);
	$data = $eil->getDetails();
	if($data)
	{
		extract($data);
		$action	=	"employee/saveempleaver";
		$formHeading	=	"Update Leaver details of  #$data[eic_employee_name]";
		$btnText	=	"Save Leaver record";
		$ChecklistItem = new ChecklistItem();
		$itemList = $ChecklistItem->getCheckListItemsByEil(2, $data['eil_id']);
		include("engine/inc/employeeleaverform.php");
	}
	else
		include("engine/404.php");
}
?>