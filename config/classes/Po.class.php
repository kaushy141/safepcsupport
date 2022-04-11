<?php
class Po extends DB{
	public $id;
	public $key_id	=	"po_id";
	public $table_name	=	"app_purchase_order";
	public $status	=	"po_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}	
		
	function getPoCode(){
		$sql = "SELECT `po_code` as code FROM `".$this->table_name."` WHERE YEAR(`po_created_date`) = '".date("Y")."' ORDER BY `po_created_date` DESC LIMIT 1";
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
		return date("y")."U".$chrStr;
	}
	
	function createCopy(){
		$data = $this->getRawDetails();
		unset($data['po_id']);
		$data['po_code'] = $this->getPoCode();
		$data['po_created_by'] = getLoginId();
		$data['po_created_date'] = 'NOW()'; 
		$data['po_is_sent_to_supplier'] = '0'; 
		$data['po_is_approved']	= 0; 
		$data['po_is_closed'] = 0;
		$data['po_status']	= 1;
		$po = new Po();
		$po_id = $po->insert($data);
		
		$poItems = new PoItems($this->id);
		$poItemList = $poItems->getAllPoItems();
		if(count($poItemList))
		{
			$poi_id = 0;
			foreach($poItemList as $item){
				$poi_id++;
				$poi_po_id 			= $po_id;
				$poi_item_id 		= $item['poi_item_id'];
				$poi_description 	= $item['poi_description'];
				$poi_rate 			= $item['poi_rate'];
				$poi_vat 			= $item['poi_vat'];
				$poi_quantity_total = $item['poi_quantity_total'];
				$poi_amount_total 	= round(($poi_quantity_total * $poi_rate), 2);
				$poi_amount_vat 	= round($poi_quantity_total * (($poi_rate * $poi_vat)/100), 2);
				$poi_update_date	= 'NOW()';
				
				$poiData = compact('poi_id', 'poi_po_id', 'poi_item_id', 'poi_description', 'poi_rate', 'poi_vat', 'poi_quantity_total', 'poi_amount_total', 'poi_amount_vat', 'poi_update_date');
				$poItems = new PoItems(0);
				$poItems->insert($poiData);
			}
			$po->setPoAmount();
		}
		return $po_id;
	}
	
