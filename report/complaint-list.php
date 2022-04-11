<?php
$report			= new Report("Repair List",true);
$complaint		= new Complaint();
$report->query 	= $complaint->getReportComplaintListSql();
$report->setJRXML("complaint-list")->generate();
?>