<?php

	$label_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($label_id){
		
		Modal::load(array('SalesLabels'));
		$SalesLabels = new SalesLabels();		
		if($labelData = $SalesLabels->loadByMd5($label_id)){
			$SalesLabels->updateDownloadCount();	
			echo json_encode(array("200", "success|Label Downloaded", $app->basePath($labelData['label_path']), $labelData['label_downloads']+1));
		}
		else{
			echo json_encode(array("300",  "warning|Sales Invoice not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Label not found"));
	}

?>