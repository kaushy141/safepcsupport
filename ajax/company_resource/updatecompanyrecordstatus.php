<?php

    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Company = new Company($idvalue);
        $status ? $Company->Activate() : $Company->Deactivate();
        Activity::add(status($status) . " Company <b>" . $Company->get('company_name') . "</b> staus");
        echo json_encode(array("200",  "success|Company " . status($status) . " Successfully"
        ));
    } else
        echo json_encode(array("300", "warning|Company Status not found."
        ));

?>