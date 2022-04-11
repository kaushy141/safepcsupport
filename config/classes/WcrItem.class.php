<?php
class WcrItem extends DB{
	public $id;	
	public $key_id	=	"wcr_id";
	public $table_name	=	"app_wc_record_items";
	public $status	=	"wcr_item_status";
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT `wcr_id`, `wc_id`, `wcr_item_id`, `wcr_item_qty`, `wcr_item_weight`, `wcr_item_status`, `wcr_item_collection_date` FROM `app_wc_record_items` WHERE `wcr_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function add($wc_id, $wcr_item_id, $wcr_item_qty, $wcr_item_weight, $wcr_item_status, $wcr_item_charge_amount, $wcr_item_charge_amount_paid, $wcr_item_charge_format, $wcr_item_description)
	{
		$sql= "INSERT INTO `app_wc_record_items`(`wc_id`, `wcr_item_id`, `wcr_item_qty`, `wcr_item_weight`, `wcr_item_status`, `wcr_item_collection_date`, `wcr_item_charge_amount`, `wcr_item_charge_amount_paid`, `wcr_item_charge_format`, `wcr_item_description`) VALUES ('$wc_id', '$wcr_item_id', '$wcr_item_qty', '$wcr_item_weight', '$wcr_item_status', NULL, '$wcr_item_charge_amount', '$wcr_item_charge_amount_paid', '$wcr_item_charge_format', '$wcr_item_description')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
	function removeAll($wc_id)
	{
		$sql= "DELETE FROM `app_wc_record_items` WHERE `wc_id` = '$wc_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	function removeItemFromCollection($wc_id, $wcr_item_id)
	{
		$sql= "DELETE FROM `app_wc_record_items` WHERE `wc_id` = '$wc_id' AND  `wcr_item_id` = '$wcr_item_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	function getAllItemArray($wc_id)
	{
		$sql= "SELECT GROUP_CONCAT(`wcr_item_id`) as items FROM `app_wc_record_items` WHERE `wc_id` = '$wc_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = $dbc->db_fetch_assoc($result);
		return explode(",", $data['items']);			
	}
	
	function getAllItemDetailsArray($wc_id)
	{
		$sql= "SELECT a.*, b.`wci_name`, b.`wci_ewc_code`, b.`wci_chemical_component`, b.`wci_concentration`, b.`wci_physical_form`, b.`wci_hazard_codes`, b.`wci_container_type`, c.`wci_type_id`, c.`wci_type_name` FROM `app_wc_record_items` as a INNER JOIN `app_wc_item_master` as b ON `wcr_item_id` =b.`wci_id` INNER JOIN `app_wci_type_master` as c ON b.`wci_type_id` =  c.`wci_type_id` WHERE a.`wc_id` = '$wc_id'";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		if($dbc->db_num_rows($result)>0)
		{
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['key'] = $row['wcr_item_id'];
				$record_array[] = $row;
			}
		}
		return $record_array;		
	}
	
	function isItemExist($wc_id, $wcr_item_id)
	{
		$sql="SELECT `wcr_id` FROM `app_wc_record_items` WHERE `wc_id` = '$wc_id' AND `wcr_item_id` = '$wcr_item_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)>0)
		{
			$data = $dbc->db_fetch_assoc($result);
			return $data['wcr_id'];			
		}
		else
			return 0;
	}
	
	function updateQuantity($wc_id, $wcr_item_id, $wcr_item_qty){
		$sql = "UPDATE `app_wc_record_items` SET `wcr_item_qty` = '$wcr_item_qty'  WHERE `wc_id` = '$wc_id' AND `wcr_item_id` = '$wcr_item_id'";
		//echo $sql;
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getComplaintItemList($wc_id)
	{
		$sql="SELECT a.`wc_id`, a.`wcr_item_id`, a.`wcr_item_qty`, a.`wcr_item_weight`, a.`wcr_item_charge_amount`, a.`wcr_item_charge_amount_paid`, a.`wcr_item_charge_format`, b.`wci_name`, b.`wci_ewc_code`, b.`wci_chemical_component`, b.`wci_concentration`, b.`wci_physical_form`, b.`wci_hazard_codes`, b.`wci_container_type`, c.`wci_type_id`, c.`wci_type_name`  FROM `app_wc_record_items` as a INNER JOIN `app_wc_item_master` as b ON `wcr_item_id` =b.`wci_id` INNER JOIN `app_wci_type_master` as c ON b.`wci_type_id` =  c.`wci_type_id` WHERE a.`wc_id`='$wc_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc())
			array_push($result_array, $row);
			return $result_array;
		}
		else
			return false;
	}
	
}
?>