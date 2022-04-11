<?php
	$complaint = new Complaint(0);
    $logCount  = $complaint->getUnreadMessage();
    echo json_encode(array("200",  "success|Customer Chat Count executed",
        $logCount
    ));

?>