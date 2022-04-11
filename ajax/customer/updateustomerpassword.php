<?php

    $old_password = $new_password = $conf_password = "";
    $data         = sanitizePostData($_POST);
    extract($data);
    if ($old_password != "" && $new_password != "" && $conf_password != "") {
        $length          = strlen($new_password);
        $approval_length = APP_PASSWORD_MIN_LENGTH;
        if ($length - $approval_length >= 0) {
            $cust          = new Customer($_SESSION['customer_id']);
            $customer      = $cust->getDetails();
            $cust_password = $customer["customer_password"];
            if ($cust_password == $old_password) {
                $cust->update(array("customer_password" => $new_password
                ));
                new SMS($customer['customer_phone'], "Hi, $customer[customer_fname] Your Password updated successfully.");
                Activity::add("changed Password");
                echo json_encode(array("200",  "success|Your Password updated successfully"
                ));
                $email = new Email($app->siteName . " : " . "Password Changed");
                $email->send("You have successfully chnaged your Password on " . $app->siteName . ". If you have not then contact to Administration immediately.");
            } else
                echo json_encode(array("300", "danger|Old Password not Matched."
                ));
        } else
            echo json_encode(array("300", "danger|Password must have atleast " . APP_PASSWORD_MIN_LENGTH . " Character"
            ));
    } else
        echo json_encode(array("300", "danger|Old password or New Password can't Blank"
        ));

?>