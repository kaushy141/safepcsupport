<?php

    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $HardwareType = new HardwareType($idvalue);
        $status ? $HardwareType->Activate() : $HardwareType->Deactivate();
        Activity::add(status($status) . " Hardware type <b>" . $HardwareType->get('hardware_name') . "</b> staus");
        echo json_encode(array("200",  "success|Hardware Type " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Hardware Type " . status($status));
        $email->send("Hardware Type <b>\"" . $HardwareType->get('hardware_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Hardware Type Status not found."
        ));

?>