<?php
class SalesLabels extends DB{
	public $id;
	public $key_id	=	"label_id";
	public $table_name	=	"app_sales_invoice_label";
	public $status	=	"label_status";
	private $defaultService = array();
		
	function __construct($id=0){		
		$this->id	=	$id;		
	}
	
	function getAssignedShipmentLabel(){
		$user_id = getLoginId();
		$sql = "SELECT a.`sales_invoice_id`, a.`sales_invoice_number`, a.`sales_invoice_packing_time`, a.`sales_invoice_packing_user`, a.`sales_invoice_created_date`, b.`store_name`, b.`store_icon`, b.`store_logo`, c.`user_id`, c.`user_fname`, c.`user_lname`, c.`user_image`, d.`label_id`, d.`label_downloads`, d.`label_path`, d.`label_uploaded_date`, d.`label_download_records`, e.`user_fname` as uploader_fname, e.`user_lname` as uploader_lname, e.`user_image` as uploader_image 
		FROM  `app_sales_invoice` AS a 
		INNER JOIN `app_store_master` AS b ON ( b.`store_id` = a.`sales_invoice_store_id` ) 
		INNER JOIN `app_system_user` AS c ON ( c.`user_id` = a.`sales_invoice_packing_user` ) 
		LEFT JOIN `app_sales_invoice_label` AS d ON ( d.`label_order_id` = a.`sales_invoice_id` AND d.`label_status` = '1') 
		LEFT JOIN `app_system_user` AS e ON ( e.`user_id` = d.`label_uploaded_by` ) 		
		WHERE a.`sales_invoice_is_cancelled` = '0' AND a.`sales_invoice_status` != '3' AND a.`sales_invoice_packing_user` != '0' AND a.`sales_invoice_process_user` = '0'  GROUP BY a.`sales_invoice_id`, d.`label_id` ORDER BY a.`sales_invoice_packing_user` = '$user_id', a.`sales_invoice_created_date`, d.`label_uploaded_date` DESC";
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
		$sql = "UPDATE `app_sales_invoice_label` SET `label_downloads` = (`label_downloads` + 1), `label_download_records` = CONCAT(COALESCE(`label_download_records`, ''), '$label_download_records') WHERE `label_id` = '".$this->id."'";
		//echo $sql;
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getWebOrderShipmentLabel($label_order_id){
		$sql = "SELECT a.*, b.`user_fname`, b.`user_lname`, b.`user_image`, d.`shipment_type_name`, d.`shipment_type_image`, d.`shipment_type_color` FROM `app_sales_invoice_label` AS a 
		INNER JOIN `app_system_user` AS b ON ( b.`user_id` = a.`label_uploaded_by` ) 
		INNER JOIN `app_sales_invoice` AS c ON a.`label_order_id` = c.`sales_invoice_id`
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