<?php

    $crm_task_id = 0;
    $data        = sanitizePostData($_POST);
    extract($data);
    if ($crm_task_id) {
        $CrmTask = new CrmTask($crm_task_id);
        $CrmTask->execute();
        echo json_encode(array("200",  "success|Crm Task executed Successfully"
        ));
    } else
        echo json_encode(array("300", "warning|Crm Task not found."
        ));

?>