<?php

	$pallet_name = $pallet_type = $pallet_location = "";
	$pallet_status = $pallet_is_full = $pallet_id = $pallet_capacity = 0;
	$data  = sanitizePostData($_POST);
    extract($data);	
	if($pallet_id !=0 && $pallet_name !="" && $pallet_type!=""){
		
		$pallet = new Pallet($pallet_id);
		$pallet->update( array(
								"pallet_name" 				=>  $pallet_name, 
								"pallet_location"			=>  $pallet_location,
								"pallet_capacity"			=>  $pallet_capacity,
								"pallet_type" 				=>  $pallet_type, 
								"pallet_status" 			=>  $pallet_status, 
								"pallet_is_full" 			=>  $pallet_is_full,
								"pallet_cert_customer"		=>	$pallet_cert_customer,
								"pallet_cert_address"		=>	$pallet_cert_address,
								"pallet_cert_telephone"		=>	$pallet_cert_telephone,
								"pallet_cert_date"			=>	$pallet_cert_date,
								"pallet_serial_number"		=>	$pallet_serial_number,
								"pallet_tester"				=>	$pallet_tester
								)
						);
		echo json_encode(array("200",  "success|Pallet Updated Successfully.",
            $pallet_id
        ));
	}
	else{
		echo json_encode(array("300",  "Warning|Pallet Couldn't Updated. Try again."));
	}

?>