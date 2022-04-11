<?php
class BatchProduct extends DB{
	public $id;
	public $key_id	=	"product_id";
	public $table_name	=	"app_batch_product";
	public $status	=	"product_status";
	public static $exportData = array(
		'product_code' => 'Product Code', 
		'product_order_number' => 'Order Number', 
		'product_reg_id' => 'Reg.Id', 
		'product_type' => 'Type', 
		'product_name' => 'Name', 
		'product_serial_number' => 'Sr.No.', 
		'product_sku' => 'Sku', 
		'product_model' => 'Model', 
		'product_condition' => 'Condition', 
		'product_processor' => 'Processor', 
		'product_processor_speed' => 'Processor Speed', 
		'product_screen_size' => 'Screen Size', 
		'product_ram' => 'RAM', 
		'product_ssd' => 'SSD', 
		'product_hdd' => 'HDD', 
		'product_fusion_drive' => 'Fusion Drive', 
		'product_release' => 'Release Year', 
		'product_reason' => 'Reason', 
		'product_battery_cycle' => 'Battery Cycle', 
		'product_operating_system' => 'OS', 
		'product_grade' => 'Grade', 
		'product_batch_type' => 'Batch Type', 
		'product_batch_code' => 'Batch Code', 
		'product_part_number' => 'Part Number', 
		'product_created_date' => 'Added Date', 
		'product_in_stock' => 'In Stock', 
		'product_under_technician' => 'Under Technician', 
		'product_age_date' => 'Age Date', 
		'product_price' => 'Cost', 
		'product_store_location' => 'Store Loation', 
		'product_verified' => 'Verified', 
		'product_is_on_way'=> 'On the way',
		'product_status'=> 'Status',
		'comments'=> 'Comments'
	);
		
	function __construct($id=0){		
		$this->id	=	$id;
	}	
	
