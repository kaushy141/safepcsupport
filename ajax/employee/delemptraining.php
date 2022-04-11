<?php
	$training_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);	
	if($training_id != 0)
	{
		$training = new Training($training_id);
		if($training->isExist())
		{
			$record = $training->getDetails();
			$training->remove();
			unlinkFile($record['training_trainee_signature']);	
			unlinkFile($record['training_trainer_signature']);	
			echo json_encode(array("200",  "success|Training record deleted", $training_id));
		}
		else
			echo json_encode(array("300", "danger|No Training found."));
	}
	else
		echo json_encode(array("300", "danger|Not a valid Training."));

?>