<?php
$company	= new Company(intval($_REQUEST['id']));
if($company->isExist()){
	$companyData = $company->getDetails();			
	$report	= new Report("Company $companyData[company_name]",true);
	$companyData['company_bank_details'] = nl2br($companyData['company_bank_details']);
	$companyData['company_trademark'] = BP.$companyData['company_trademark'];
	
	$report->addData($companyData);
	$report->setJRXML("print-company")->generate();
}
?>