<?php
	$request_id = $shipment_description = '';
	$data           = sanitizePostData($_POST);
    extract($data);	
	Modal::load(array('Shipment', 'ShipmentShipper', 'ShipmentOrigin', 'ShipmentType', 'ShipmentRequest'));
	$shipmentRequest = new ShipmentRequest();	
	if($shipmentData = $shipmentRequest->loadByMd5($request_id))
	{
		//print_r($shipmentData);die;
		$shipmentType = new ShipmentType($shipmentData['request_shipment_type_id']);
		$shipmentTypeData = $shipmentType->getDetails();
		$shipmentCreated = false;
		if($shipmentTypeData['shipment_type_code'] === 'UPS')
		{			
			$requestArray = json_decode(json_encode(simplexml_load_string(trim($shipmentData['request_send']))), true);
			$responseArray = json_decode(json_encode(@simplexml_load_string(trim($shipmentData['request_response']))), true);
			
			$ShipmentDigest = $responseArray['ShipmentDigest'];
			extract(App::exploreApiCredentilas('UpsShippingApi'));
			if(!$ServiceEnable){
				echo json_encode(array("300",  "warning|Service is Disabled by admin"));
				die;
			}
			include(BP.'api/ups/shipAccept.php'); 		
			$resp = new SimpleXMLElement ( $response );
			
			if($resp->Response->ResponseStatusDescription == 'Success')
			{
				$shipment = new Shipment();
				$shipmentData = array(
					'shipment_code' => $shipment->getShipmentCode(), 
					'shipment_type_id' => $shipmentData['request_shipment_type_id'], 
					'shipment_shipper_id' => $shipmentData['request_shipment_shipper_id'], 
					'shipment_origin_id' => $shipmentData['request_shipment_origin_id'],
					'shipment_identification_number' => $responseArray['ShipmentIdentificationNumber'],
					'shipment_shipment_digest' => $responseArray['ShipmentDigest'],
					'shipment_transportation_charges' => setData($responseArray['ShipmentCharges']['TransportationCharges']['MonetaryValue']),
					'shipment_service_options_charges' => setData($responseArray['ShipmentCharges']['ServiceOptionsCharges']['MonetaryValue']),
					'shipment_total_charges' => setData($responseArray['ShipmentCharges']['TotalCharges']['MonetaryValue']),
					'shipment_grand_total_charges' => setData($responseArray['ShipmentCharges']['TotalCharges']['MonetaryValue']),
					'shipment_charge_currency' => setData($responseArray['ShipmentCharges']['TotalCharges']['CurrencyCode']),
					'shipment_billing_weight' => setData($responseArray['BillingWeight']['Weight']),
					'shipment_billing_weight_unit' => setData($responseArray['BillingWeight']['UnitOfMeasurement']['Code']),
					'shipment_to_name' => setData($requestArray['Shipment']['ShipTo']['AttentionName']),
					'shipment_to_company' => setData($requestArray['Shipment']['ShipTo']['CompanyName']),
					'shipment_to_phone_number' => setData($requestArray['Shipment']['ShipTo']['PhoneNumber']),
					'shipment_to_address_line1' => setData($requestArray['Shipment']['ShipTo']['Address']['AddressLine1']),
					'shipment_to_city' => setData($requestArray['Shipment']['ShipTo']['Address']['City']),
					'shipment_to_state_code' => isset($requestArray['Shipment']['ShipTo']['Address']['StateCode']) ? setData($requestArray['Shipment']['ShipTo']['Address']['StateCode']):'',
					'shipment_to_country_code' => setData($requestArray['Shipment']['ShipTo']['Address']['CountryCode']),
					'shipment_to_country_name' => setData(Country::getValueByParameter('name', 'iso2', $requestArray['Shipment']['ShipTo']['Address']['CountryCode'])),
					'shipment_to_post_code' => setData($requestArray['Shipment']['ShipTo']['Address']['PostalCode']),
					'shipment_tracking_number' => setData($resp->ShipmentResults->PackageResults->TrackingNumber),
					'shipment_label_image' => setData($resp->ShipmentResults->PackageResults->LabelImage->GraphicImage),
					'shipment_label_image_format' => setData($resp->ShipmentResults->PackageResults->LabelImage->LabelImageFormat->Code),
					'shipment_created_date' => 'NOW()',
					'shipment_created_by' => getLoginId(),
					'shipment_status' => 1
				);
				$shipmentCreated = true;
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
		elseif($shipmentTypeData['shipment_type_code'] === 'DPD')
		{
			extract(App::exploreApiCredentilas('DpdShippingApi'));
			$shipmentShipper = new ShipmentShipper($shipmentData['request_shipment_shipper_id']);
			$shipmentShipperData = $shipmentShipper->getDetails();			
			extract($shipmentShipperData);
			$shipmentOrigin = new ShipmentOrigin($shipmentData['request_shipment_origin_id']);
			$shipmentOriginData = $shipmentOrigin->getDetails();
			extract($shipmentOriginData);
			$requestArray = json_decode(trim($shipmentData['request_send']), true);			
			$responseArray = json_decode(trim($shipmentData['request_response']), true);			
			$testShipment 	= "";	
			$soapHeaderBody = 	$requestArray['soapHeaderBody'];
			$geoSession 	= $requestArray['geoSession']; 	
			include(BP.'api/dpd/shipmentRquest.php'); 
			$responseArray = json_decode($response, true);			
			if(empty($responseArray['error']))
			{
				$shipmentId = $responseArray['data']['shipmentId'];
				$consignmentNumber = $responseArray['data']['consignmentDetail'][0]['consignmentNumber'];
				$parcelNumbers =  (isset($responseArray['data']['consignmentDetail'][0]['parcelNumbers']) && is_array($responseArray['data']['consignmentDetail'][0]['parcelNumbers'])) ? implode(',',$responseArray['data']['consignmentDetail'][0]['parcelNumbers']) : "";
				
				$shipment_label_image = isset($labelResponse) ? "$labelResponse" : '';
				
				$shipmentCreated = true;
				
				$shipment = new Shipment();
				$shipmentData = array(
				'shipment_code' => $shipment->getShipmentCode(), 
				'shipment_type_id' => $shipmentData['request_shipment_type_id'], 
				'shipment_shipper_id' => $shipmentData['request_shipment_shipper_id'], 
				'shipment_origin_id' => $shipmentData['request_shipment_origin_id'],
				'shipment_identification_number' => $shipmentId,
				'shipment_shipment_digest' => $parcelNumbers,
				'shipment_transportation_charges' => '-',
				'shipment_service_options_charges' => '-',
				'shipment_total_charges' => '-',
				'shipment_grand_total_charges' => '-',
				'shipment_charge_currency' => '-',
				'shipment_billing_weight' => $requestArray['soapHeaderBody']['consignment'][0]['totalWeight'],
				'shipment_billing_weight_unit' => '-',
				'shipment_to_name' => $requestArray['Attentioner']['AttentionName'],
				'shipment_to_company' => $requestArray['Attentioner']['CompanyName'],
				'shipment_to_phone_number' => $requestArray['Attentioner']['PhoneNumber'],
				'shipment_to_address_line1' => $requestArray['Address']['AddressLine1'],
				'shipment_to_city' => $requestArray['Address']['City'],
				'shipment_to_state_code' => $requestArray['Address']['StateCode'],
				'shipment_to_country_code' => $requestArray['Address']['CountryCode'],
				'shipment_to_country_name' => setData(Country::getValueByParameter('name', 'iso2', $requestArray['Address']['CountryCode'])),
				'shipment_to_post_code' => $requestArray['Address']['PostalCode'],
				'shipment_tracking_number' => setData($consignmentNumber),
				'shipment_label_image' => $shipment_label_image,
				'shipment_label_image_format' => '',
				'shipment_created_date' => 'NOW()',
				'shipment_created_by' => getLoginId(),
				'shipment_status' => 1
				);				
			}
			else
			{				
				if(isset($responseArray['error'][0]))
					$errorResp = $responseArray['error'][0];
				else
					$errorResp = $responseArray['error'];
				echo json_encode(array("300",  "warning|{$errorResp['errorCode']} - {$errorResp['errorMessage']}"));
				die;
			}
		}
		elseif($shipmentTypeData['shipment_type_code'] === 'FDX')
		{
			extract(App::exploreApiCredentilas('FedexShippingApi'));
			$shipmentShipper = new ShipmentShipper($shipmentData['request_shipment_shipper_id']);
			$shipmentShipperData = $shipmentShipper->getDetails();			
			extract($shipmentShipperData);
			$shipmentOrigin = new ShipmentOrigin($shipmentData['request_shipment_origin_id']);
			$shipmentOriginData = $shipmentOrigin->getDetails();
			extract($shipmentOriginData);
			$requestArray = json_decode(trim($shipmentData['request_send']), true);			
			$soap = simplexml_load_string($shipmentData['request_response']);
			$responseArray = $soap->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->RateReply;
			
			$ShipmentRateDetail = $responseArray->RateReplyDetails->RatedShipmentDetails->ShipmentRateDetail;
			
			$CurrencyCode = $ShipmentRateDetail->TotalNetCharge->Currency;
			$MonetaryValue = $ShipmentRateDetail->TotalBaseCharge->Amount;			
			$ServiceOptionsCharges = $ShipmentRateDetail->TotalSurcharges->Amount;
			$TotalCharges = $ShipmentRateDetail->TotalNetCharge->Amount;
			
			$shipment_products_array = $shipment_products = array();			
			$shipment_for = $requestArray['Parameter']['shipment_for'];			
			$shipment_for_id = $requestArray['Parameter']['shipment_for_id'];
			$shipment_description = $requestArray['Parameter']['shipment_description'];
			$shipment_products_array = explode(",", $requestArray['Parameter']['shipment_products']);
						
			$shipment_billing_weight = $requestArray['RequestedShipment']['TotalWeight']['Value'];
			$shipment_billing_weight_unit = $requestArray['RequestedShipment']['TotalWeight']['Units'];
			//$ShipmentIdentificationNumber = $responseArray['loginResponse']['data']['geoSession'];
			$ShipmentIdentificationNumber = 'Not avilable yet';
			
			$shipment_to_name = $requestArray['Attentioner']['AttentionName'];
			$shipment_to_company = $requestArray['Attentioner']['CompanyName'];
			$shipment_to_phone_number = $requestArray['Attentioner']['PhoneNumber'];		
			$shipment_to_email = $requestArray['Attentioner']['Email'];			
			$address = $requestArray['Address'];			
			
			$shipment_to_address_line1 = $address["AddressLine1"];
			$shipment_to_city = $address["City"];
			$shipment_to_state_code = $address["StateCode"];
			$shipment_to_country_code = $address["CountryCode"];
			$shipment_to_post_code = $address["PostalCode"];
			
			$shipment_service_code = $requestArray['RequestedShipment']['ServiceType'];	
			$isSuccess = false;
			include(BP.'api/fedex/shipmentRequest.php');			
			if($isSuccess==true)
			{
				$shipmentCreated = true;
				$shipment_tracking_number =  $response->CompletedShipmentDetail->MasterTrackingId->TrackingNumber;
				$shipment_label_image_format =  $response->CompletedShipmentDetail->CompletedPackageDetails->Label->ImageType;
				$shipment_label_image = $response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image;
				$shipment_shipment_digest = $response->CompletedShipmentDetail->CompletedPackageDetails->OperationalDetail->Barcodes->BinaryBarcodes->Value;
				$shipment_identification_number = $response->CompletedShipmentDetail->CompletedPackageDetails->OperationalDetail->Barcodes->StringBarcodes->Value;
				
				$shipment = new Shipment();
				$shipment_code = $shipment->getShipmentCode();
				$shipmentData = array(
					'shipment_code' => $shipment_code, 
					'shipment_type_id' => $shipmentData['request_shipment_type_id'], 
					'shipment_shipper_id' => $shipmentData['request_shipment_shipper_id'], 
					'shipment_origin_id' => $shipmentData['request_shipment_origin_id'],
					'shipment_identification_number' => $shipment_identification_number,
					'shipment_shipment_digest' => sanitizePostData($shipment_shipment_digest),
					'shipment_transportation_charges' => "$MonetaryValue",
					'shipment_service_options_charges' => "$ServiceOptionsCharges",
					'shipment_total_charges' => "$TotalCharges",
					'shipment_grand_total_charges' => "$TotalCharges",
					'shipment_charge_currency' => "$CurrencyCode",
					'shipment_billing_weight' => $shipment_billing_weight,
					'shipment_billing_weight_unit' => $shipment_billing_weight_unit,
					'shipment_to_name' => $requestArray['Attentioner']['AttentionName'],
					'shipment_to_company' => $requestArray['Attentioner']['CompanyName'],
					'shipment_to_phone_number' => $requestArray['Attentioner']['PhoneNumber'],
					'shipment_to_address_line1' => $requestArray['Address']['AddressLine1'],
					'shipment_to_city' => $requestArray['Address']['City'],
					'shipment_to_state_code' => $requestArray['Address']['StateCode'],
					'shipment_to_country_code' => $requestArray['Address']['CountryCode'],
					'shipment_to_country_name' => setData(Country::getValueByParameter('name', 'iso2', $requestArray['Address']['CountryCode'])),
					'shipment_to_post_code' => $requestArray['Address']['PostalCode'],
					'shipment_tracking_number' => setData($shipment_tracking_number),
					'shipment_label_image' => sanitizePostData($shipment_label_image),
					'shipment_label_image_format' => sanitizePostData($shipment_label_image_format),
					'shipment_created_date' => 'NOW()',
					'shipment_created_by' => getLoginId(),
					'shipment_status' => 1
				);
			}
			else
			{
				echo json_encode(array("300",  "warning|Unable to create shipment $outputmessage"));
			}
		}
		//print_r($shipmentData);die;
		if($shipmentCreated)
		{
			$shipment = new Shipment();
			
			$shipment_id = $shipment->insert($shipmentData);
			$shipmentRequest->Activate();
			Activity::add("Created new Shipment|^|$shipment_code", "T", $shipment_id);
			$shipmentRequest->update(array('request_shipment_id'=>$shipment_id));			
			$shipment_for = $requestArray['Parameter']['shipment_for'];		
			$shipment_for_id = $requestArray['Parameter']['shipment_for_id'];
			$shipment_products_array = explode(",", $requestArray['Parameter']['shipment_products']);
			
			if($shipment_for == 'weborder')
			{
				$weborder = new WebsiteOrder($shipment_for_id);
				$data = $weborder->load();
				 $shipment->update(array(
										'shipment_reference_number' => $data['web_order_number'],
										'shipment_reference_code' => 'O'
										)
									);
				$weborderProducts = new WebsiteOrderProduct();
				$productList = $weborderProducts->getList($data['web_order_id']);
				$productShipCount = 0;				
				if($productList)
				{
					foreach($productList as $_product)
					{
						if($_product['wo_shipment_tracking'] != ''){
							$productShipCount++;
						}
						if(in_array($_product['wo_id'], $shipment_products_array))
						{
							$websiteOrderProduct = new WebsiteOrderProduct($_product['wo_id']);
							$websiteOrderProduct->update(array('wo_shipment_tracking'=>$shipmentData['shipment_tracking_number']));	$productShipCount++;						
						}
					}
				}
				$web_order_shipment_status = $productShipCount >= count($productList) ? SHIPMENT_STATUS_SHIPPED : SHIPMENT_STATUS_SHIPPED_PARTIALY;
				$weborder->update(array('web_order_shipment_status'=>$web_order_shipment_status));				
			}
			
			echo json_encode(array("200",  "success|Shipment created successfully", "viewshipment/$shipment_id"));	
		}
		else
		{
			echo json_encode(array("300",  "warning|Unable to create shipment :("));
		}
	}

?>