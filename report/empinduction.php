<?php
$eic_id = intval($_REQUEST['id']);
		$eic	= new EmployeeInduction($eic_id);
		$eicData = $eic->getDetails();
		$report	= new Report("Employee induction", true);
		$eicData['emp_sign_date'] = date('d/M/Y', strtotime($eicData['eic_employee_sign_date']));
		$eicData['hr_sign_date'] = date('d/M/Y', strtotime($eicData['eic_hr_signature_date']));
		$eicData['emp_signature'] = $app->sitePath($eicData['eic_employee_signature']);
		$eicData['hr_signature'] = $app->sitePath($eicData['eic_hr_signature']);
		$eicData['relevant_information'] = $eicData['eic_relevant_issue'];
		$eicData['emp_name'] = strtoupper($eicData['eic_employee_name']);
		$eicData['html'] = $eic->getItemsReportHtml();
		$report->addData($eicData);
		
		$report->setJRXML("emp-induction-document")->generate();	
?>