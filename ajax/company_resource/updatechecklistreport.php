<?php

	$checklist_id = 0; 
	$checklist_employee_name = $checklist_name = $checklist_file_path = "";
    $data           = sanitizePostData($_POST);
    extract($data);
    $ChecklistReport = new ChecklistReport($checklist_id);    
	
	if($ChecklistReport->isExist())
	{
		$checkList_update_array = array(
			"checklist_employee_name"=>$checklist_employee_name
			);
		if($checklist_file_path !="" && file_exists(BP.$checklist_file_path))
		{
			$file_extension = pathinfo($checklist_file_path, PATHINFO_EXTENSION);	
			$checklist_file = DOC_UPLOAD_DIR."checklist/".getDirectorySeparatorPath()."checklist-".time().".$file_extension";
			move_file($checklist_file_path, $checklist_file);
			$checkList_update_array['checklist_file'] = $checklist_file;
		}
		$ChecklistReport->update($checkList_update_array);
		Activity::add("Updated Checklist report <b>$checklist_name</b>","", $checklist_id);
        echo json_encode(array("200",  "success|Checklist report Updated Successfully"));
    } else
        echo json_encode(array("300",  "warning|Checklist report not found."));

?>