<?php
$report			= new Report("Appointment ",true);
$employee	= new Employee(intval($_REQUEST['id']));
$employeeRecord  = $employee->getDetails();

$contract = new Contract();
if($contractRecord = $contract->getDetailsByUser($employeeRecord['user_id']))
{
	$storeOrg = new Store($contractRecord['user_contract_store']);
	$storeOrgData = $storeOrg->getDetails();
	$dataArray = array(
		"issue_date" => date("d M-Y", strtotime($contractRecord['user_appointment_issue_date'])),
		"employee_name" => $employeeRecord['user_name'],
		"employee_position" => $employeeRecord['user_type_name'],
		"father_name" => $contractRecord['user_father_name'],
		"employee_cnic_number" => $contractRecord['user_cnic_number'],
		"department_name" => $contractRecord['user_department_name'],
		"joining_date" => date("d M-Y", strtotime($contractRecord['user_pay_joining_date'])),
		"currency_code" => $contractRecord['user_payment_currency'],
		"employee_salary" => $contractRecord['user_pay_salary'],
		"reporting_person" => $contractRecord['user_reporting_person'],
		"working_time" => $contractRecord['user_working_time'],
		"working_days" => $contractRecord['user_pay_working_days'],
		"pay_shedule" => strtolower($contractRecord['user_pay_salary_invoicing']),
		"employee_org_name"=>$storeOrgData['store_official_name'],
		"employee_org_country"=>$storeOrgData['store_country_code']
	);
	
	$appointment_text = App::readTemplate("employee_appointment", $dataArray);			
	$data = array("appointment_text" => $appointment_text);
	$report->addData($data);
	$report->watermark	=	$app->sitePath("/img/employee_appointment_".$storeOrgData['store_key'].".jpg");
	$report->setJRXML("employee-appointment")->generate();
}
?>