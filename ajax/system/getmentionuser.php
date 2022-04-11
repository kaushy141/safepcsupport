<?php

    $data           = sanitizePostData($_POST);
    extract($data);
	$employee = new Employee();
	echo json_encode(array("200",  "success|User mention listed", "data"=>$employee->getSearchedResult()));

?>