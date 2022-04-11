<?php
	$status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Employee       = new Employee($idvalue);
        $Employee_fname = $Employee->get('user_fname');
        $status ? $Employee->Activate() : $Employee->Deactivate();
        Activity::add(status($status) . " Employee <b>" . $Employee_fname . "</b> staus");
        echo json_encode(array("200",  "success|User " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Employee " . status($status));
        $email->send("Employee \"" . $Employee_fname . "\" " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|User Status not found."
        ));

?>