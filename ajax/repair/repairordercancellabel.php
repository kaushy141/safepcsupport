<?php

	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('ComplaintLabels'));
		$ComplaintLabels = new ComplaintLabels();		
		if($labelData = $ComplaintLabels->loadByMd5($label_id)){
			$ComplaintLabels->update(array("label_status" => 0));
			Activity::add("Cancelled Label for", "C", $labelData['label_complaint_id']);
			unlinkFile($labelData['label_path']);
			echo json_encode(array("200", "success|Label Cancelled successfully"));
		}
		else{
			echo json_encode(array("300",  "warning|Label not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Label not found"));
	}

?>