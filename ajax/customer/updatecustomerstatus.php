<?php

    $status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Customer      = new Customer($idvalue);
        $Customer_name = $Customer->get('customer_fname');
        $status ? $Customer->Activate() : $Customer->Deactivate();
        Activity::add(status($status) . " Customer <b>" . $Customer_name . "</b> staus");
        echo json_encode(array("200",  "success|Customer $Customer_name " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Customer " . status($status));
        $email->send("Customer \"" . $Customer_name . "\" " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|Customer Status not found."
        ));

?>