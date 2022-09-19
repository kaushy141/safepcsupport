<?php
class ProductAvailbility extends DB{
	public $id;
	public $key_id	=	"pro_avail_id";
	public $table_name	=	"app_website_order_product_availability";
	public $status	=	"pro_avail_status";	
	
	public static $AVAILABLE = 'Available';
	public static $NOT_AVAILABLE = 'Not available';
	public static $WAITING = 'Waiting to confirm';
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	function getAvailbiltyChecked($pro_avail_section_code, $pro_avail_order_id, $pro_avail_product_id = 0){
		global $app;
		$condition = $pro_avail_product_id ? " AND a.`pro_avail_product_id` = '$pro_avail_product_id'" : "";
		$sql = "SELECT a.*, b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, c.`supplier_name`, c.`supplier_email`, c.`supplier_contact`, c.`supplier_image` FROM `app_website_order_product_availability` AS a 
		INNER JOIN `app_system_user` AS b ON a.`pro_avail_checked_user_id` = b.`user_id` 
		INNER JOIN `app_supplier_record` AS c ON a.`pro_avail_supplier_id` = c.`supplier_id` 
		WHERE a.`pro_avail_section_code` = '$pro_avail_section_code' AND a.`pro_avail_order_id` = '$pro_avail_order_id' $condition AND a.`pro_avail_status` = '1' ORDER BY a.`pro_avail_checked_time` DESC";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['user_image'] = getResizeImage($row["user_image"],50);
				$row['pro_avail_checked_time_full'] = dateView($row["pro_avail_checked_time"],'FULL');
				$row['pro_avail_created_date_now'] = dateView($row["pro_avail_checked_time"],'NOW');
				$alertCalss = "info";
				if($row['pro_avail_stock_status'] == self::$AVAILABLE)
					$alertCalss = "success";
				elseif($row['pro_avail_stock_status'] == self::$NOT_AVAILABLE)
					$alertCalss = "danger";
				elseif($row['pro_avail_stock_status'] == self::$WAITING)
					$alertCalss = "warning";
				$row['pro_avail_class'] = $alertCalss;
				$row['pro_avail_remark_beautify'] = applyAnchor($row['pro_avail_remark']);
				$record_array[] = $row;
			}
		}
		return $record_array;
	}
	
	function getLastAvailbility($pro_avail_section_code, $pro_avail_order_id, $pro_avail_product_id = 0){
		$records = $this->getAvailbiltyChecked($pro_avail_section_code, $pro_avail_order_id, $pro_avail_product_id);
		if(count($records))
			return $records[0];
		else
			return null;
	}
	
}

?>