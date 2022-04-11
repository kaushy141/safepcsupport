<?php
	$id               = 0;
    $complaint_format = '';
    $data             = sanitizePostData($_POST);
    extract($data);
    if (intval($id) != 0 && $complaint_format != '') {
        $complaint_id = $id;		
		//UserTag::markUserTagRead();		
        $log          = new ComplaintLog($complaint_format, $complaint_id);
        $logdata      = $log->getLog($complaint_id, $complaint_format);		
        echo json_encode(array("200",  $logdata?"success|Log comments loaded successfully.":"success|No Log comments available for it.", $logdata ));
    } 
	else
        echo json_encode(array("300", "warning|Unable to fetch Log Text"));

?>