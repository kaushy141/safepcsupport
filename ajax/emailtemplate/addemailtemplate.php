<?php

    $crm_email_template_id     = 0;
    $crm_email_template_name   = $crm_email_template_html = "";
    $crm_email_template_status = 1;
    $data                      = sanitizePostData($_POST);
    extract($data);
    $CrmEmailTemplate      = new CrmEmailTemplate();
    $crm_email_template_id = $CrmEmailTemplate->add($crm_email_template_name, $crm_email_template_html, $crm_email_template_status);
    Activity::add("added New Email Template \"$crm_email_template_name\"");
    echo json_encode(array("200",  "success|Email Template added Successfully",
        $crm_email_template_id
    ));
    $email = new Email($app->siteName . " : " . "New Template added");
    $email->send("You have successfully added Email Template \"$crm_email_template_name\" on " . $app->siteName);

?>