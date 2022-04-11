<?php 
$action	=	"employee/saveempleaver";
$formHeading	=	"Add Leaver";
$btnText	=	"Save Employee Leaver";
$ChecklistItem = new ChecklistItem();
$itemList = $ChecklistItem->getCheckListItems(2);
?>
<?php include("engine/inc/employeeleaverform.php"); ?>