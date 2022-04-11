<?php
	$wc_process_asset_code = '';
	$data  = sanitizePostData($_POST);
    extract($data);	
	if($result = CollectionProcess::getProcessByCode($wc_process_asset_code))
		echo json_encode(array("200",  "success|Items details loaded",
            $result
        ));
	else
		echo json_encode(array("300",  "Warning|No Collection item found."));

?>