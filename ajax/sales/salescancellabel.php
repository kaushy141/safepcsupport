<?php

	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('SalesLabels'));
		$SalesLabels = new SalesLabels();		
		if($labelData = $SalesLabels->loadByMd5($label_id)){
			$SalesLabels->update(array("label_status" => 0));
			Activity::add("Cancelled Label for", "S", $labelData['label_order_id']);
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