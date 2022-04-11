<?php
$training	= new Training();		
$report	= new Report("Employee training list", true);
$report->query 	= $training->getReportSql();
$report->setJRXML("employee-training-list")->generate();		
?>