<?php

	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('WeborderLabels'));
		$weborderLabels = new WeborderLabels();		
		if($labelData = $weborderLabels->loadByMd5($label_id)){
			$weborderLabels->update(array("label_status" => 0));
			Activity::add("Cancelled Label for", "O", $labelData['label_order_id']);
			unlinkFile($labelData['label_path']);
			echo json_encode(array("200", "success|Label Cancelled successfully"));
		}
		else{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Label not found"));
	}

?>