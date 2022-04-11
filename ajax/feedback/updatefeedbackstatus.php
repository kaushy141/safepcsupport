<?php

    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Feedback     = new Feedback($idvalue);
        $FeedbackData = $Feedback->getDetails();
        $status ? $Feedback->Activate() : $Feedback->Deactivate();
        Activity::add(status($status) . " Feedback of <b>" . $FeedbackData['customer_name'] . "</b>");
        echo json_encode(array("200",  "success|Customer $Customer_name's feedback " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Customer " . status($status));
        $email->send("Feedback \"" . $FeedbackData['feedback_id'] . "\" " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Feedback Status not found."
        ));

?>