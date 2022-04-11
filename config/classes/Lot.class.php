<?php
class Lot extends DB{
	public $id;
	public $key_id	=	"lot_id";
	public $table_name	=	"app_product_lots";
	public $status	=	"lot_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function getLotByCode($lot_code){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `lot_code` = '$lot_code'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows() == 1){
			return $dbc->db_fetch_assoc(true);
		}
		return $data;
	}
	
	function getLotCode(){
		$sql = "SELECT `lot_code` as code FROM `".$this->table_name."` WHERE YEAR(`lot_created_date`) = '".date("Y")."' ORDER BY `lot_created_date` DESC LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$chrStr = "AA00";
		if($dbc->db_num_rows() == 1){
			$data = $dbc->db_fetch_assoc(true);
			$chrStr = substr($data['code'], 3, 4);
			$chrStr++;
		}
		if(substr($chrStr, 2, 2) === "00")
		$chrStr++;
		return date("y")."L".$chrStr;
	}
	
	function getLotLog(){
		$comlaint = new ComplaintLog();
		return $comlaint->getLog($this->id, 'L');
	}
	
	function getDetails(){
		$sql = "SELECT a.*, b.user_fname, b.user_lname, b.user_image, COUNT(c.`lot_item_id`) AS record FROM `".$this->table_name."` AS a 
		INNER JOIN `app_system_user` AS b ON a.`lot_created_by` = b.`user_id` 
		LEFT JOIN `app_product_lots_item` AS c ON a.`lot_id` = c.`lot_item_lot_id` 
		WHERE `".$this->key_id."` = '".$this->id."' GROUP BY a.`lot_id`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function getProducts(){
		$lotProduct = new LotProduct();
		return $lotProduct->getProducts($this->id);
	}
	
	function getExistingItemsIds(){		
		$sql = "SELECT `lot_item_id` FROM `app_product_lots_item` WHERE `lot_item_lot_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		if($dbc->db_num_rows($result)){
			while($row = $dbc->db_fetch_assoc(true))
			$record_array[] = $row['lot_item_id'];
		}
		return $record_array;
	}
	
	function isProductInLot($lot_item_code, $lot_item_product_id, $lot_item_lot_id=0){
		$condition = $lot_item_lot_id ? " AND `lot_item_lot_id` = '$lot_item_lot_id' " : "";
		$sql = "SELECT `lot_item_id` FROM `app_product_lots_item` WHERE `lot_item_code` = '$lot_item_code' AND `lot_item_product_id` = '$lot_item_product_id' $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){
			$record = $dbc->db_fetch_assoc(true);
			return $record['lot_item_id'];
		}
		return 0;
	}
	
	function unlinkItems($items){
		$itemsIds = implode(",", $items);
		$sql = "DELETE FROM `app_product_lots_item` WHERE `lot_item_id` IN ($itemsIds)";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
		
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				//`lot_id`, `lot_code`, `lot_name`, `lot_created_date`, `lot_created_by`, `lot_status`
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_product_lots`" => array(
															"column"=> array("`lot_id`", "`lot_code`", "`lot_name`", "`lot_created_date`", "`lot_update_date`", "`lot_created_by`", "`lot_status`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_system_user`" => array(
															"column"=> array("`user_fname`", "`user_lname`", "`user_image`"),
															"reference" => "c",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_product_lots`", "on"=>array("`user_id`" => "`lot_created_by`"))
															),
								"`app_product_lots_item`" => array(
															"column"=> array("COUNT(`lot_item_id`) AS record"),
															"reference" => "d",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_product_lots`", "on"=>array("`lot_item_lot_id`" => "`lot_id`"))
															)
								),
					"ORDER"	=>array("user_fname", "lot_code", "`lot_name`", "`lot_created_date`", "`COUNT(`lot_item_id`)`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
															
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`lot_id`");
			
		
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
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".getResizeImage($row["user_image"],50)."\" height=\"40px\"></div>",
								viewText($row["lot_name"]),
								viewText($row["lot_code"]),
								$row["record"],	
								dateView($row["lot_created_date"],"DATE"),
								dateView($row["lot_update_date"],"DATE"),								
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
	<a class=\"dropdown-item redirect\" href=\"viewlotitems/".$row['lot_id']."\"><i class=\"fa fa-truck fa-fw\"></i> View</a>
    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[lot_id]|L', 'Lot #$row[lot_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::LOTEXCEL($row['lot_id'])."');\"><i class=\"fa fa-file-excel-o fa-fw text-success\"></i> Export to Excel</a></div></div>",
								$row['lot_id']
								
			);
		}
		return json_encode($output);		
	}
}

class LotProduct extends DB{
	public $id;
	public $key_id	=	"lot_item_id";
	public $table_name	=	"app_product_lots_item";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function getProducts($lot_id){
		$sql = "SELECT CONCAT(`lot_item_code`, '|' ,`lot_item_product_id`) AS keyname FROM `".$this->table_name."` WHERE `lot_item_lot_id` = '$lot_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		if($dbc->db_num_rows()>0)
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$record_array[] = $row['keyname'];
			}			
		}
		return $record_array;
	}
}
?>