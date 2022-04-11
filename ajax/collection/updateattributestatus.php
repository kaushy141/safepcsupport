<?php

    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $attribute = new Attribute($idvalue);
        $status ? $attribute->Activate() : $attribute->Deactivate();
        Activity::add(status($status) . " Attribute <b>" . $attribute->get('attribute_name') . "</b> status");
        echo json_encode(array("200",  "success|Attribute " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Attribute " . status($status));
        $email->send("Attribute <b>\"" . $attribute->get('attribute_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Attribute Status not found."
        ));

?>