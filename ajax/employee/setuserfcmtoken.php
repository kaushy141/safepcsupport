<?php
    $currentToken = "";
    $data         = $_POST;
    extract($data);
    if ($currentToken != "") {
        $_SESSION['currentToken'] = $currentToken;
        $tokenUpdate              = false;
        if (getUserType() == "E") {
            $emp = new Employee($_SESSION['user_id']);
            if ($emp->get("user_fcm_token") != $currentToken) {
                $emp->setFCMToken($currentToken);
                $tokenUpdate = true;
            }
        } else {
            $cst = new Customer($_SESSION['customer_id']);
            if ($cst->get("customer_fcm_token") != $currentToken) {
                $cst->setFCMToken($currentToken);
                $tokenUpdate = true;
            }
        }
        echo json_encode(array("200",  "success|" . ($tokenUpdate ? "Token Updated on Server" : "Token Exist") . ". You are ready to chat"
        ));
    } else
        echo json_encode(array("300", "warning|Opps Could not set up token on Server"
        ));

?>