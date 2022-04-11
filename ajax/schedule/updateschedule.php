<?php

    $schedule_status = $keyid = 0;
    $schedule_title  = $schedule_due_date = $customer_email = $schedule_scope = "";
    $crm_task_status = 1;
    $data            = sanitizePostData($_POST);
    extract($data);
    $Customer = new Customer(0);
    if (!$schedule_customer_id = $Customer->getIdByEmail(md5(trim(strtolower($customer_email)))))
        $schedule_customer_id = 0;
    if ($keyid > 0) {
        $Schedule = new Schedule($keyid);
        $Schedule->update(array("schedule_customer_id" => $schedule_customer_id,
            "schedule_title" => $schedule_title,
            "schedule_scope" => $schedule_scope,
            "schedule_due_date" => $schedule_due_date,
            "schedule_status" => $schedule_status,
            "schedule_mark_as_read" => 0
        ));
        Activity::add("Updated a Schedule");
		$email = new Email($app->siteName . " : " . "Your Schedule updated");
		$email->to("test-e6e34to1l@srv1.mail-tester.com", "Tester");
		$email->send("You have successfully updated Scheduled on " . $app->siteName . " w.e.f. " . date('d M Y h:i:s A'));
        echo json_encode(array("200",  "success|Schedule Updated Successfully"
        ));
    }

?>