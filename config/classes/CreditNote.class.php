<?php
class CreditNote extends DB{
	public $id;
	public $key_id	=	"credit_note_id";
	public $table_name	=	"app_refund_credit_notes";
	public $status	=	"credit_note_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function getCreditNoteByCode($credit_note_code){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `credit_note_code` = '$credit_note_code'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows() == 1){
			return $dbc->db_fetch_assoc(true);
		}
		return $data;
	}
	
	function getCreditNoteCode(){
		$sql = "SELECT `credit_note_code` as code FROM `".$this->table_name."` WHERE YEAR(`credit_note_created_date`) = '".date("Y")."' ORDER BY `credit_note_created_date` DESC LIMIT 1";
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
		return date("y")."C".$chrStr;
	}
	
	function getCreditNoteLog(){
		$comlaint = new ComplaintLog();
		return $comlaint->getLog($this->id, 'N');
	}
	
	function getDetails(){
		$sql = "SELECT a.*, b.`refund_code`, b.`refund_type_code`, b.`refund_type_id`, b.`refund_reference`, b.`refund_customer_id`, b.`refund_amount`, b.`refund_amount_currency` FROM `".$this->table_name."` AS a 
		INNER JOIN `app_refund_record` as b on a.`credit_note_refund_id` = b.`refund_id` 
		WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function isCreditNoteExist($credit_note_refund_id ){
		$sql = "SELECT `credit_note_id` FROM `".$this->table_name."` WHERE `credit_note_refund_id` = '$credit_note_refund_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result);
	}
	
	function getCreditNoteQuery(){	
		return "SELECT `credit_note_item_description`, `credit_note_quantity`, `credit_note_amount`, `credit_note_amount_total`, `credit_note_currency`, `credit_note_vat`, ROUND((`credit_note_quantity` * ((`credit_note_vat` * `credit_note_amount`)/100)),2) AS credit_note_vat_amount, `credit_note_remaining` FROM `".$this->table_name."` 
		WHERE `".$this->key_id."` = '".$this->id."'";
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_refund_credit_notes`" => array(
															"column"=> array("`credit_note_id`", "`credit_note_code`", "`credit_note_date`", "`credit_note_reference`", "`credit_note_item_description`", "`credit_note_quantity`", "`credit_note_amount`", "`credit_note_currency`", "`credit_note_vat`", "`credit_note_created_date`"),
															"reference" => "a",
															"join" => NULL
															)
								),
					"ORDER"	=>array("credit_note_code", "credit_note_reference", "`credit_note_amount`", "`credit_note_date`", "`credit_note_created_date`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if(isset($filter) && $filter != NULL && count($filter)){
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
		//$this->groupby = array("a.`credit_note_id`");
			
		
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
								viewText($row["credit_note_code"]),
								viewText($row["credit_note_reference"]),						
								viewText($row["credit_note_amount"]. ' '.$row["credit_note_currency"]),	
								dateView($row["credit_note_date"],"DATE"),	
								dateView($row["credit_note_created_date"],"DATE"),	
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
	<a class=\"dropdown-item redirect\" href=\"updatecreditnote/".md5($row['credit_note_id'])."\"><i class=\"fa fa-truck fa-fw\"></i> View</a>
    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[credit_note_id]|N', '$row[credit_note_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
	<a class=\"dropdown-item\" target=\"new\" href=\"".DOC::CREDITNOTE(md5($row['credit_note_id']))."\"><i class=\"fa fa-file-pdf-o fa-fw text-danger\"></i> Credit Note</a></div></div>"
								
			);
		}
		return json_encode($output);		
	}
	
}
?>