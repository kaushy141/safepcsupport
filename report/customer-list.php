<?php
$report			= new Report("Customer List",true);
$cst			= new Customer();
$report->query 	= $cst->getReportSql();
$report->setJRXML("customer-list")->generate();
?>