<?php
 
    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $CustomerGroup = new CustomerGroup($idvalue);
        $status ? $CustomerGroup->Activate() : $CustomerGroup->Deactivate();
        Activity::add(status($status) . " Crm Group <b>" . $CustomerGroup->get('crm_group_name') . "</b> staus");
        echo json_encode(array("200",  "success|Crm Group " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Crm Group " . status($status));
        $email->send("Crm Group <b>\"" . $CustomerGroup->get('crm_group_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Crm Group Status not found."
        ));

?>