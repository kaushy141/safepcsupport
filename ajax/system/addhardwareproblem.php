<?php

    $problem_name   = "";
    $problem_status = 1;
    $problem_id     = 0;
    $popup          = false;
    $autocheck      = array();
    $html           = "";
    $data           = sanitizePostData($_POST);
    extract($data);
    if ($problem_name != "") {
        if ($problem_id == 0) {
            $HardwareProblem = new HardwareProblem(0);
            $hrd_prob_id     = $HardwareProblem->add($problem_name, $problem_status);
            if ($hrd_prob_id) {
                if (isset($popup) == true)
                    $html = $HardwareProblem->getCheckbox("app_not_working_problem_mark", $autocheck);
                echo json_encode(array("200",  "success|New Hardware Problem Type Addedd Successfully",
                    $hrd_prob_id,
                    $html
                ));
                $email = new Email($app->siteName . " : " . "New Hardware Problem Added");
                $email->send("New Hardware Problem \"$problem_name\" added on " . $app->siteName);
            } else
                echo json_encode(array("300", "warning|Hardware Problem could not addedd. Please try again."
                ));
        } else {
            $HardwareProblem = new HardwareProblem($problem_id);
            $updateSuccess   = $HardwareProblem->update(array("problem_name" => $problem_name,
                "problem_status" => $problem_status
            ));
            if ($updateSuccess) {
                Activity::add("added New Hardware Problem <b>$problem_name</b>");
                echo json_encode(array("200",  "success|New Hardware Problem Type Updated Successfully",
                    $hrd_prob_id,
                    $html
                ));
                $email = new Email($app->siteName . " : " . "New Hardware Problem Updated");
                $email->send("New Hardware Problem \"$problem_name\" updated on " . $app->siteName);
            } else
                echo json_encode(array("300", "warning|Hardware Problem could not updated. Please try again."
                ));
        }
    } else
        echo json_encode(array("300", "warning|Hardware Problem name found invalid."
        ));

?>