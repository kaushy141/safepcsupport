<?php
class ShipmentRequest extends DB{
	public $id;
	public $key_id	=	"request_id";
	public $table_name	=	"app_shipment_request";
	public $status	=	"request_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function loadByMd5($id){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE md5(`".$this->key_id."`) = '$id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){	
			$record = $dbc->db_fetch_assoc(true);
			$this->id	=	$record['request_id'];
			return $record;
		}
		else
			return false;
	}
	function loadByShipmentId($request_shipment_id){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `request_shipment_id` = '$request_shipment_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){	
			$record = $dbc->db_fetch_assoc(true);
			$this->id	=	$record['request_id'];
			return $record;
		}
		else
			return false;
	}
	
}
?>