<?php
	Modal::load(array('ShipmentType'));
	$ShipmentType = new ShipmentType();
	echo json_encode(array("200",  "success|Shipment origin loaded", $ShipmentType->getOptions()));

?>