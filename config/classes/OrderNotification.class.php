<?php
class OrderNotification extends DB{
	public $id;		
	public $key_id	=	"order_notif_id";
	public $table_name	=	"app_order_notification_message";
	public $status	=	"order_notif_status";
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	function add($order_notif_title, $order_notif_body, $order_notif_url, $order_notif_icon, $order_notif_status)
	{
		$sql="INSERT INTO `app_order_notification_message`(`order_notif_title`, `order_notif_body`, `order_notif_url`, `order_notif_icon`, `order_notif_id_created_date`, `order_notif_status`) VALUES ('$order_notif_title', '$order_notif_body', '$order_notif_url', '$order_notif_icon', NOW(), '$order_notif_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function getListView($offset=0, $searchkeyword = "")
	{
		global $app;
		$condition = "";
		if($searchkeyword!="")
		{
			$searchkeyword = trim(strtolower($searchkeyword));
			$condition = " AND (LOWER(`order_notif_title`) LIKE '%$searchkeyword%' OR  LOWER(`order_notif_body`) LIKE '%$searchkeyword%') ";
		}
		
		$sql="SELECT `order_notif_id`, `order_notif_title`, `order_notif_body`, `order_notif_url`, `order_notif_icon`, `order_notif_id_created_date`, `order_notif_status` FROM `app_order_notification_message` WHERE 1 $condition ORDER BY `order_notif_id` DESC Limit 10 OFFSET $offset";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){	
			$row['order_notif_id_created_date']	= dateView($row['order_notif_id_created_date'], "FULL");		
			$row['order_notif_color_status']	= $row['order_notif_status']?"#4CAF50":"#F44336";
			$record_array[] = $row;		
		}
		return $record_array;	
	}
	
}

?>