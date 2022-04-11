<?php
	Modal::load(array('Shipment', 'ShipmentType'));
    
    $shipment_to_city =  $shipment_to_state_code =  $shipment_to_country_code =  $shipment_to_post_code = "";
	$shipment_type_id =  0;
    $data           = sanitizePostData($_POST);
    $shipment        = new Shipment(0);
    extract($data);
	$shipmentType = new ShipmentType($shipment_type_id);
	if($shipmentType->isExist()){
		$shipmentTypeDetails = $shipmentType->getDetails();
		if($shipmentTypeDetails['shipment_type_code'] === 'UPS'){
			$City = $shipment_to_city;
			$PostalCode = $shipment_to_post_code;
			extract(App::exploreApiCredentilas('UpsShippingApi'));
			if(!$ServiceEnable){
				echo json_encode(array("300",  "warning|Service is Disabled by admin"));
				die;
			}
			ob_start();
			include(BP.'api/ups/addressValidate.php');
			$result = ob_get_clean();
			echo json_encode(array("200",  "warning|$result"));
		}		
	}
	else{
		echo json_encode(array("300",  "warning|Shipment type not found"));
	}

?>