<?php

	$training_id = 0;
	$training_status = 1;
	$training_type = $training_date = $training_date = $training_description = $training_trainee_name = $training_job_title = $training_learning_detail = $training_trainer_name = "";
	$training_trainee_signature = $training_trainer_signature = '';
	
	$data = sanitizePostData($_POST);
	extract($data);	
	
	$training_array = compact('training_type', 'training_date', 'training_description', 'training_trainee_name', 'training_job_title', 'training_learning_detail', 'training_trainer_name');
	
	$training = new Training($training_id);
	if($training_id == 0){
		$training_array['training_created_date'] = 'NOW()';
		$training_array['training_status'] = '1'; 
		$training_id = $training->insert($training_array);		
	}
	else{
		$training->update($training_array);
	}
	$record = $training->getDetails();
	if ($training_trainee_signature != "") {
		$training_trainee_signature_path = "upload/trainee/sign/".getDirectorySeparatorPath()."$training_id-" . time() . ".".pathinfo($training_trainee_signature, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($training_trainee_signature), $app->sitePath($training_trainee_signature_path))){
			$training->update(array("training_trainee_signature" => $training_trainee_signature_path));
			unlinkFile($record['training_trainee_signature']);	
		}
			
	}
	if ($training_trainer_signature != "") {
		$training_trainer_signature_path = "upload/trainer/sign/".getDirectorySeparatorPath()."$training_id-" . time()  . ".".pathinfo($training_trainer_signature, PATHINFO_EXTENSION);
		if (move_file($app->sitePath($training_trainer_signature), $app->sitePath($training_trainer_signature_path))){
			$training->update(array("training_trainer_signature" => $training_trainer_signature_path));
			unlinkFile($record['training_trainer_signature']);	
		}
			
	}
	
	echo json_encode(array("200",  "success|Training record saved", $training_id));

?>