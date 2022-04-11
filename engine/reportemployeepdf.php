<?php
$report 		= new Report("Employee List");
$emp = new Employee();
$report->query 	= $emp->getReportSql();
$report->setJRXML("employee-list");
$report->generate();
echo "<script type=\"text/javascript\">window.open('".$app->basePath("report.php")."');</script>"; 
?>