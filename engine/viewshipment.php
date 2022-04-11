<?php
admin();
$shipment_id = $parameter1;
$action	=	"shipment/confirmshipmentqoutes";
if($shipment_id != "")
{		
	Modal::load(array('Shipment', 'ShipmentShipper', 'ShipmentOrigin', 'ShipmentType', 'ShipmentRequest'));
	$shipment = new Shipment($shipment_id);
	
	if($trackingData = $shipment->getDetails())
	{		
		$formHeading	=	"Shipment #$trackingData[shipment_code]";
		$isShipmentCreated = true;
		
		$shipmentRequest = new ShipmentRequest();
		$shipmentData = $shipmentRequest->loadByShipmentId($trackingData['shipment_id']);
		$request_id= $shipmentData['request_id'];		
		
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
			$ShipmentRateDetail = $responseArray->RateReplyDetails->RatedShipmentDetails->ShipmentRateDetail;
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
		$shipment_products_array = $shipment_products = array();
		
		$shipment_for = $requestArray['Parameter']['shipment_for'];		
		$shipment_for_id = $requestArray['Parameter']['shipment_for_id'];
		$shipment_products_array = explode(",", $requestArray['Parameter']['shipment_products']);
		$shipment_description = $requestArray['Parameter']['shipment_description'];
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
							"product_srno" => $_product['wo_product_srno'],
							"product_track" => $_product['wo_shipment_tracking']
						);
					}
				}
			}	
		}
		
		include("engine/inc/addshipmentconfirmform.php");
		
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>