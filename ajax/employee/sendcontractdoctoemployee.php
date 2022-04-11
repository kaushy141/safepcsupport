<?php

	$employee_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($employee_id != 0)
	{
		$employee = new ContractEmployee($employee_id);
		if($employee->isExist())
		{
			$store = new Store(APP_EMPLOYER_DOMAIN);
			$storeData = $store->getDetails();
			$empData = $employee->getDetails();
			$empData['employee_employment_date'] = date('D, d M Y', strtotime($empData['employee_employment_date']));
			$empData['employer_name'] = $storeData['store_title'];
			$email     = new Email("Contract information");
			$email->to($empData['employee_email'], $empData['employee_name'], $app->basePath(DEFAULT_USER_IMAGE));
			$email->addFile(DOC::EMPCONTRACT($empData['employee_id']), $app->siteName . " contract.pdf");
			$email->template('cont_employee_contract', $empData);
			$email->send();
			echo json_encode(array("200",  "success|Contract copy sent"));
		}
		else
			echo json_encode(array("300", "danger|No Employee found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Employee."));

?>