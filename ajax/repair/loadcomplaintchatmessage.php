<?php
	$customer_id      = $complaint_id = 0;
    $complaint_format = '';
    $data             = sanitizePostData($_POST);
    extract($data);
    if ($customer_id != 0 && $complaint_id != 0 && $complaint_format != '') {
        $ComplaintLog                   = new ComplaintLog();
        $ComplaintLog->customer_id      = $customer_id;
        $ComplaintLog->complaint_id     = $complaint_id;
        $ComplaintLog->complaint_format = $complaint_format;
        $customer_log                   = $ComplaintLog->getCustomerLog();
        echo json_encode(array("200",  "success|Log History loaded successfully",
            $customer_log
        ));
        $ComplaintLogViewer = new ComplaintLogViewer();
        $ComplaintLogViewer->add($complaint_id, $complaint_format);
    } else
        echo json_encode(array("300", "warning|Unable to Fetch Log Text"
        ));

?>