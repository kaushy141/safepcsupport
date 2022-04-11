<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$ManualTrainingDoc = new ManualTrainingDoc($id);
	if($ManualTrainingDoc->isActive())
	{
		$record = $ManualTrainingDoc->getDetails();
		$file_extension = pathinfo(BP.$record['manual_file_path'], PATHINFO_EXTENSION);
		$manual_file_path = DOC_UPLOAD_DIR."manual/del/manual-".time().".$file_extension";
		if(move_file(BP.$record['manual_file_path'], BP.$manual_file_path))
		{
			unlink(BP.$record['manual_file_path']);
			$ManualTrainingDoc->update(array("manual_file_path" => $manual_file_path));
			$ManualTrainingDoc->Deactivate();
			echo json_encode(array("200",  "success|Manual & Training Document file removed Successfully"));
			Activity::add("Removed manual <b>$record[manual_name]</b>","", $id);
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete Manual & Training Document file."));
	}
	else
		echo json_encode(array("300",  "warning|Manual & Training Document not found."));

?>