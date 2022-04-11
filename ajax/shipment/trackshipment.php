<?php
	$tracking_id = '';
	$data           = sanitizePostData($_POST);
    extract($data);
	Modal::load(array('Shipment', 'ShipmentType'));
    
	extract(App::exploreApiCredentilas('UpsShippingApi'));
	if(!$ServiceEnable){
		echo json_encode(array("300",  "warning|Service is Disabled by admin"));
		die;
	}
	$TrackingNumber = $tracking_id;
	include(BP.'api/ups/shipTrack.php');
	if(!$responseArray = json_decode(json_encode(simplexml_load_string($response)), true))
	{
		echo json_encode(array("300",  "warning|Shipment tracking service unavailable for this tracking number"));
		die;
	}
	//print_r($responseArray);die;
	if($responseArray['Response']['ResponseStatusDescription'] == 'Success'){
		$track = array();
		$Shipment = $responseArray['Shipment'];
		$shipAddress = $Shipment['Shipper']['Address'];
		$track['ShipperFrom'] = $shipAddress['AddressLine1'].$shipAddress['City'].$shipAddress['CountryCode'].$shipAddress['PostalCode'];
		$shipTo = $Shipment['ShipTo']['Address'];	
		$track['ShipperTo'] = $shipTo['City'].$shipTo['CountryCode'].$shipTo['PostalCode'];
		$track['Weight'] = $Shipment['ShipmentWeight']['Weight'].' '.$Shipment['ShipmentWeight']['UnitOfMeasurement']['Code'];
		$track['Service'] = $Shipment['Service']['Description'];
		$track['ReferenceNumber'] = $Shipment['ReferenceNumber']['Value'];
		$track['ShipmentIdentificationNumber'] = $Shipment['ShipmentIdentificationNumber'];
		$track['PickupDate'] = date('d M Y', strtotime(formatDate($Shipment['PickupDate'])));
		$Package = $Shipment['Package'];
		$track['Package']['DeliveryDate'] = isset($Package['DeliveryDate']) ? date('d M Y', strtotime(formatDate($Package['DeliveryDate']))) : "Not available";
		if(count($Package['Activity'])){
			foreach($Package['Activity'] as $activity){
				$track['Package']['Activity'][] = array(
								'Location'=> isset($activity['ActivityLocation']['Address']) ? (isset($activity['ActivityLocation']['Address']['City']) ? $activity['ActivityLocation']['Address']['City'] : $activity['ActivityLocation']['Address']['CountryCode']):"N/A",
								'Description'=>$activity['Status']['StatusType']['Description'],
								'Time'=>date('D,d M-Y h:iA', strtotime($activity['GMTDate'].' '.$activity['GMTTime'].$activity['GMTOffset']))
								);
			}
		}

		$html = "<table class='table'>";
		$html .= "<tr><td>Shipper</td><td>{$track['ShipperFrom']}</td></tr>";
		$html .= "<tr><td>Ship To</td><td>{$track['ShipperTo']}</td></tr>";
		$html .= "<tr><td>Weight</td><td>{$track['Weight']}</td></tr>";
		$html .= "<tr><td>Service</td><td>{$track['Service']}</td></tr>";
		$html .= "<tr><td>Ref.No.</td><td>{$track['ReferenceNumber']}</td></tr>";
		$html .= "<tr><td>Id No.</td><td>{$track['ShipmentIdentificationNumber']}</td></tr>";
		$html .= "<tr><td>Pickup Date</td><td>{$track['PickupDate']}</td></tr>";
	$html .= "<tr><td>Delivery Date</td><td>{$track['Package']['DeliveryDate']}</td></tr>";
		if(isset($track['Package']['Activity']))
		{
			$html .= "<tr><td colspan='2'><table class='table'><tr><th>Time</th><th>Location</th><th>Description</th></tr>";
			foreach($track['Package']['Activity'] as $_activity){
				$html .= "<tr><td>{$_activity['Time']}</td><td>{$_activity['Location']}</td><td>{$_activity['Description']}</td></tr>";
			}
			$html .= "</table></td></tr>";
		}
		$html .= "</table>";
		
		echo json_encode(array("200",  "success|Shipment tracking information loaded", $html, $track));
	}
	elseif($responseArray['Response']['ResponseStatusDescription'] == 'Failure') 
	{
		$errorCode = $responseArray['Response']['Error']['ErrorCode'];
		$errorMsg = $responseArray['Response']['Error']['ErrorDescription'];
		echo json_encode(array("300",  "warning|$errorCode - $errorMsg"));
	}
	else{
		echo json_encode(array("300",  "warning|Unexpected error $response"));
	}

?>