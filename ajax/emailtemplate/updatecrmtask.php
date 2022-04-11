<?php

    $crm_task_id     = $crm_task_template_id = $crm_task_customer_group_id = 0;
    $crm_task_name   = $crm_task_subject = $crm_task_execution_time = "";
    $crm_task_status = 1;
    $data            = sanitizePostData($_POST);
    extract($data);
    $CrmTask = new CrmTask($crm_task_id);
    $CrmTask->update(array("crm_task_name" => $crm_task_name,
        "crm_task_subject" => $crm_task_subject,
        "crm_task_template_id" => $crm_task_template_id,
        "crm_task_customer_group_id" => $crm_task_customer_group_id,
        "crm_task_execution_time" => $crm_task_execution_time,
        "crm_task_status" => $crm_task_status
    ));
    Activity::add("updated Crm Task \"$crm_task_name\"");
    echo json_encode(array("200",  "success|Crm Task updated Successfully",
        $crm_task_id
    ));
    $email = new Email($app->siteName . " : " . "Crm Task Updated");
    $email->send("You have successfully updated Crm Task \"$crm_task_name\" on " . $app->siteName);

?>