<?php
$report			= new Report("Collection / Drop Off / Pickup List",true);
$collecion		= new Collection();
$report->query 	= $collecion->getReportcollecionListSql();
$report->setJRXML("collection-list")->generate();
?>