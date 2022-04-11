<?php

    $schedule_id = 0;
    $data        = sanitizePostData($_POST);
    extract($data);
    if ($schedule_id > 0) {
        $Schedule = new Schedule($schedule_id);
        $Schedule->update(array("schedule_mark_as_read" => 1
        ));
        echo json_encode(array("200",  "success|Schedule marked as read"
        ));
    } else
        echo json_encode(array("200",  "warning|Ooops..couldn't marked as read"
        ));

?>