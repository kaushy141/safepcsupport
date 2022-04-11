<?php
class ShipmentShipper extends DB{
	public $id;
	public $key_id	=	"shipment_shipper_id";
	public $table_name	=	"app_shipment_shipper";
	public $status	=	"shipment_shipper_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
}
?>