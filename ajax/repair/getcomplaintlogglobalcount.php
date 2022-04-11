<?php
	$complaint = new Complaint(0);
    $logCount  = $complaint->getGlobalUnreadMessage();
    echo json_encode(array("200",  "success|Customer Chat Global Count executed",
        $logCount
    ));

?>