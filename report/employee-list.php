<?php
$report			= new Report("Employee List",true);
$emp			= new Employee();
$report->query 	= $emp->getReportSql();
$report->setJRXML("employee-list")->generate();
?>