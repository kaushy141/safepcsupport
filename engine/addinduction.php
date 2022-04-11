<?php 
$action	=	"employee/saveempinduction";
$formHeading	=	"Add Induction";
$btnText	=	"Save Employee Induction";
$ChecklistItem = new ChecklistItem();
$itemList = $ChecklistItem->getCheckListItems(1);
?>
<?php include("engine/inc/employeeinductionform.php"); ?>