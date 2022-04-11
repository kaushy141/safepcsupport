<?php

    $crm_email_template_id     = 0;
    $crm_email_template_name   = $crm_email_template_html = "";
    $crm_email_template_status = 1;
    $data                      = sanitizePostData($_POST);
    extract($data);
    $CrmEmailTemplate = new CrmEmailTemplate($crm_email_template_id);
    if ($record = $CrmEmailTemplate->getDetails()) {
        $CrmEmailTemplate->update(array("crm_email_template_name" => $crm_email_template_name,
            "crm_email_template_html" => $crm_email_template_html,
            "crm_email_template_status" => $crm_email_template_status
        ));
        Activity::add("Updated New Email Template \"$record[crm_email_template_name]\"");
        echo json_encode(array("200",  "success|Email Template updated Successfully",
            $crm_email_template_id
        ));
        $email = new Email($app->siteName . " : " . " Email Template Updated");
        $email->send("You have successfully updated Email Template \"$record[crm_email_template_name]\" to \"$crm_email_template_name\" on " . $app->siteName);
    } else
        echo json_encode(array("400",
            "Warning|No Email Template found"
        ));

?>