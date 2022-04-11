<?php
	$shipment_id = '';
	$data           = sanitizePostData($_POST);
    extract($data);
	Modal::load(array('Shipment', 'ShipmentType'));
	if($shipment_id){
		$shipment = new Shipment($shipment_id);
		if($shipment->isExist())
		{
			$shipment->markDownloader();
			$record = $shipment->getDetails();
			$shipmentType = new ShipmentType($record['shipment_type_id']);
			$shipmentTypeData = $shipmentType->getDetails();
			if($shipmentTypeData['shipment_type_code'] === 'UPS')	
			{
				$label = array(
				'image' => $record['shipment_label_image'],
				'format' => strtolower(trim($record['shipment_label_image_format']))
				);
				echo json_encode(array("200",  "success|Shipment label genearetd successfully", $label));
			}
			elseif($shipmentTypeData['shipment_type_code'] === 'DPD')	
			{
				extract(App::exploreApiCredentilas('DpdShippingApi'));
				include(BP.'api/dpd/loginRequest.php');			
				$responseArray = json_decode($response, true);
				$geoSession = '';
				if($responseArray['error'] == '')
				{
					$shipmentId = $record['shipment_identification_number'];
					$geoSession = $responseArray['data']['geoSession'];
					include(BP.'api/dpd/shipmentLabel.php'); 
					echo json_encode(array("200", "success|Shipment label genearetd successfully",$labelResponse));
				}
				else
					echo json_encode(array("300",  "warning|Shipment label not found"));
			}
			else{
				echo json_encode(array("300",  "warning|Shipment type not found"));
			}			
		}
		else{
			echo json_encode(array("300",  "warning|Shipment not found"));
		}
			
	}
	else{
			echo json_encode(array("300",  "warning|Invalid Shipment data"));
		}

?>