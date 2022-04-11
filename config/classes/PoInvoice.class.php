<?php
class PoInvoice extends DB{
	public $id;
	public $key_id	=	"pob_id";
	public $table_name	=	"app_purchase_order_bill";
	public $status	=	"pob_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}	
		
	function getPobCode(){
		$sql = "SELECT `pob_code` as code FROM `".$this->table_name."` WHERE YEAR(`pob_created_date`) = '".date("Y")."' ORDER BY `pob_created_date` DESC LIMIT 1";
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
		return date("y")."D".$chrStr;
	}
	
	function getDetails(){
		$sql = "SELECT *
		FROM  `app_purchase_order_bill` AS a  
		INNER JOIN `app_purchase_order` AS b ON ( b.`po_id` = a.`pob_po_id`  ) 
		INNER JOIN `app_store_master` AS c ON ( c.`store_id` = b.`po_store_id`  ) 
		INNER JOIN `app_supplier_record` AS d ON ( d.`supplier_id` = b.`po_suplier_id`  ) 
		WHERE `".$this->key_id."` = '".$this->id."' OR MD5(`".$this->key_id."`) = '".$this->id."'";
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
		return $comlaint->getLog($this->id, 'D');
	}
	
	function getPoAllInvoices($pob_po_id){
		$sql = "SELECT a.* FROM `app_purchase_order_bill` AS a WHERE `pob_po_id` = '$pob_po_id'";
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
	
	function getPobItemQuery(){
		return "SELECT a.`pobi_description`, a.`pobi_rate`, a.`pobi_vat`, a.`pobi_quantity`, a.`pobi_vat_amount`, a.`pobi_amount`, b.`wci_name` FROM `app_purchase_order_bill_items` AS a INNER JOIN `app_wc_item_master` b ON a.`pobi_item_id` = b.`wci_id` WHERE `pobi_pob_id` = '".$this->id."'";
	}
	
	function getPoItemSum(){
		$sql = "SELECT SUM(`pobi_quantity`) AS pob_total_quantity, SUM(`pobi_amount`) AS pob_sub_total, SUM(`pobi_vat_amount`) AS pob_vat_total FROM `app_purchase_order_bill_items` WHERE `pobi_pob_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){		
			return $dbc->db_fetch_assoc(true);
		}
		else
			return false;
	}
		
	function setPobAmount(){
		$sql = "UPDATE `".$this->table_name."` SET `pob_item_amount_total` = (SELECT SUM(`pobi_amount`+`pobi_vat_amount`) FROM `app_purchase_order_bill_items` WHERE `pobi_pob_id` = '".$this->id."') ";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
		
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_purchase_order_bill`" => array(
															"column"=> array("pob_id", "pob_code", "pob_invoice_date", "pob_due_date", "pob_item_amount_total", "pob_amount_discount", "pob_currency", "pob_created_date", "pob_status"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_supplier_record`" => array(
															"column"=> array("`supplier_id`", "`supplier_name`"),
															"reference" => "c",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_purchase_order_bill`", "on"=>array("`supplier_id`" => "`pob_suplier_id`"))
															)
								),
					"ORDER"	=>array("pob_code", "pob_invoice_date", "pob_due_date", "pob_item_amount_total", "pob_amount_discount", "pob_currency", "pob_created_date")						
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
								viewText($row["pob_code"]),
								dateView($row["pob_invoice_date"],"DATE"),
								dateView($row["pob_due_date"],"DATE"),
								viewText($row["supplier_name"]),
								viewText($row["pob_item_amount_total"]),	
								viewText($row["pob_amount_discount"]),		
								viewText($row["pob_currency"]),
								dateView($row["pob_created_date"],"DATE"),								
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-lg fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
	<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('{$row['pob_id']}|D', '$row[pob_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
	<a class=\"dropdown-item\" target=\"new\" href=\"".DOC::POINVOICE($row['pob_id'])."\"><i class=\"fa fa-file-pdf-o fa-fw text-danger\"></i> Download</a>
    </div></div>"								
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
	
}

class PobItems extends DB{
	public $id;
	public $key_id	=	"pobi_id";
	public $table_name	=	"app_purchase_order_bill_items";
	public $status	=	"pobi_status";

	function __construct($id=0){		
		$this->id	=	$id;
	}
			
	public function getAllPobItems($pobi_pob_id){
		$sql = "SELECT a.*, b.`wci_name` FROM `app_purchase_order_bill_items` AS a 
		INNER JOIN `app_wc_item_master` b ON a.`pobi_item_id` = b.`wci_id` WHERE a.`pobi_pob_id` = '$pobi_pob_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		if($dbc->db_num_rows() > 0){
			while($row = $dbc->db_fetch_assoc(true))
				$record_array[] = $row;
		}
		return $record_array;
	}
			
	public function getAllPoItems($pobi_po_id){
		$sql = "SELECT a.*, b.`wci_name` FROM `app_purchase_order_bill_items` AS a 
		INNER JOIN `app_wc_item_master` b ON a.`pobi_item_id` = b.`wci_id` WHERE `pobi_po_id` = '$pobi_po_id'";
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