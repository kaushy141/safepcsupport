<?php	
	$status = $idvalue = 0;
    $data   = sanitizePostData($_POST);
    extract($data);
    if ($idvalue != 0) {
        $Carrier      = new Carrier($idvalue);
        $Carrier_name = $Carrier->get('carrier_name');
        $status ? $Carrier->Activate() : $Carrier->Deactivate();
        Activity::add(status($status) . " Carrier <b>" . $Carrier_name . "</b> staus");
        echo json_encode(array("200",  "success|Carrier \"" . $Carrier_name . "\" " . status($status) . " Successfully"
        ));
        $email = new Email($app->siteName . " : " . "Carrier " . status($status));
        $email->send("Employee \"" . $Carrier_name . "\" " . status($status) . " on " . $app->siteName);
    } else
        echo json_encode(array("300", "warning|User Status not found."
        ));
?>