<?php
	Modal::load(array('Shipment', 'ShipmentShipper', 'ShipmentOrigin', 'ShipmentType', 'ShipmentRequest'));
    
    $shipment_billing_weight =  $shipment_billing_weight_unit =  $shipment_to_name =  $shipment_to_company =  $shipment_to_phone_number = $shipment_to_email = $shipment_to_address_line1 =  $shipment_to_city =  $shipment_to_state_code =  $shipment_to_country_code =  $shipment_to_post_code = $shipment_for = $shipment_description = "";
	$shipment_type_id =  $shipment_shipper_id=  $shipment_origin_id =  $shipment_for_id = 0;
    $data           = sanitizePostData($_POST);
    $shipment        = new Shipment(0);
    extract($data);
	$shipmentType = new ShipmentType($shipment_type_id);
	if($shipmentType->isExist()){
		$shipmentTypeDetails = $shipmentType->getDetails();
		
		$shipmentShipper = new ShipmentShipper($shipment_shipper_id);
		$shipmentShipperData = $shipmentShipper->getDetails();
		extract($shipmentShipperData);
		
		$shipmentOrigin = new ShipmentOrigin($shipment_origin_id);
		$shipmentOriginData = $shipmentOrigin->getDetails();
		extract($shipmentOriginData);
			
		if($shipmentTypeDetails['shipment_type_code'] === 'UPS')
		{
			$City = $shipment_to_city;
			$PostalCode = $shipment_to_post_code;
			extract(App::exploreApiCredentilas('UpsShippingApi'));
			if(!$ServiceEnable){
				echo json_encode(array("300",  "warning|Service is Disabled by admin"));
				die;
			}		
			
			$shipment_service_name = Shipment::getUPSShipmentShipingServiceName($shipment_service_code);
			include(BP.'api/ups/shipConfirm.php'); 
			$responseArray = json_decode(json_encode(simplexml_load_string($response)), true); 
			if($responseArray['Response']['ResponseStatusDescription'] == 'Success'){
				$parameter = $shipmentConfirmRequestXML->addChild ( 'Parameter' );
				$parameter->addChild ( "shipment_for", "$shipment_for" );
				$parameter->addChild ( "shipment_for_id", "$shipment_for_id" );
				$parameter->addChild ( "shipment_description", "$shipment_description" );
				if(count($shipment_products)){
					$parameter->addChild ( "shipment_products", implode(",", $shipment_products) );
				}
				
				$shipmentRequest = new ShipmentRequest();
				$request_id = $shipmentRequest->insert(array(
				'request_shipment_type_id'=>$shipment_type_id, 
				'request_shipment_shipper_id'=>$shipment_shipper_id, 
				'request_shipment_origin_id'=>$shipment_origin_id, 
				'request_send'=>$shipmentConfirmRequestXML->asXML(), 
				'request_response'=>$response, 
				'request_status'=> 0));
				/*
				$shipment_transportation_charges = $responseArray['ShipmentCharges']['TransportationCharges']['MonetaryValue'];
				$shipment_service_options_charges = $responseArray['ShipmentCharges']['ServiceOptionsCharges']['MonetaryValue'];
				$shipment_total_charges = $responseArray['ShipmentCharges']['TotalCharges']['MonetaryValue'];
				$shipment_grand_total_charges = $responseArray['ShipmentCharges']['TotalCharges']['MonetaryValue'];
				$shipment_charge_currency = $responseArray['ShipmentCharges']['TotalCharges']['CurrencyCode'];
				$shipment_billing_weight = $responseArray['BillingWeight']['Weight'];
				$shipment_billing_weight_unit = $responseArray['BillingWeight']['UnitOfMeasurement']['Code'];
				*/				
				echo json_encode(array("200",  "success|Shipment qoute loaded", md5($request_id)));
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
		}
		elseif($shipmentTypeDetails['shipment_type_code'] === 'DPD'){
			extract(App::exploreApiCredentilas('DpdShippingApi'));
			if(!$ServiceEnable){
				echo json_encode(array("300",  "warning|Service is Disabled by admin"));
				die;
			}	
			include(BP.'api/dpd/loginRequest.php');			
			$responseArray = json_decode($response, true);
			$geoSession = '';
			if($responseArray['error'] == '')
			{
				$geoSession = $responseArray['data']['geoSession'];
				$testShipment= "?test=true";
				$soapHeaderBody = NULL;
				include(BP.'api/dpd/shipmentRquest.php'); 
				$responseArray = json_decode($response, true);			
				if(empty($responseArray['error']))
				{
					$requestSendArray = array();
					$requestSendArray['soapHeaderBody'] = $soapHeaderBody;
					$requestSendArray['totalWeight'] = $shipment_billing_weight;
					$requestSendArray['geoSession'] = $geoSession;
					$requestSendArray['Parameter'] = array(
						"shipment_for"=> $shipment_for,
						"shipment_for_id"=> $shipment_for_id,
						"shipment_description"=> $shipment_description,
						"shipment_products" => implode(",", $shipment_products)
						);
					$requestSendArray['Address'] = array(
						"AddressLine1"=> $shipment_to_address_line1,
						"City"=> $shipment_to_city,
						"StateCode"=> $shipment_to_state_code,
						"CountryCode"=> $shipment_to_country_code,
						"PostalCode"=> $shipment_to_post_code
						);
					$requestSendArray['Attentioner'] = array(
						"AttentionName"=> $shipment_to_name,
						"CompanyName"=> $shipment_to_company,
						"PhoneNumber"=> $shipment_to_phone_number,
						"Email" => $shipment_to_email
					);
					
					$shipmentRequest = new ShipmentRequest();
					$request_id = $shipmentRequest->insert(array(
					'request_shipment_type_id'=>$shipment_type_id, 
					'request_shipment_shipper_id'=>$shipment_shipper_id, 
					'request_shipment_origin_id'=>$shipment_origin_id, 
					'request_send'=>json_encode($requestSendArray), 
					'request_response'=>json_encode($response), 
					'request_status'=> 0));
					echo json_encode(array("200",  "success|Shipment qoute loaded", md5($request_id)));
				}
				else
				{
					if(isset($responseArray['error'][0]))
					$errorResp = $responseArray['error'][0];
					else
						$errorResp = $responseArray['error'];
					echo json_encode(array("300",  "warning|{$errorResp['errorCode']} - {$errorResp['errorMessage']} : {$errorResp['obj']}"));
					die;
				}				
			}
			else
			{
				echo json_encode(array("300",  "warning|{$responseArray['errorCode']} - {$responseArray['errorMessage']}"));
			}
		}
		elseif($shipmentTypeDetails['shipment_type_code'] === 'FDX'){
			extract(App::exploreApiCredentilas('FedexShippingApi'));
			if(!$ServiceEnable){
				echo json_encode(array("300",  "warning|Service is Disabled by admin"));
				die;
			}	
			include(BP.'api/fedex/shipmentRates.php');			
			if($isSuccess)
			{
				$request['totalWeight'] = $shipment_billing_weight;
				$request['Parameter'] = array(
					"shipment_for"=> $shipment_for,
					"shipment_for_id"=> $shipment_for_id,
					"shipment_description"=> $shipment_description,
					"shipment_products" => implode(",", $shipment_products)
					);
				$request['Address'] = array(
					"AddressLine1"=> $shipment_to_address_line1,
					"City"=> $shipment_to_city,
					"StateCode"=> $shipment_to_state_code,
					"CountryCode"=> $shipment_to_country_code,
					"PostalCode"=> $shipment_to_post_code
					);
				$request['Attentioner'] = array(
					"AttentionName"=> $shipment_to_name,
					"CompanyName"=> $shipment_to_company,
					"PhoneNumber"=> $shipment_to_phone_number,
					"Email" => $shipment_to_email
				);
				$request_send = json_encode($request);
				$shipmentRequest = new ShipmentRequest();
				$request_id = $shipmentRequest->insert(array(
				'request_shipment_type_id'=>$shipment_type_id, 
				'request_shipment_shipper_id'=>$shipment_shipper_id, 
				'request_shipment_origin_id'=>$shipment_origin_id, 
				'request_send'=>"$request_send", 
				'request_response'=>"$response_received", 
				'request_status'=> 0));				
				echo json_encode(array("200",  "success|Shipment qoute loaded", md5($request_id)));
			}
			else
				echo json_encode(array("300",  "warning|$outputmessage"));
		}
	}

?>