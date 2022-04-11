<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$policies = new Policies($id);
	if($policies->isActive())
	{
		$record = $policies->getDetails();
		$file_extension = pathinfo(BP.$record['policies_file_path'], PATHINFO_EXTENSION);	
		$policies_file_path = DOC_UPLOAD_DIR."policies/del/policies-".time().".$file_extension";
		if(move_file(BP.$record['policies_file_path'], BP.$policies_file_path))
		{
			unlink(BP.$record['policies_file_path']);
			$policies->update(array("policies_file_path" => $policies_file_path));
			$policies->Deactivate();
			Activity::add("Removed Policy <b>$record[policies_name]</b>","", $id);
			echo json_encode(array("200",  "success|Policies file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete policies file."));
	}
	else
		echo json_encode(array("300",  "warning|Policies not found."));

?>