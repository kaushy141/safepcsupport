<?php
class Notification extends DB{
	public $id			=	0;
	public $key_id		=	"notification_id";
	public $table_name	=	"android_notification";
	public $status		=	"notification_status";	
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	
	function add($notification_order_number, $notification_site_name, $notification_order_status, $notification_order_created_date, $notification_order_customer_name, $notification_order_customer_address, $notification_status)
	{
		global $app;
		$sql="INSERT INTO `android_notification`(`notification_order_number`, `notification_site_name`, `notification_order_status`, `notification_order_created_date`, `notification_order_customer_name`, `notification_order_customer_address`, `notification_status`) VALUES ('$notification_order_number', '$notification_site_name', '$notification_order_status', '$notification_order_created_date', '$notification_order_customer_name', '$notification_order_customer_address', '$notification_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))
		{
			$row = $dbc->db_fetch_assoc(true);
			return $row;
		}
		else
			return false;
	}
	
	function getDetails()
	{
		global $app;
		$sql="SELECT `notification_id`, `notification_order_number`, `notification_site_name`, `notification_order_status`, `notification_order_created_date`, `notification_order_customer_name`, `notification_order_customer_address`, `notification_status` FROM `android_notification` WHERE `".$this->key_id."` = '".$this->id."'" ;	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))
		{
			$row = $dbc->db_fetch_assoc(true);
			return $row;
		}
		else
			return false;
	}
		
	function getList($limit=10, $start=0)
	{
		global $app;
		$sql="SELECT `notification_id`, `notification_order_number`, `notification_site_name`, `notification_order_status`, `notification_order_created_date`, `notification_order_customer_name`, `notification_order_customer_address`, `notification_status` FROM `android_notification` WHERE `notification_id` >= '$start' LIMIT $limit" ;	
		$dbc 	= 	new DB();
		$result_array = array();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc(true))
			{
				$result_array[] = $row;
			}
			return $result_array;
		}
		else
			return false;
	}	
		
}
?>