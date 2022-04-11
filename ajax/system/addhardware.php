<?php

    $hardware_name  = $hardware_code = $html = "";
    $hardware_status = 1;
	$getExtraOption = false;
    $data           = sanitizePostData($_POST);
    extract($data);
	$HardwareType = new HardwareType(0);
    if ($hardware_name != "" && $hardware_code != "" && $HardwareType->IsCodeAvailable($hardware_code) && $HardwareType->IsNameAvailable($hardware_name)) 
	{
		$hardware_id     = $HardwareType->add($hardware_name, $hardware_code, $hardware_status);
		if ($hardware_id) {
			if (isset($getExtraOption) == true)
				$html = $HardwareType->getOptions();
			echo json_encode(array("200",  "success|New Hardware Type Addedd Successfully", $hardware_id, $html));
			$email = new Email($app->siteName . " : " . "New Hardware Type Added");
			$email->send("New Hardware Type \"$hardware_name\" added on " . $app->siteName);
		} else
			echo json_encode(array("300", "warning|Hardware could not addedd. Please try again."
			));
    } else
        echo json_encode(array("300", "warning|Hardware name found invalid."
        ));


?>