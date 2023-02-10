<?php
class BuybackOrder extends DB{
	public $id;
	public $key_id	=	"id";
	public $table_name	=	"app_buyback_order";
	function __construct($id=0){
				
		$this->id	=	$id;
	}	
	
	function load(){
		$sql = "SELECT a.*, b.*, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, d.`store_name`, d.`store_icon`,d.`store_logo`, f.`customer_type_name`
		FROM  `app_buyback_order` AS a  
		INNER JOIN `app_customer` AS b ON ( b.`customer_id` = a.`customer_id` )
		INNER JOIN `app_store_master` AS d ON ( d.`store_id` = a.`website_id` )  
		LEFT JOIN `app_customer_type` as f ON ( b.`customer_type_id` = f.`customer_type_id` ) 
		WHERE  a.`id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()==1)
		{
			$collection_item_list_array = array();
			$row = $dbc->db_fetch_assoc(true);
			return $row;
		}
	}
	
	
	
	function isOrderExist($order_number, $website_id){
		$sql = "SELECT `id` FROM `app_buyback_order` WHERE `order_number` = '$order_number' AND `website_id` = '$website_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() > 0  ? true : false;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL, $isUnderCustomerReview = false){
		Modal::load(array('ProductAvailbility'));		
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_buyback_order`" => array(
															"column"=> array("`id`",  "order_number", "customer_mobile", "customer_email", "customer_address", "product_sku", "product_name", "product_quantity", "buy_price", "buy_currency", "base_price", "base_currency", "payout_method", "payout_detail", "product_packging", "status", "created_at"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_customer`" => array(
															"column"=> array("`customer_id`", "`customer_fname`", "`customer_lname`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_buyback_order`", "on"=>array("`customer_id`" => "`customer_id`"))
															),
								"`app_store_master`" => array(
															"column"=> array("`store_name`", "`store_title`", "`store_official_name`", "`store_icon`"),
															"reference" => "c",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_buyback_order`", "on"=>array("`store_id`" => "`website_id`"))
															)		
								),
					"ORDER"	=>array("`store_name`", "`store_name`", "`web_order_number`", "`created_at`","`customer_fname`", "`product_sku`", "`buy_price`", "product_quantity")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $field => $values){
				$filedCondArray = array();
				if($field == "bb_order_created_date_from"){
					if($values)
						$filedCondArray[] = array("DATE(bb_order_created_date)", ">=", sanitizePostData($values));
				}
				elseif($field == "bb_order_created_date_to"){
					if($values)
						$filedCondArray[] = array("DATE(bb_order_created_date)", "<=", sanitizePostData($values));
				}
				else
				{
					if(is_array($values)){
						foreach($values as $_val){
							$voa = explode("|||", $_val);
							$filedCondArray[] = array("".$field, isset($voa[1]) ? $voa[1] : "=", sanitizePostData($voa[0]));
						}
					}
					else{
						$voa = explode("|||", $values);
						$filedCondArray[] = array("".$field, isset($voa[1]) ? $voa[1] : "=", sanitizePostData($voa[0]));
					}
				}
				$conditionArray[] = $filedCondArray;	
			}
		}
		
		if(isCustomer()){
			$conditionArray[] = array("`customer_id`", "=", getLoginId());	
		}
		
		$this->condition = $conditionArray;
		
										
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`id`");
			
		
		$sql = $this->getSql();
		$_SESSION['BB-ORDER']['BUYBACKORDER'] = $this->SqlExceptLimit;
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		//$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		//$num_rows_total= $dbcTotal->db_num_rows($resultTotal);
		$num_rows_total = $this->getTotalCount();
		
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array(), "sql" => $sql );
		$pa = new ProductAvailbility();
		while($row = $dbc->db_fetch_assoc(true)){
			
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".$app->imagePath($row["store_icon"])."\" style=\"height:24px; width:24px;\"><span class=\"avatar-status badge-".($row['product_packging'] ?'success':(!$row['product_packging'] ?'danger':'warning'))."\"></span></div><div class='meta_detail_$row[id]'></div>".(isAdmin() ? "<a data-quick-view=\"true\" data-toggle=\"modal\" data-target=\"#appModalQuick\" data-quick-url=\"viewbuybackorder/{$row['id']}\" class=\"badge badge-default text-dark\">View</a>":""),		
								viewText($row["store_official_name"]),
								"<span data-trigger=\"hover\" data-toggle=\"popover-ajax\"  data-popover-action=\"bborder\" data-popover-id=\"{$row['id']}\">".viewText($row["order_number"])."</span>"."<p class='text-muted'>$row[product_sku]</p>",
								dateView($row["created_at"],"SMALL")."<br/>",
								viewText($row["customer_fname"] . " ". $row["customer_lname"]),
								viewText(round($row["buy_price"],2)).' '.$row['buy_currency'],
								$row["product_quantity"],
								$row["status"],
								isAdmin() ? "<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><span class=\"sr-only\">Toggle Dropdown</span></button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    ".(isAdmin()?	
    ("<a class=\"dropdown-item redirect\" href=\"viewbuybackorder/".$row['id']."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::BBINVOICE($row['id'])."');\"><i class=\"fa fa-file-pdf-o fa-fw text-danger\"></i> Download Invoice</a>
	<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[id]|K', '<img class=\'img-avatar\' src=\'".$app->imagePath($row["store_icon"])."\' style=\'height:24px; width:24px;\'> Buyback #$row[order_number] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
	<a class=\"dropdown-item\" href=\"javascript:requestFeedback('".$row['id']."', 'K', '".$row['customer_id']."')\"><i class=\"fa fa-comments-o fa-fw\"></i> Request Feedback</a>"):"")." </div></div>" : "<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[id]|K', '<img class=\'img-avatar\' src=\'".$app->imagePath($row["store_icon"])."\' style=\'height:24px; width:24px;\'> #$row[order_number] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>",
								"viewbuybackorder/".$row['id']
			);
		}
		return json_encode($output);		
	}
	
	function getWebsiteFilteration(){
		$sql = "SELECT a.`website_id`, b.`store_name`, b.`store_title`, b.`store_icon`, COUNT(a.`id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`website_id` = b.`store_id` GROUP BY `website_id` ORDER BY `store_name`";
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
	
	function getBbOrderCurrency(){
		$sql = "SELECT `buy_currency`, COUNT(`id`) AS record FROM `".$this->table_name."` GROUP BY `buy_currency` ORDER BY `buy_currency`";
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
class BuybackOrderProductMedia extends DB{
	public $id;
	public $key_id		=	"bpoi_id";
	public $table_name	=	"app_buyback_order_product_image";
	public $status		=	"bpoi_status";	
	public $bpoi_wo_id	=	"bpoi_wo_id";
	function __construct($bpoi_id=0){
				
		$this->id	=	$bpoi_id;
	}
	
	function getImageList($bpoi_wo_id){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `".$this->bpoi_wo_id."` = '$bpoi_wo_id' AND `".$this->status."` = '1' ";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$product_image_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$product_image_array[] = $row;
			}
			return $product_image_array;
		}
		return NULL;
	}
	
}
?>