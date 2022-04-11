<?php
	$employee = new Employee(0);
	echo json_encode(array("200",  "success|Today's Birthday user loaded", $employee->getTodayBirthDayUser()));

?>