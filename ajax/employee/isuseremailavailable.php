<?php
	if (isset($_POST['user_email'])) {
        $user_email = checkData($_POST['user_email']);
        $employee   = new Employee(0);
        if ($employee->IsEmialAvailable($user_email))
            echo json_encode(array(
                200,
                "success|\"$user_email\" is available to use"
            ));
        else
            echo json_encode(array(
                300,
                "danger|\"$user_email\" is allready used..."
            ));
    } else
        echo json_encode(array(
            300,
            "danger|Data Not found..."
        ));

?>