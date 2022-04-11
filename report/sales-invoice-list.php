<?php
$report			= new Report("Sales Invoice List",true);
$SalesInvoice	= new SalesInvoice();
$report->query 	= $SalesInvoice->getReportSql();
$report->setJRXML("sales-invoice-list")->generate();
?>