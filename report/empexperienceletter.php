<?php 
$releaving_id = $_REQUEST['id'];
$employeeReleaving	= new EmployeeReleaving($releaving_id);
$releavingData = $employeeReleaving->getDetails();

$releavingData['user_name'] = $releavingData['user_fname'].' '.$releavingData['user_lname'];
$releavingData['user_joining_date'] = date('d F, Y', strtotime($releavingData['user_joining_date']));
$releavingData['user_releaving_date'] = date('d F, Y', strtotime($releavingData['releaving_date']));
$releavingData['user_pronoun'] = $releavingData['user_gender'] == USER_GENDER_MALE ? "He" : ($releavingData['user_gender'] == USER_GENDER_FEMALE ? "She" : "He");
$releavingData['user_object_pronoun'] =  getObjectPronoun($releavingData['user_pronoun']);

$store = new Store(4);
$storeData = $store->getDetails();

$employer = new Employee(ACCOUNT_MANAGER); // 18 farhan Id
$emplyerData = $employer->getDetails();

$releavingData['application_address'] = $storeData['store_address'];
$releavingData['employee_job_location'] = nl2br(trim($storeData['store_address']));
$releavingData['employee_org_name'] = $storeData['store_official_name'];

$html = nl2br(trim(getTemplateView('template/report/employee_experience_letter.txt', $releavingData)));
$report	= new Report("Employee experience letter", true);
$report->addData($releavingData);
$report->addData(array(
	"html"=>$html, 
	"employee_org_name" => $storeData['store_official_name'],
	"employer_signature"=>$app->sitePath($emplyerData['user_signature']),
	"user_employer_name"=>$emplyerData['user_fname'].' '.$emplyerData['user_lname']
));

$report->setJRXML("employee-experience-letter");
$report->generate();
?>