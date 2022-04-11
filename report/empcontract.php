<?php
$emp_id = intval($_REQUEST['id']);
$ContEmp	= new ContractEmployee($emp_id);
$empData = $ContEmp->getDetails();
$empData['employee_dob'] = date('d M, Y', strtotime($empData['employee_dob']));
$empData['employee_contract_date'] = date('d M, Y', strtotime($empData['employee_contract_date']));
$empData['employee_employment_date'] = date('d M, Y', strtotime($empData['employee_employment_date']));

$store = new Store($empData['employee_contract_store']);
$storeData = $store->getDetails();

$employer = new Employee(ACCOUNT_MANAGER); // 18 farhan Id
$emplyerData = $employer->getDetails();

$empData['application_address'] = $storeData['store_address'];
$empData['employee_job_location'] = preg_replace('/\s+/', ' ', trim($storeData['store_address']));
$empData['employee_org_name'] = $storeData['store_official_name'];

$html = nl2br(trim(getTemplateView($empData['employee_is_zero_hour_contract'] ? 'template/report/contract_employee_contract_zero_hour.txt' :  'template/report/contract_employee_contract.txt', $empData)));
$report	= new Report("Employee Contract", true);
$report->addData(array(
	"html"=>$html, 
	"contract_date" => $empData['employee_contract_date'],
	"employee_org_name" => $storeData['store_official_name'],
	"employee_signature"=>$empData['employee_signature'] != "" ? $app->sitePath($empData['employee_signature']) : $app->sitePath(DEFAULT_SIGNATURE_IMAGE),
	"employer_signature"=>$app->sitePath($emplyerData['user_signature'])
));

$report->setJRXML("contract-employee-contract");
$report->watermark	=	$app->sitePath("img/employee_appointment_".$storeData['store_key'].".jpg");
$report->generate();
?>