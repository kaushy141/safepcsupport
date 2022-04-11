<?php
class Product extends DB{
	public $id;
	public $key_id	=	"product_id";
	public $table_name	=	"app_sales_products";
	public $status	=	"product_status";	
	function __construct($product_id=0){
				
		$this->id	=	$product_id;
	}
	
	function isproductexist($product_name,$product_id=NULL)
	{
		$condition="";
		$product_name = checkData($product_name);
		if($product_id!=NULL)
		$condition=" AND `product_id`!='$product_id'";
		$sql="SELECT `product_id` FROM `app_sales_products` WHERE LOWER(`product_name`) = LOWER('".$product_name."') $condition";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return ($dbc->db_num_rows()>0)?true:false;			
	}
	
	function isHardwareProductExist($product_name, $product_hardware_id, $product_id=NULL)
	{
		global $conn;
		$condition="";
		//$product_name = sanitizePostData($product_name);
		$product_name = strtolower(checkData($product_name));
		if($product_id!=NULL)
		$condition=" AND `product_id`!='$product_id'";
		$sql="SELECT `product_id` FROM `app_sales_products` WHERE LOWER(`product_name`) = LOWER('".$product_name."') AND `product_hardware_id` = '".$product_hardware_id."' $condition";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$record = $dbc->db_fetch_assoc(true);
			return $record['product_id'];
		}
		else
			return 0;			
	}
	
	function add($product_name, $product_hardware_id, $product_quantity, $product_price, $product_inc_vat, $product_status=1)
	{
		$sql="INSERT INTO `app_sales_products`(`product_name`, `product_hardware_id`, `product_quantity`, `product_price`, `product_inc_vat`, `product_created_date`, `product_status`) VALUES ('$product_name', '$product_hardware_id', '$product_quantity', '$product_price', '$product_inc_vat', NOW(), '$product_status')";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();	
		return $this->id;
	}
	
	function getReportSqlSlip($limit=10)
	{
		$sql=array();
		for($i=1; $i<=$limit; $i++)
		$sql[] = "SELECT $i as num, a.`product_id`, a.`product_id` as product_number, SUBSTR(a.`product_name`, 1,90) as product_name, a.`product_price`, b.`hardware_name` as product_type_name FROM `app_sales_products` as a LEFT JOIN `app_hardware_type` as b ON a.`product_hardware_id` = b.`hardware_id` WHERE a.`product_id` = '".$this->id."'";
		return implode(" UNION ", $sql);
	}
		
	function getDetails()
	{
		$sql="SELECT a.*, b.`hardware_code`, b.`hardware_name` FROM `app_sales_products` as a LEFT JOIN `app_hardware_type` as b ON a.`product_hardware_id` = b.`hardware_id` WHERE a.`product_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function load()
	{
		$sql="SELECT `product_id`, `product_name`, `product_hardware_id`, `product_quantity`, `product_price`, `product_inc_vat`, `product_created_date`, `product_status` FROM `app_sales_products` WHERE `product_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function getRecords()
	{
		$sql="SELECT a.`product_id`, a.`product_name`, a.`product_hardware_id`, a.`product_price`, a.`product_inc_vat`, a.`product_created_date`, a.`product_status`, b.`hardware_code`, b.`hardware_name` FROM `app_sales_products` as a LEFT JOIN `app_hardware_type` as b ON a.`product_hardware_id` = b.`hardware_id` WHERE 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<tr>";
				$html.="<td>".$row['product_name']."</td>";
				$html.="<td>".$row['hardware_name']."</td>";
				$html.="<td>".$row['product_price']."</td>";
				$html.="<td>".$row['product_created_date']."</td>";
				$html.="<td>".statusView($row['product_id'], $row['product_status'])."</td>";
			$html.="</tr>";		
		}
		return $html;	
	}
	
	public function getDropdownList($fieldName, $keyword, $extra, $condition)
	{
		if($condition != "")
		$condition = "AND a.$condition";
		
		$keyword = str_replace(" ", "%", trim($keyword));
		
		$sql="SELECT a.`product_name`, CONCAT(a.`product_name`, ' - &pound;', a.`product_price`) as itemlabel FROM `app_sales_products` as a LEFT JOIN `app_hardware_type` as b ON a.`product_hardware_id` = b.`hardware_id` WHERE LOWER(a.$fieldName) LIKE '%$keyword%' $condition 
		
		UNION 
		
		SELECT `wo_product_name` as product_name, `wo_product_name` as itemlabel FROM `app_website_order_product` WHERE `wo_product_name` LIKE '$keyword%' OR  `wo_product_name` LIKE '%$keyword%' OR `wo_product_name` LIKE '$keyword'
		
		UNION
		
		SELECT `product_name`, `product_name` as itemlabel FROM `app_batch_product` WHERE `product_name` LIKE '$keyword%' OR `product_name` LIKE '%$keyword%' OR `product_name` LIKE '$keyword'
		
		ORDER BY product_name LIKE '$keyword%', product_name LIKE '%$keyword%', product_name LIKE '$keyword' 
		
		LIMIT 20";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($result)
		if($dbc->db_num_rows()>0)
		{	
			while($row = $dbc->db_fetch_assoc(true))	
				$data[]	= array("label"=>$row['itemlabel'], "data-id"=>$row['itemlabel'], "value"=>$row['product_name'], "extra"=>$extra?$row:NULL);	
		}
		return $data;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_sales_products`" => array(
															"column"=> array("`product_id`", "`product_name`", "`product_quantity`", "`product_price`", "`product_inc_vat`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_hardware_type`" => array(
															"column"=> array("`hardware_name`"),
															"reference" => "b",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_sales_products`", "on"=>array("`hardware_id`" => "`product_hardware_id`"))
															)
								),
					"ORDER"	=>array("product_id","`product_name`", "`hardware_name`", "`product_price`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
										
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		//$this->groupby = array("a.`product_id`");
			
		
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
								$row["product_id"],
								viewText($row["product_name"]),
								viewText($row["product_quantity"]),
								viewText($row["hardware_name"]),
								"&pound; ".$row["product_price"],
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    ".(isAdmin()?	
    ("<a class=\"dropdown-item redirect\" href=\"updateproduct/".$row['product_id']."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::PROSLIP($row['product_id'])."');\"><i class=\"fa fa-bookmark-o fa-fw\"></i> Multi Slip</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::PROSLIPSINGLE($row['product_id'])."');\"><i class=\"fa fa-bookmark-o fa-fw\"></i> Single Slip</a>"):"")." </div></div>"
			);
		}
		return json_encode($output);		
	}
	
	static function getLotProducts($lot_products){
		global $app;
		$products = "'".implode("', '", $lot_products)."'";
		$sql = "SELECT CONCAT('B|',`product_id`) AS keyname, 'BatchProduct' as origin, `product_in_stock` as stock, CONCAT('updatebatchproduct/',`product_id`) as link, `product_id` as id, `product_name` as name, `product_serial_number` as srno, `product_sku` as sku, `product_age_date` as age, `product_store_location` as location, `product_batch_code` as batch_code FROM `app_batch_product` WHERE CONCAT('B|',`product_id`) IN ($products) 
		UNION 
		SELECT CONCAT('P|',`wc_process_asset_code`) AS keyname, 'CollectionProcess' as origin, `wc_process_item_stock` as stock, CONCAT('updatewcprocessitem/', `wc_process_asset_code`) as link, `wc_process_id` as id, `wc_process_item_name` as name, `wc_process_item_sr_no` as srno, `wc_process_item_sku` as sku, `wc_process_age_date` as age, `wc_process_item_location` AS location, (SELECT `values_data` FROM `app_wc_item_attributes_values` WHERE `values_wc_process_id` = `wc_process_id` AND `values_attribute_id` = '57') AS batch_code FROM app_wc_process WHERE CONCAT('P|',`wc_process_asset_code`) IN ($products) ";	
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['id'] = md5($row['keyname']);
				$row['location'] = "<img class=\"img-avatar\" src=\"".$app->basePath('img/system/flag/flag_'.strtolower($row['location']).'.png')."\" height=\"16px\"> <span>{$row['location']}</span>";
				$row['url'] = $app->basePath($row['link']);
				$row['age'] = $row['age'] != "" ? ("<span class='d-none'>".strtotime($row['age'])."</span>".dateView($row['age'], 'NOW')."<br/>".dateView($row['age'], 'DATE')):'N/A';
				$product_array[] = $row;
			}
			return $product_array;
		}
		return NULL;
	}
	
	public function getProductUnderTechnician()
	{		
		global $app;
		$sql = "SELECT 'BatchProduct' as origin, `product_in_stock` as stock, CONCAT('updatebatchproduct/',`product_id`) as link, `product_id` as id, `product_code` as code, CONCAT('B|',`product_id`) as lot_code, `product_name` as name, `product_serial_number` as srno, `product_sku` as sku, `product_age_date` as age, `product_store_location` as location, `product_batch_code` as batch_code, `user_id`, `user_fname`, `user_lname`, `user_image` FROM `app_batch_product` INNER JOIN `app_system_user` ON `product_under_technician_id` = `user_id` WHERE `product_under_technician`  = '1'
		UNION 
		SELECT  'CollectionProcess' as origin, `wc_process_item_stock` as stock, CONCAT('updatewcprocessitem/', `wc_process_asset_code`) as link, `wc_process_id` as id, `wc_process_asset_code` as code, CONCAT('P|',`wc_process_asset_code`) as lot_code, `wc_process_item_name` as name, `wc_process_item_sr_no` as srno, `wc_process_item_sku` as sku, `wc_process_age_date` as age, `wc_process_item_location` AS location, (SELECT `values_data` FROM `app_wc_item_attributes_values` WHERE `values_wc_process_id` = `wc_process_id` AND `values_attribute_id` = '57') AS batch_code, `user_id`, `user_fname`, `user_lname`, `user_image` FROM app_wc_process INNER JOIN `app_system_user` ON `wc_process_under_technician` = `user_id` WHERE `wc_process_under_technician` > 0";
		
		$product_array = array();
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				if($row['origin'] == 'CollectionProcess')
					$row['label'] = DOC::CASSETCODE($row["id"], $row["code"]);
				elseif($row['origin'] == 'BatchProduct')
					$row['label'] = DOC::BATCHPROCODE($row['id']);
				$row['location'] = "<img class=\"img-avatar\" src=\"".$app->basePath('img/system/flag/flag_'.strtolower($row['location']).'.png')."\" height=\"16px\"> <span>{$row['location']}</span>";
				$row['url'] = $app->basePath($row['link']);
				$row['age'] = $row['age'] != "" ? ("<span class='d-none'>".strtotime($row['age'])."</span>".dateView($row['age'], 'NOW')."<br/>".dateView($row['age'], 'DATE')):'N/A';
				$product_array[] = $row;
			}
		}
		return $product_array;
	}
}

?>