	static function getBatchProductByCode($product_code){
		$sql = "SELECT * FROM `app_batch_product` WHERE `product_code` = '$product_code'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows() == 1){
			return $dbc->db_fetch_assoc(true);
		}
		else
			return false;
	}
	
	function getPrintProductCode($condition){
		$sql = "SELECT `product_processor`, `product_ram`, `product_ssd`, `product_hdd`, `product_screen_size`, `product_code`, `product_type`, `product_model`, `product_serial_number`, `product_release` FROM `app_batch_product` $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record = array();
		if($dbc->db_num_rows() > 0){
			while($row = $dbc->db_fetch_assoc(true))
			$record[] = $row;
			return $record;
		}
		else
			return false;
	}
	
	function isSerialNumberExist($product_serial_number){
		$sql = "SELECT `product_id` FROM `app_batch_product` WHERE TRIM(UPPER(`product_serial_number`)) = TRIM(UPPER('$product_serial_number')) AND `product_id` != '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows();
	}
	
	function getBatchProductItemsByIds($ids){
		$products = "'".implode("', '", $ids)."'";
		$sql =  "SELECT `product_id`, `product_code`, `product_order_number`, `product_reg_id`, `product_type`, `product_name`, `product_serial_number`, `product_sku`, `product_model`, `product_condition`, `product_processor`, `product_processor_speed`, `product_screen_size`, `product_ram`, `product_ssd`, `product_hdd`, `product_fusion_drive`, `product_release`, `product_reason`, `product_battery_cycle`, `product_operating_system`, `product_grade`, `product_batch_type`, `product_batch_code`, `product_in_stock`, `product_under_technician` FROM `app_batch_product` WHERE CONCAT('B|',`product_id`) IN ($products) ORDER BY `product_code`";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record = array();
		if($dbc->db_num_rows() > 0){
			while($row = $dbc->db_fetch_assoc(true))
			$record[] = $row;			
		}
		return $record;
	}
	
	function getExportProductSql($condition, $column=null){
		if($column == null){
			$aSelectedColumn = '`product_code`, `product_order_number`, `product_reg_id`, `product_type`, `product_name`, `product_serial_number`, `product_sku`, `product_model`, `product_condition`, `product_processor`, `product_processor_speed`, `product_screen_size`, `product_ram`, `product_ssd`, `product_hdd`, `product_fusion_drive`, `product_release`, `product_reason`, `product_battery_cycle`, `product_operating_system`, `product_grade`, `product_batch_type`, `product_batch_code`, `product_in_stock`, `product_under_technician`';
		}
		else{
			$skipColumn = array('comments');
			$aSelectedColumn = '`'. implode('`, `', array_diff($column, $skipColumn)). '`';
		}
		return "SELECT `product_id`, $aSelectedColumn FROM `app_batch_product` $condition";
	}
	
	function getTechnicianFilteration($filter_name = null){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`product_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`product_under_technician_id` = b.`user_id` WHERE a.`product_under_technician` = 1 GROUP BY a.`product_under_technician_id` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	function getCreatorFilteration(){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`product_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`product_created_by` = b.`user_id` WHERE 1 GROUP BY a.`product_created_by` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	
	function getBatchProductCode()
	{
		$time = time();
		$year = date('Y', $time);
		$month = date('n', $time);
		$sql = "SELECT `product_code` FROM `".$this->table_name."` WHERE YEAR(`product_created_date`) = '$year' AND MONTH(`product_created_date`) = '$month' ORDER BY `product_id` DESC LIMIT 1 ";	
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()){
			$data = $dbc->db_fetch_assoc();
			$numcount = intval(substr($data['product_code'],5,5))+1;
		}
		else
		{
			$numcount = 1;
		}		
		return strtoupper(date("ym", $time)."B".sprintf("%05d",$numcount));
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
			//SELECT `product_id`, `product_order_number`, `product_reg_id`, `product_type`, `product_name`, `product_serial_number`, `product_model`, `product_condition`, `product_processor`, `product_processor_speed`, `product_screen_size`, `product_ram`, `product_ssd`, `product_hdd`, `product_release`, `product_reason`, `product_battery_cycle`, `product_operating_system`, `product_batch_type`, `product_created_date`, `product_in_stock`, `product_status` FROM `app_batch_product` WHERE 1
					"TABLES"=>array(
								"`app_batch_product`" => array(
															"column"=> array("`product_id`", "`product_code`", "`product_type`", "`product_name`", "`product_serial_number`", "`product_sku`", "`product_condition`", "`product_release`", "`product_in_stock`", "`product_batch_code`", "`product_part_number`", "`product_verified`", "`product_store_location`", "`product_is_on_way`"),
															"reference" => "a",
															"join" => NULL
															)
								),
					"ORDER"	=>array("`product_type`", "`product_code`", "`product_name`", "`product_serial_number`",  "`product_condition`", "`product_in_stock`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`product_id`");
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					foreach($values as $_val){
						$_val_arr = explode("|||", $_val);
						$_val = $_val_arr[0];
						$operator = isset($_val_arr[1]) ? $_val_arr[1] : "=";
						$filedCondArray[] = array($filed, $operator, sanitizePostData($_val));
					}
				}
				else{
					$values_arr = explode("|||", $values);
					$values = $values_arr[0];
					$operator = isset($values_arr[1]) ? $values_arr[1] : "=";
					$filedCondArray[] = array($filed, $operator, sanitizePostData($values));
				}
				$conditionArray[] = $filedCondArray;	
			}
		}
		$this->condition = $conditionArray;
		/*$this->condition = array(
									//array("a.`user_id`","=","1"),
									//array(
										//array("user_fname","=","Kaushal"),
										//array("user_type_name","=","technician")
										//),
									//array("user_address","like","1")
									
								);	*/
		$this->condition = $conditionArray;
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
		
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								($row['product_verified'] == "" ? ("<i class=\"fa fa-check text-muted\"></i> ") : ("<i title='".(dateView($row['product_verified'], 'FULL'))."' class=\"fa fa-check text-success\"></i> ")).$row["product_type"]."<br/><span class=\"badge badge-info\">{$row['product_batch_code']}</span>",
								$row["product_code"]."<br/><img class=\"img-avatar\" src=\"".$app->basePath('img/system/flag/flag_'.strtolower($row['product_store_location']).'.png')."\" height=\"16px\"> <span>{$row['product_store_location']}</span>",
								$row["product_name"],
								$row["product_serial_number"],
								$row["product_condition"],
								($row["product_in_stock"] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>').($row["product_is_on_way"] ? '<i class="fa fa-fw text-warning fa-truck"></i>' : '<i class="fa fa-fw text-success fa-home"></i>'),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-ellipsis-v fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">	
    <a class=\"dropdown-item redirect\" href=\"updatebatchproduct/".$row['product_id']."\"><i class=\"fa fa-share-square-o fa-fw\"></i> Update</a>
	<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[product_id]|B', '$row[product_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Comment Log</a>
	<a class=\"dropdown-item\" href=\"javascript:verifyBatchProduct(".$row['product_id'].", ".($row['product_verified'] == "" ? 1:0).")\"><i class=\"fa fa-check fa-fw text-success\"></i> ".($row['product_verified'] == "" ? "Mark Verify":"Unverify")."</a>
	<a class=\"dropdown-item redirect\" href=\"productsalehistory/".$row['product_id']."\"><i class=\"fa fa-shopping-cart fa-fw\"></i> Sale History</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::BATCHPROCODE($row['product_id'])."')\"><i class=\"fa fa-barcode fa-fw\"></i> Print Barcode</a>
	<a class=\"dropdown-item addtolot\" data-lot=\"B|{$row['product_id']}\" href=\"#\"><i class=\"fa fa-plus fa-fw text-success\"></i> Add to lot</a>
	<a class=\"dropdown-item\" href=\"javascript:deleteBatchProduct(".$row['product_id'].")\"><i class=\"fa fa-trash fa-fw text-danger\"></i> Delete</a>
  </div>
</div>",
							"updatebatchproduct/".$row['product_id']
			);
		}
		return json_encode($output);		
	}
	
	
}


class BatchProductMedia extends DB{
	public $id;
	public $key_id		=	"image_id";
	public $table_name	=	"app_batch_product_images";
	function __construct($image_id=0){
				
		$this->id	=	$image_id;
	}
	
	function getImageList($batch_product_id){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `image_product_id` = '$batch_product_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$image_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$image_array[] = $row;
			}
			return $image_array;
		}
		return NULL;
	}
	
}
?>