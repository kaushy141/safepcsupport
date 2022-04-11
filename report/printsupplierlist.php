<?php
$supplier	= new Supplier();			
$report	= new Report("Supplier List", true);		
$report->setJRXML("print-supplier-list")->generate();	
?>