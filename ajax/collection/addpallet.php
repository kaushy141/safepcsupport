<?php

	$pallet_name = $pallet_type = $pallet_location = "";
	$pallet_status = 1;
	$pallet_is_full = $pallet_capacity = 0;
	$pallet_created_by = $_SESSION['user_id'];
	$pallet_cert_customer = "";
	$pallet_cert_address = "";
	$pallet_cert_telephone = "";
	$pallet_cert_date = "";
	$pallet_serial_number = "";
	$pallet_tester = "";
	$data  = sanitizePostData($_POST);
    extract($data);
	$pallet = new Pallet();	
	$pallet_code = $pallet->getPalletCode();
	if($pallet_name !="" && $pallet_type!=""){
		$pallet_id = $pallet->insert( array(
										"pallet_code" 				=>	$pallet_code, 
										"pallet_name" 				=>	$pallet_name, 
										"pallet_capacity"			=>  $pallet_capacity,
										"pallet_location"			=>  $pallet_location,
										"pallet_type" 				=>	$pallet_type, 
										"pallet_status" 			=>	$pallet_status,
										"pallet_is_full"			=>	$pallet_is_full, 
										"pallet_created_by"			=>	$pallet_created_by,
										"pallet_created_date"		=>	"NOW()",
										"pallet_cert_customer"		=>	$pallet_cert_customer,
										"pallet_cert_address"		=>	$pallet_cert_address,
										"pallet_cert_telephone"		=>	$pallet_cert_telephone,
										"pallet_cert_date"			=>	$pallet_cert_date,
										"pallet_serial_number"		=>	$pallet_serial_number,
										"pallet_tester"				=>	$pallet_tester
										)										
									);
		echo json_encode(array("200",  "success|Apllet Added Successfully. Pallet Code is #$pallet_code",
            $pallet_id
        ));
	}
	else{
		echo json_encode(array("300",  "Warning|Apllet Couldn't added. Try again."));
	}

?>