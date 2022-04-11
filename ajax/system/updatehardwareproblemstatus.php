<?php

    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $HardwareProblem = new HardwareProblem($idvalue);
        $status ? $HardwareProblem->Activate() : $HardwareProblem->Deactivate();
        Activity::add(status($status) . " Hardware Problem <b>" . $HardwareProblem->get('problem_name') . "</b> staus");
        echo json_encode(array("200",  "success|Hardware Problem " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Hardware Problem " . status($status));
        $email->send("Hardware Problem <b>\"" . $HardwareProblem->get('problem_name') . "\"</b> " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Hardware Problem Status not found."
        ));

?>