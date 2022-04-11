<?php
$eil_id = intval($_REQUEST['id']);
		$eil	= new EmployeeLeaver($eil_id);
		$eilData = $eil->getDetails();
		$report	= new Report("Employee induction", true);
		$eilData['emp_sign_date'] = date('d/M/Y', strtotime($eilData['eil_employee_sign_date']));
		$eilData['hr_sign_date'] = date('d/M/Y', strtotime($eilData['eil_hr_signature_date']));
		$eilData['emp_signature'] = $app->sitePath($eilData['eil_employee_signature']);
		$eilData['hr_signature'] = $app->sitePath($eilData['eil_hr_signature']);
		$eilData['relevant_information'] = $eilData['eil_relevant_issue'];
		$eilData['emp_name'] = strtoupper($eilData['eil_employee_name']);
		$eilData['html'] = $eil->getItemsReportHtml();
		$report->addData($eilData);
		
		$report->setJRXML("emp-leaver-document")->generate();	
?>