	function getRawDetails(){
		$sql = "SELECT * FROM  `app_purchase_order` WHERE `".$this->key_id."` = '".$this->id."' OR MD5(`".$this->key_id."`) = '".$this->id."'";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){		
			$record = $dbc->db_fetch_assoc(true);
			$this->id = $record[$this->key_id];
			return $record;
		}
		else
			return false;
	}
	
	function getDetails(){
		$sql = "SELECT * FROM  `app_purchase_order` AS a  INNER JOIN `app_store_master` AS b ON ( b.`store_id` = a.`po_store_id`  )  INNER JOIN `app_supplier_record` AS c ON ( c.`supplier_id` = a.`po_suplier_id`  ) WHERE `".$this->key_id."` = '".$this->id."' OR MD5(`".$this->key_id."`) = '".$this->id."'";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){		
			$record = $dbc->db_fetch_assoc(true);
			$this->id = $record[$this->key_id];
			return $record;
		}
		else
			return false;
	}
	
	function getPoLog(){
		$comlaint = new ComplaintLog();
		return $comlaint->getLog($this->id, 'U');
	}
	
	function markApproved(){
		$sql = "UPDATE `".$this->table_name."` SET `po_is_approved` = '1' WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}	
	
	public function isApprooved(){
		$sql = "SELECT `".$this->key_id."` FROM `".$this->table_name."` WHERE `".$this->key_id."` = '".$this->id."' AND `po_is_approved` = '1'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows();
	}	
	
	function setPoAmount(){
		$sql = "UPDATE `".$this->table_name."` SET `po_item_amount_total` = (SELECT SUM(`poi_amount_total` + `poi_amount_vat`) FROM `app_purchase_order_items` WHERE `poi_po_id` = '".$this->id."')";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getPoReceivedAmount(){
		$sql = "SELECT SUM(`pobi_amount`+`pobi_vat_amount`) FROM `app_purchase_order_bill_items` WHERE `pobi_po_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getPoItemQuery(){
		return "SELECT a.`poi_description`, a.`poi_rate`, a.`poi_vat`, a.`poi_quantity_total`, a.`poi_amount_vat`, a.`poi_amount_total`, b.`wci_name` FROM `app_purchase_order_items` AS a INNER JOIN `app_wc_item_master` b ON a.`poi_item_id` = b.`wci_id` WHERE `poi_po_id` = '".$this->id."'";
	}
	
	function getPoItemSum(){
		$sql = "SELECT SUM(`poi_quantity_total`) AS po_total_quantity, SUM(`poi_amount_total`) AS po_sub_total, SUM(`poi_amount_vat`) AS po_vat_total FROM `app_purchase_order_items` WHERE `poi_po_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){		
			return $dbc->db_fetch_assoc(true);
		}
		else
			return false;
	}
	
	function managePoItems($poItemsData){
		extract($poItemsData);
		$processIds = array();
		$poi_id = 0;
		PoItems::removeItems($this->id);
		foreach($po_item_sr as $item => $srno){
			$poi_id++;
			$poi_po_id 			= $this->id;
			$poi_item_id 		= $po_item[$item];
			$poi_description 	= $po_item_description[$item];
			$poi_rate 			= $po_item_rate[$item];
			$poi_vat 			= $po_item_vat[$item];
			$poi_quantity_total = $po_item_qty[$item];
			$poi_amount_total 	= round(($poi_quantity_total * $poi_rate), 2);
			$poi_amount_vat 	= round($poi_quantity_total * (($poi_rate * $poi_vat)/100), 2);
			$poi_update_date	= 'NOW()';
			
			$poiData = compact('poi_id', 'poi_po_id', 'poi_item_id', 'poi_description', 'poi_rate', 'poi_vat', 'poi_quantity_total', 'poi_amount_total', 'poi_amount_vat', 'poi_update_date');
			$poItems = new PoItems(0);
			$poItems->insert($poiData);
		}
		$this->setPoAmount();
	}
	
		
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
		//`po_id`, `po_code`, `po_description`, `po_store_id`, `po_suplier_id`, `po_shipping_address`, `po_currency`, `po_item_amount_total`, `po_amount_discount`, `po_order_date`, `po_crew`, `po_shipping_via`, `po_created_date`, `po_is_approved`, `po_is_closed`, `po_status`
					"TABLES"=>array(
								"`app_purchase_order`" => array(
															"column"=> array("`po_id`", "`po_code`", "`po_description`", "`po_currency`", "`po_item_amount_total`", "`po_amount_discount`", "`po_order_date`", "`po_created_date`", "`po_is_approved`", "`po_is_closed`", "`po_is_sent_to_supplier`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_store_master`" => array(
															"column"=> array("`store_name`", "`store_icon`", "`store_link`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_purchase_order`", "on"=>array("`store_id`" => "`po_store_id`"))
															),
								"`app_supplier_record`" => array(
															"column"=> array("`supplier_id`", "`supplier_name`"),
															"reference" => "c",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_purchase_order`", "on"=>array("`supplier_id`" => "`po_suplier_id`"))
															)
								),
					"ORDER"	=>array("po_code", "po_order_date", "supplier_name", "`po_description`", "`po_item_amount_total`", "`po_amount_discount`", "`po_currency`", "`po_is_approved`", "`po_is_closed`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					foreach($values as $_val)
						$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($_val));
				}
				else
					$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($values));
				$conditionArray[] = $filedCondArray;	
			}
		}
		$this->condition = $conditionArray;
													
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		//$this->groupby = array("a.`po_id`");
			
		
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
								viewText($row["po_code"]),
								dateView($row["po_order_date"],"DATE"),
								viewText($row["supplier_name"]),	
								viewText($row["po_item_amount_total"]),		
								viewText($row["po_amount_discount"]),
								viewText($row["po_currency"]),
								$row["po_is_closed"] ? '<span class="badge badge-danger">Closed</span>':'<span class="badge badge-success">Open</span>',
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-lg fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
	<a class=\"dropdown-item redirect\" href=\"purchaseorder/".md5($row['po_id'])."\"><i class=\"fa fa-eye fa-fw\"></i> ".($row['po_is_approved'] ? 'View':'Update')."</a>
	<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('{$row['po_id']}|U', '$row[po_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
	<a class=\"dropdown-item sentpotosupplier\" data-id=\"$row[po_id]\" href=\"#\"><i class=\"fa fa-reply fa-fw ".($row['po_is_sent_to_supplier'] ? 'text-success':'')."\"></i> Sent to suplier</a>
	".($row['po_is_closed'] == 0 ? "<a class=\"dropdown-item redirect\" href=\"addpurchaseorderinvoice/".md5($row['po_id'])."\"><i class=\"fa fa-plus fa-fw\"></i> Add Invoice</a>":"")."
	<a class=\"dropdown-item\" target=\"new\" href=\"".DOC::PO(md5($row['po_id']))."\"><i class=\"fa fa-file-pdf-o fa-fw text-danger\"></i> Download</a>
    </div></div>",
								
								$row["po_is_approved"] ? '#c5f4a1':'#ffffff"',
								"purchaseorder/".md5($row['po_id'])
								
			);
		}
		return json_encode($output);		
	}
	static function getPoStatusCount(){
		$sql = "
		SELECT count(`po_id`) as record, 'Closed' as status FROM `app_purchase_order` WHERE `po_is_closed` = '1'
UNION 
		SELECT count(`po_id`) as record, 'Approved' as status FROM `app_purchase_order` WHERE `po_is_approved` = '1'		
UNION 
		SELECT count(`po_id`) as record, 'Open' as status FROM `app_purchase_order` WHERE `po_is_closed` = '0'";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		if($dbc->db_num_rows()>0)
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$record_array[] = $row;
			}
		}
		return $record_array;		
	}
	
	function getWebsiteFilteration(){
		$sql = "SELECT a.`po_store_id`, b.`store_name`, b.`store_icon`, COUNT(a.`po_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`po_store_id` = b.`store_id` GROUP BY `po_store_id` ORDER BY `store_name`";
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
	function getCreatorFilteration(){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`po_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`po_created_by` = b.`user_id` GROUP BY a.`po_created_by` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	
	public function hasClosedOrFullItems($items){
		$itemsIn = implode(',', $items);
		$sql = "SELECT * FROM `app_purchase_order_items` WHERE `poi_po_id` = '".$this->id."' AND `poi_id` IN ($itemsIn) AND (`poi_is_closed` = '1' OR `poi_quantity_received` = `poi_quantity_total`)";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows();
	}
		
	
}

class PoItems extends DB{
	public $id;
	public $key_id	=	"poi_po_id";
	public $table_name	=	"app_purchase_order_items";
	public $status	=	"poi_status";

	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	public static function removeItems($poi_po_id){
		$sql = "DELETE FROM `app_purchase_order_items` WHERE `poi_po_id` = '$poi_po_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	public static function isClosed($poi_po_id, $poi_id){
		$sql = "SELECT * FROM `app_purchase_order_items` WHERE `poi_po_id` = '$poi_po_id' AND `poi_id` = '$poi_id' AND `poi_is_closed` = '1'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows();
	}
	
	public static function updateReceivedItems($poi_po_id, $poi_id, $poi_quantity_received, $poi_amount_received){
		$sql = "UPDATE `app_purchase_order_items` SET `poi_quantity_received` = (`poi_quantity_received` + '$poi_quantity_received'), `poi_amount_received` = (`poi_amount_received` + '$poi_amount_received') WHERE `poi_po_id` = '$poi_po_id' AND `poi_id` = '$poi_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	public static function updateCloseStatus($poi_id, $poi_po_id, $poi_item_id, $poi_is_closed){
		$sql = "UPDATE `app_purchase_order_items` SET `poi_is_closed` = '$poi_is_closed' WHERE `poi_id` = '$poi_id' AND `poi_po_id` = '$poi_po_id' AND `poi_item_id` = '$poi_item_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	public static function getAllPoItemsGrouped($poi_po_id){
		$sql = "SELECT GROUP_CONCAT(`poi_id`) as ids FROM `app_purchase_order_items` WHERE `poi_po_id` = '$poi_po_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows() == 1){
			$data = $dbc->db_fetch_assoc(true);
			return explode(",", $data['ids']);
		}
		else
			return array();
	}
		
	public function getAllPoItems(){
		$sql = "SELECT * FROM `app_purchase_order_items` WHERE `poi_po_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		if($dbc->db_num_rows() > 0){
			while($row = $dbc->db_fetch_assoc(true))
				$record_array[] = $row;
		}
		return $record_array;
	}
}
?>