<?php
$training_id = intval($_REQUEST['id']);
$ContEmp	= new Training($training_id);
$empData = $ContEmp->getDetails();
$report	= new Report("Employee Contract", true);
$report->addData($empData);
$report->setJRXML("employee-training")->generate();	
?>