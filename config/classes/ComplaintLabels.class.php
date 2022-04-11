<?php
class ComplaintLabels extends DB{
	public $id;
	public $key_id	=	"label_id";
	public $table_name	=	"app_complaint_record_label";
	public $status	=	"label_status";
	private $defaultService = array();
		
	function __construct($id=0){		
		$this->id	=	$id;		
	}
	//SELECT `complaint_id`, `complaint_origin_type_id`, `complaint_store_id`, `complaint_customer_id`, `customer_address_id`, `complaint_ticket_number`, `complaint_product_serial`, `complaint_product_sku`, `complaint_product_model`, `complaint_is_backup`, `complaint_product_password`, `complaint_is_disk_provided`, `complaint_product_is_under_waranty`, `complaint_product_operating_system`, `complaint_product_antivirus`, `complaint_description`, `complaint_problem_details`, `complaint_product_hardware_not_working`, `complaint_due_date`, `complaint_priority`, `complaint_sales_record_number`, `complaint_order_number`, `complaint_estimated_cost`, `complaint_product_condition_at_receiving`, `complaint_technician_id`, `complaint_tax_id`, `complaint_created_date`, `complaint_created_by`, `complaint_picking_user`, `complaint_picking_time`, `complaint_packing_user`, `complaint_packing_time`, `complaint_process_user`, `complaint_process_time`, `complaint_cancel_user`, `complaint_cancel_time`, `complaint_invoice_image`, `complaint_invoice_date`, `complaint_is_outsourced`, `complaint_refund_status`, `complaint_status`, `complaint_active_status` FROM `app_complaint_record` WHERE 1
	function getAssignedShipmentLabel(){
		$user_id = getLoginId();
		$sql = "SELECT a.`complaint_id`, a.`complaint_ticket_number`, a.`complaint_estimated_cost`, a.`complaint_due_date`, a.`complaint_created_date`, a.`complaint_invoice_image`, a.`complaint_invoice_date`, b.`store_name`, b.`store_icon`, b.`store_logo`, c.`user_id`, c.`user_fname`, c.`user_lname`, c.`user_image`, d.`label_id`, d.`label_downloads`, d.`label_path`, d.`label_uploaded_date`, d.`label_download_records`, e.`user_fname` as uploader_fname, e.`user_lname` as uploader_lname, e.`user_image` as uploader_image
		FROM  `app_complaint_record` AS a 
		INNER JOIN `app_store_master` AS b ON ( b.`store_id` = a.`complaint_store_id` ) 
		INNER JOIN `app_system_user` AS c ON ( c.`user_id` = a.`complaint_packing_user` ) 
		LEFT JOIN `app_complaint_record_label` AS d ON ( d.`label_complaint_id` = a.`complaint_id` AND d.`label_status` = '1') 
		LEFT JOIN `app_system_user` AS e ON ( e.`user_id` = d.`label_uploaded_by` ) 		
		WHERE a.`complaint_packing_user` != '0' AND a.`complaint_process_user` = '0' AND a.`complaint_status` NOT IN (2,8) GROUP BY a.`complaint_id`, d.`label_id` ORDER BY a.`complaint_packing_user` = '$user_id', a.`complaint_created_date`, d.`label_uploaded_date` DESC";
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
		$sql = "UPDATE `app_complaint_record_label` SET `label_downloads` = (`label_downloads` + 1), `label_download_records` = CONCAT(COALESCE(`label_download_records`, ''), '$label_download_records') WHERE `label_id` = '".$this->id."'";
		//echo $sql;
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getComplaintShipmentLabel($label_complaint_id){
		$sql = "SELECT a.*, b.`user_fname`, b.`user_lname`, b.`user_image`, d.`shipment_type_image`, d.`shipment_type_color` FROM `app_complaint_record_label` AS a 
		INNER JOIN `app_system_user` AS b ON ( b.`user_id` = a.`label_uploaded_by` ) 
		INNER JOIN `app_complaint_record` AS c ON a.`label_complaint_id` = c.`complaint_id`
		LEFT JOIN `app_shipment_types` AS d ON a.`label_shipment_type` = d.`shipment_type_id` 
		WHERE a.`label_complaint_id` = '$label_complaint_id' AND a.`label_status` = '1' ORDER BY a.`label_uploaded_date`";
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