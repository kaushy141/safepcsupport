<?php

    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $CrmTask = new CrmTask($idvalue);
        $status ? $CrmTask->Activate() : $CrmTask->Deactivate();
        Activity::add(status($status) . " Crm Task <b>" . $CrmTask->get('crm_task_name') . "</b> staus");
        echo json_encode(array("200",  "success|Crm Task " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Crm Task " . status($status));
        $email->send("Crm Task <b>\"" . $CrmTask->get('crm_task_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Crm Task Status not found."
        ));

?>