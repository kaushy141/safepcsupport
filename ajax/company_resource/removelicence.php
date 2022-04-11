<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$licences = new Licences($id);
	if($licences->isActive())
	{
		$record = $licences->getDetails();
		$file_extension = pathinfo(BP.$record['licence_file_path'], PATHINFO_EXTENSION);	
		$licence_file_path = DOC_UPLOAD_DIR."licence/del/".getDirectorySeparatorPath()."licence-".time().".$file_extension";
		if(move_file(BP.$record['licence_file_path'], BP.$licence_file_path))
		{
			unlink(BP.$record['licence_file_path']);
			$licences->update(array("licence_file_path" => $licence_file_path));
			$licences->Deactivate();
			Activity::add("Removed Licence <b>$record[licence_name]</b>","", $id);
			echo json_encode(array("200",  "success|Licence file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete licence file."));
	}
	else
		echo json_encode(array("300",  "warning|Licence not found."));

?>