<?php

	$checklist_employee_name = $checklist_name = $checklist_file_path = "";
	$checklist_status = 1;
    $data           = sanitizePostData($_POST);
    $ChecklistReport = new ChecklistReport(0);
    extract($data);
	
	if($checklist_file_path !="" && file_exists(BP.$checklist_file_path))
	{
		$file_extension = pathinfo($checklist_file_path, PATHINFO_EXTENSION);	
		$checklist_file = DOC_UPLOAD_DIR."checklist/".getDirectorySeparatorPath()."".getDirectorySeparatorPath()."checklist-".time().".$file_extension";
		move_file($checklist_file_path, $checklist_file);	
	
		$checklist_origin = $ChecklistReport->getCheckListTypeCount($checklist_name) ? 1 : 0;
		if($checklist_id = $ChecklistReport->add($checklist_employee_name, $checklist_name, $checklist_origin, $checklist_file, $checklist_status))
		{			
			Activity::add("Added Checklist report <b>$checklist_name</b>","", $checklist_id);
			echo json_encode(array("200",  "success|Checklist report Added Successfully"));
		} 
		else{
			echo json_encode(array("300",  "warning|Checklist report couldn't added" ));
		}	
	}

?>