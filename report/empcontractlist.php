<?php
$employee	= new ContractEmployee();		
$report	= new Report("Contract employee list", true);
$report->query 	= $employee->getReportSql();
$report->setJRXML("contract-employee-contract-list")->generate();		
?>