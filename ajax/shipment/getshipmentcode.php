<?php
	$shipment_type_id = '';
	$data = sanitizePostData($_POST);
    extract($data);
	Modal::load(array('ShipmentType'));
	$shipmentType = new ShipmentType($shipment_type_id);
	$shipmentTypeData = $shipmentType->getDetails();
	if($shipmentTypeData)	
	{
		echo json_encode(array("200",  "success|Shipment Service loaded", $shipmentType->getShipmetCodeOptions($shipmentTypeData['shipment_type_code'])));
	}
	else
		echo json_encode(array("300",  "warning|Invalid Shipment type Code"));

?>