<?php
$pay_slip_id	= intval($_GET['id']);
$SalaryRegister = 	new SalaryRegister($pay_slip_id);
$info = $SalaryRegister->getDetails();
if($info)
{
	$month_name = date("M-Y", strtotime("20".substr($info['pay_slip_month_id'],0,2)."-".substr($info['pay_slip_month_id'],2,2)."-01"));
			
	$employee	= new Employee($info['pay_slip_user_id']);
	$empData = $employee->getDetails();	
	
	$contract = new Contract();
	$contractData = $contract->getDetailsByUser($info['pay_slip_user_id']);	
	
	$contractEmployee = new ContractEmployee();
	$contractDetails = $contractEmployee->getContractDetailsByEmail($empData['user_email']);
	
	$currency = "";
	if($contractDetails){
		$currency = $contractDetails['employee_salary_rate_currency'];
	}
	
	$storeOrg = new Store($contractData['user_contract_store']);
	$storeOrgData = $storeOrg->getDetails();
	$accMgr = new Employee(HR_MANAGER);
	$mgrData = $accMgr->getDetails();
					
	$report			= new Report("Pay Slip $month_name",true);
	$report->headerimage = $app->sitePath($storeOrgData['store_logo']);
	$report->address = $storeOrgData['store_address'];
	$data = array(
					"company_name" => $storeOrgData['store_official_name'],
					"pay_slip_date" => $month_name,					 
					"pay_slip_heading" => "$empData[user_name] Pay Slip for $month_name", 						
					"account_manager" => $mgrData['user_name'],
					"account_signature" => $app->sitePath($mgrData['user_signature']),
					"employee_name" => $empData['user_name'],
					"employee_designation" => $empData['user_type_name'],
					"joining_date" => date("M d Y", strtotime($contractData['user_pay_joining_date'])),
					"basic_salary" => $currency.' '.$info['pay_slip_basic_salary'],
					"commission_rate" => $info['pay_slip_commision'],
					"commission_amount" => $currency.' '.round(($info['pay_slip_total_sale']*$info['pay_slip_commision'])/100),
					"leaves" => $info['pay_slip_leave_taken'],
					"net_salary" => $currency.' '.$info['pay_slip_grant_pay'],
					"total_sale" => $info['pay_slip_total_sale'],
					"paidimage" => $app->sitePath($info['pay_slip_paid_status']==PAY_STATUS_PAID ? INVOICE_PAID_IMAGE_PATH : INVOICE_UNPAID_IMAGE_PATH),
					"pay_mode" => $info['pay_slip_pay_mode'],
					"pay_status" => $info['pay_slip_paid_status'],
					"pay_remark" => $info['pay_slip_payment_reference'],
					"pay_date" => date("M d Y")
	);
	//print_r($data);die;
	
	$report->watermark	=	$app->sitePath("/img/employee_appointment_".$storeOrgData['store_key'].".jpg");		
	$report->addData($data);
	$report->setJRXML("employee-pay-slip")->generate();
}
?>