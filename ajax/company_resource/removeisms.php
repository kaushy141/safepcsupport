<?php

	$id = 0;
	$data           = sanitizePostData($_POST);
    extract($data);
	$isms = new Isms($id);
	if($isms->isActive())
	{
		$record = $isms->getDetails();
		$file_extension = pathinfo(BP.$record['isms_file_path'], PATHINFO_EXTENSION);	
		$isms_file_path = DOC_UPLOAD_DIR."isms/del/isms-".time().".$file_extension";
		if(move_file(BP.$record['isms_file_path'], BP.$isms_file_path))
		{
			unlink(BP.$record['isms_file_path']);
			$isms->update(array("isms_file_path" => $isms_file_path));
			$isms->Deactivate();
			Activity::add("Removed isms register <b>$record[isms_name]</b>","", $id);
			echo json_encode(array("200",  "success|ISMS Register file removed Successfully"));
		}
		else
			echo json_encode(array("300",  "warning|Unable to delete isms register file."));
	}
	else
		echo json_encode(array("300",  "warning|ISMS Register not found."));

?>