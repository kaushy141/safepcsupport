<?php
	//Hardware code 

	$hardware_code = checkData($_POST['hardware_code']);
    $hardwaretype  = new HardwareType(0);
    if ($hardwaretype->IsCodeAvailable($hardware_code))
        echo json_encode(array(
            200,
            "success|\"$hardware_code\" Hardware code is available to use"
        ));
    else
        echo json_encode(array(
            300,
            "danger|\"$hardware_code\" Hardware code is allready used..."
        ));

?>