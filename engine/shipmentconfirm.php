<?php
admin();
$request_id = $parameter1;
$action	=	"shipment/confirmshipmentqoutes";
$formHeading	=	"Confirm Shipment";
if($request_id != "")
{		
	Modal::load(array('Shipment', 'ShipmentShipper', 'ShipmentOrigin', 'ShipmentType', 'ShipmentRequest'));
	$shipmentRequest = new ShipmentRequest();
	
	if($shipmentData = $shipmentRequest->loadByMd5($request_id))
	{
		$shipment_id = 0;
		$isShipmentCreated = false;
		if($shipmentData['request_status'] == 1)
		{
			$isShipmentCreated = true;
			$shipment = new Shipment($shipmentData['request_shipment_id']);
			if($shipment->isExist()){
				$trackingData = $shipment->getDetails();
			}
		}
		
		$shipmentType = new ShipmentType($shipmentData['request_shipment_type_id']);
		$shipmentTypeData = $shipmentType->getDetails();		
		
		$shipmentShipper = new ShipmentShipper($shipmentData['request_shipment_shipper_id']);
		$shipmentShipperData = $shipmentShipper->getDetails();
		
		$shipmentOrigin = new ShipmentOrigin($shipmentData['request_shipment_origin_id']);
		$shipmentOriginData = $shipmentOrigin->getDetails();
		
		if($shipmentTypeData['shipment_type_code'] === 'UPS')
		{
			$requestArray = json_decode(json_encode(simplexml_load_string(trim($shipmentData['request_send']))), true);
			$responseArray = json_decode(json_encode(@simplexml_load_string(trim($shipmentData['request_response']))), true);		
			if(isset($responseArray['Response']['Error']))
			{
				$responseMessage = array(
					'status' => strtolower(trim($responseArray['Response']['Error']['ErrorSeverity'])),
					'message' => strtolower(trim($responseArray['Response']['Error']['ErrorDescription']))
				);
			}
			
			$shipment_products_array = $shipment_products = array();
			
			$shipment_for = $requestArray['Parameter']['shipment_for'];
			
			$shipment_for_id = $requestArray['Parameter']['shipment_for_id'];
			$shipment_products_array = explode(",", $requestArray['Parameter']['shipment_products']);
			$shipment_description = $requestArray['Parameter']['shipment_description'];
			
			$MonetaryValue = $responseArray['ShipmentCharges']['TransportationCharges']['MonetaryValue'];
			$CurrencyCode = $responseArray['ShipmentCharges']['TransportationCharges']['CurrencyCode'];
			$ServiceOptionsCharges = $responseArray['ShipmentCharges']['ServiceOptionsCharges']['MonetaryValue'];
			$TotalCharges = $responseArray['ShipmentCharges']['TotalCharges']['MonetaryValue'];
			
			$Weight = $responseArray['BillingWeight']['Weight'];
			$WeightCode = $responseArray['BillingWeight']['UnitOfMeasurement']['Code'];
			$ShipmentIdentificationNumber = $responseArray['ShipmentIdentificationNumber'];
			
			$AttentionName = $requestArray['Shipment']['ShipTo']['AttentionName'];
			$PhoneNumber = $requestArray['Shipment']['ShipTo']['PhoneNumber'];
			$Email = $requestArray['Shipment']['ShipTo']['Email'];
			
			$address = $requestArray['Shipment']['ShipTo']['Address'];
			$shipment_service_code = $requestArray['Shipment']['Service']['Code'];
			
		}
		elseif($shipmentTypeData['shipment_type_code'] === 'DPD')
		{
			$requestArray = json_decode(trim($shipmentData['request_send']), true);			
			$responseArray = json_decode(trim($shipmentData['request_response']), true);
			$shipment_products_array = $shipment_products = array();			
			$shipment_for = $requestArray['Parameter']['shipment_for'];			
			$shipment_for_id = $requestArray['Parameter']['shipment_for_id'];
			$shipment_description = $requestArray['Parameter']['shipment_description'];
			$shipment_products_array = explode(",", $requestArray['Parameter']['shipment_products']);
			
			$MonetaryValue = 'Not Calculated';
			$CurrencyCode = '';
			$ServiceOptionsCharges = 'Not Calculated';
			$TotalCharges = 'Not Available';
			
			$Weight = $requestArray['totalWeight'];
			$WeightCode = 'Unknown';
			//$ShipmentIdentificationNumber = $responseArray['loginResponse']['data']['geoSession'];
			$ShipmentIdentificationNumber = 'Not avilable yet';
			
			$AttentionName = $requestArray['Attentioner']['AttentionName'];
			$PhoneNumber = $requestArray['Attentioner']['PhoneNumber'];		
			$Email = $requestArray['Attentioner']['Email'];			
			$address = $requestArray['Address'];
			
			$shipment_service_code = $requestArray['soapHeaderBody']['consignment'][0]['networkCode'];	
		}
		elseif($shipmentTypeData['shipment_type_code'] === 'FDX')
		{
			$requestArray = json_decode(trim($shipmentData['request_send']), true);			
			$soap     = simplexml_load_string($shipmentData['request_response']);
			$responseArray = $soap->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->RateReply;
			$responseMessage = array(
				'status' => 'warning',
				'message' => strtolower(trim($responseArray->Notifications->Message))
			);
//$customerId = (string) $response->CreateRebillCustomerResult->RebillCustomerID;
			//echo "<pre>"; 
			//print_r($responseArray);
			$ShipmentRateDetail = $responseArray->RateReplyDetails->RatedShipmentDetails->ShipmentRateDetail;
			//print_r($ShipmentRateDetail);
			//echo "</pre>";
			$shipment_products_array = $shipment_products = array();			
			$shipment_for = $requestArray['Parameter']['shipment_for'];			
			$shipment_for_id = $requestArray['Parameter']['shipment_for_id'];
			$shipment_description = $requestArray['Parameter']['shipment_description'];
			$shipment_products_array = explode(",", $requestArray['Parameter']['shipment_products']);
			
			$CurrencyCode = $ShipmentRateDetail->TotalNetCharge->Currency;
			$MonetaryValue = $ShipmentRateDetail->TotalBaseCharge->Amount;			
			$ServiceOptionsCharges = $ShipmentRateDetail->TotalSurcharges->Amount;
			$TotalCharges = $ShipmentRateDetail->TotalNetCharge->Amount;
			
			$Weight = $requestArray['RequestedShipment']['TotalWeight']['Value'];
			$WeightCode = $requestArray['RequestedShipment']['TotalWeight']['Units'];
			//$ShipmentIdentificationNumber = $responseArray['loginResponse']['data']['geoSession'];
			$ShipmentIdentificationNumber = 'Not avilable yet';
			
			$AttentionName = $requestArray['Attentioner']['AttentionName'];
			$PhoneNumber = $requestArray['Attentioner']['PhoneNumber'];		
			$Email = $requestArray['Attentioner']['Email'];			
			$address = $requestArray['Address'];			
			
			
			$shipment_service_code = $requestArray['RequestedShipment']['ServiceType'];	
		}
		if($shipment_for == 'weborder')
		{
			$weborder = new WebsiteOrder($shipment_for_id);
			$data = $weborder->load();
			$weborderProducts = new WebsiteOrderProduct();
			$productList = $weborderProducts->getList($data['web_order_id']);
			
			if($productList)
			{
				foreach($productList as $_product)
				{
					if(in_array($_product['wo_id'], $shipment_products_array))
					{
						$shipment_products[] = array(
							"product_id" => $_product['wo_id'],
							"product_name" => $_product['wo_product_name'],
							"product_sku" => $_product['wo_product_sku'],
							"product_image" => $_product['wo_product_image'],
							"product_quantiry" => $_product['wo_product_quantity'],
							"product_srno" => $_product['wo_product_srno']
						);
					}
				}
			}	
		}
		$shipment_description = rtrim($shipment_description, ",");
		
		include("engine/inc/addshipmentconfirmform.php");
		
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>