<?php

    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $CrmEmailTemplate = new CrmEmailTemplate($idvalue);
        $status ? $CrmEmailTemplate->Activate() : $CrmEmailTemplate->Deactivate();
        Activity::add(status($status) . " Email Template <b>" . $CrmEmailTemplate->get('crm_email_template_name') . "</b> staus");
        echo json_encode(array("200",  "success|Email Template " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Email Template " . status($status));
        $email->send("Email Template <b>\"" . $CrmEmailTemplate->get('crm_email_template_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Email Template Status not found."
        ));

?>