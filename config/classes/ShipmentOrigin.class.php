<?php
class ShipmentOrigin extends DB{
	public $id;
	public $key_id	=	"shipment_origin_id";
	public $table_name	=	"app_shipment_origin";
	public $status	=	"shipment_origin_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
}
?>