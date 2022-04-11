<?php
class WeborderLabels extends DB{
	public $id;
	public $key_id	=	"label_id";
	public $table_name	=	"app_website_order_label";
	public $status	=	"label_status";
	private $defaultService = array();
		
	function __construct($id=0){		
		$this->id	=	$id;		
	}
	
	function getAssignedShipmentLabel($web_order_packing_user = 0){
		$user_id = getLoginId();
		$condition = $web_order_packing_user ? " AND a.`web_order_packing_user` = '$web_order_packing_user'" : "";
		/*$sql = "SELECT a.`web_order_id`, a.`web_order_number`, a.`web_order_total_amount`, a.`web_order_currency`, a.`web_order_packing_time`, a.`web_order_packing_user`, a.`web_order_invoice_image`, a.`web_order_invoice_date`, a.`web_order_created_date`, b.`store_name`, b.`store_icon`, b.`store_logo`, c.`user_id`, c.`user_fname`, c.`user_lname`, c.`user_image`, d.`label_id`, d.`label_downloads`, d.`label_path`, d.`label_uploaded_date`, d.`label_download_records`, e.`user_fname` as uploader_fname, e.`user_lname` as uploader_lname, e.`user_image` as uploader_image, GROUP_CONCAT(p.`wo_product_premium` SEPARATOR ', ') AS product_premium
		FROM  `app_website_order` AS a 
		INNER JOIN `app_store_master` AS b ON ( b.`store_id` = a.`web_order_website_id` ) 
		INNER JOIN `app_system_user` AS c ON ( c.`user_id` = a.`web_order_packing_user` ) 
		LEFT JOIN `app_website_order_label` AS d ON ( d.`label_order_id` = a.`web_order_id` AND d.`label_status` = 1) 
		LEFT JOIN `app_system_user` AS e ON ( e.`user_id` = d.`label_uploaded_by` ) 
		INNER JOIN `app_website_order_product` as p ON p.`wo_web_order_id` = a.`web_order_id`
		WHERE a.`web_order_status` != '3' AND a.`web_order_packing_user` != '0' AND a.`web_order_process_user` = '0' $condition GROUP BY a.`web_order_id`, d.`label_id` ORDER BY c.`user_fname`, a.`web_order_packing_user` = '$user_id', a.`web_order_created_date`, d.`label_uploaded_date` DESC";*/
		
		$sql = "SELECT a.`web_order_id`, a.`web_order_number`, a.`web_order_total_amount`, a.`web_order_currency`, a.`web_order_packing_time`, a.`web_order_packing_user`, a.`web_order_invoice_image`, a.`web_order_invoice_date`, a.`web_order_priority`, a.`web_order_created_date`, b.`store_name`, b.`store_icon`, b.`store_logo`, c.`user_id`, c.`user_fname`, c.`user_lname`, c.`user_image`, SUM(p.`wo_product_quantity`) AS total_quantity,  
GROUP_CONCAT(DISTINCT COALESCE(d.`label_id`, '0'), '^', COALESCE(d.`label_downloads`, '0'), '^', COALESCE(d.`label_path`, 'NULL'), '^',  COALESCE(d.`label_uploaded_date`, 'NULL'), '^', COALESCE(d.`label_download_records`, 'NULL'), '^', COALESCE(e.`user_fname`, 'NULL'),  '^',  COALESCE(e.`user_lname`, 'NULL'),  '^',  COALESCE(e.`user_image`, 'NULL') SEPARATOR '*^*') AS label_data, 
GROUP_CONCAT(p.`wo_product_premium` SEPARATOR ', ') AS product_premium 
FROM `app_website_order` AS a INNER JOIN `app_store_master` AS b ON ( b.`store_id` = a.`web_order_website_id` ) INNER JOIN `app_system_user` AS c ON ( c.`user_id` = a.`web_order_packing_user` ) LEFT JOIN `app_website_order_label` AS d ON ( d.`label_order_id` = a.`web_order_id` AND d.`label_status` = 1) LEFT JOIN `app_system_user` AS e ON ( e.`user_id` = d.`label_uploaded_by` ) INNER JOIN `app_website_order_product` as p ON p.`wo_web_order_id` = a.`web_order_id` WHERE   a.`web_order_status` != '3' AND a.`web_order_packing_user` != '0' AND a.`web_order_process_user` = '0' GROUP BY a.`web_order_id` ORDER BY c.`user_fname`, a.`web_order_packing_user` = '$user_id', a.`web_order_priority` DESC, a.`web_order_created_date`, d.`label_uploaded_date` DESC";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	
	function updateDownloadCount(){
		$label_download_records = $_SESSION['user_name']." at ".dateView(date('Y-m-d H:i:s'), 'FULL')."|";
		$sql = "UPDATE `app_website_order_label` SET `label_downloads` = (`label_downloads` + 1), `label_download_records` = CONCAT(COALESCE(`label_download_records`, ''), '$label_download_records') WHERE `label_id` = '".$this->id."'";
		//echo $sql;
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getWebOrderShipmentLabel($label_order_id){
		$sql = "SELECT a.*, b.`user_fname`, b.`user_lname`, b.`user_image`, d.`shipment_type_name`, d.`shipment_type_image`, d.`shipment_type_color` FROM `app_website_order_label` AS a 
		INNER JOIN `app_system_user` AS b ON ( b.`user_id` = a.`label_uploaded_by` ) 
		INNER JOIN `app_website_order` AS c ON a.`label_order_id` = c.`web_order_id`
		LEFT JOIN `app_shipment_types` AS d ON a.`label_shipment_type` = d.`shipment_type_id` 
		WHERE a.`label_order_id` = '$label_order_id' AND a.`label_status` = '1' ORDER BY a.`label_uploaded_date`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
}
?>