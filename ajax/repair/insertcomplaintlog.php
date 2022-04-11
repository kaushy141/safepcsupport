<?php

	$logtext          = "";
    $id               = 0;
    $complaint_format = '';
	$privacy = 0;
    $data             = sanitizePostData($_POST);
    extract($data);
    if ($logtext != "" && intval($id) != 0 && $complaint_format != '') {
        $complaint_id          = $id;
        $complaint             = new Complaint($complaint_id);
        $complaint_customer_id = $complaint->get("complaint_customer_id");
        $complaint_log_text    = $logtext;
        $complaint_log_type    = "TEXT";
        $complaint_log_status  = 1;
		$complaint_log_privacy = $privacy ? 0 : 1;
        $log                   = new ComplaintLog();
        $complaint_log_id      = $log->add($complaint_id, $complaint_format, $complaint_log_text, $complaint_log_type, $complaint_log_status, $complaint_log_privacy);
        if ($complaint_log_id) {
            $ComplaintLogViewer = new ComplaintLogViewer();
            $ComplaintLogViewer->add($complaint_id, $complaint_format);
            $logdata = $log->getLog($complaint_id, $complaint_format, $complaint_log_id);
            echo json_encode(array("200",  "success|Repair Request Log Added successfully",
                $logdata
            ));
        } else
            echo json_encode(array("300", "warning|Unable to Insert Repair Request Log. try again"
            ));
    } else
        echo json_encode(array("300", "warning|Please write Log Text"
        ));

?>