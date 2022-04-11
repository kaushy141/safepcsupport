<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$templates = new Templates($id);
	if($templates->isActive())
	{
		$record = $templates->getDetails();
		$file_extension = pathinfo(BP.$record['templates_file_path'], PATHINFO_EXTENSION);	
		$templates_file_path = DOC_UPLOAD_DIR."templates/del/templates-".time().".$file_extension";
		if(move_file(BP.$record['templates_file_path'], BP.$templates_file_path))
		{
			unlink(BP.$record['templates_file_path']);
			$templates->update(array("templates_file_path" => $templates_file_path));
			$templates->Deactivate();
			Activity::add("Removed Templates <b>$record[templates_name]</b>","", $id);
			echo json_encode(array("200",  "success|Templates file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete Templates file."));
	}
	else
		echo json_encode(array("300",  "warning|Templates not found."));

?>