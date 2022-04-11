<?php
 
    $feedback_star_rating = $feedback_title = $feedback_comments = $feedback_hashcode = "";
    $data                 = sanitizePostData($_POST);
    extract($data);
    if ($feedback_hashcode != "") {
        $feedback = new Feedback();
        if ($feedback_id = $feedback->getFeebackIdByPattern($feedback_hashcode)) {
            $updateFeed = new Feedback($feedback_id);
            $data       = $updateFeed->getDetails();
            $updateFeed->update(array("feedback_star_rating" => $feedback_star_rating,
                "feedback_title" => $feedback_title,
                "feedback_comments" => $feedback_comments,
                "feedback_completion_date" => "NOW()",
                "feedback_ip_address" => IP_ADDRESS,
                "feedback_status" => 0
            ));
            Activity::add("updated", "F", 0, $data['feedback_customer_id'], "C");
            $email = new Email($app->siteName . " : " . "Feedback success");
            $email->to($data['customer_email'], $data['customer_name'], $data['customer_image']);
            $email->send("Thankyou for submitting Feedback on " . $app->siteName);
            echo json_encode(array("200",  "success|Feedback submitted Successfully"
            ));
        } else
            echo json_encode(array("300", "warning|Ooops.. Feedback couldn't submitted. try again"
            ));
    }

?>