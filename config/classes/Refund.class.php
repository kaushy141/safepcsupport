<?php
class Refund extends DB{
	public $id;
	public $key_id	=	"refund_id";
	public $table_name	=	"app_refund_record";
	public $status	=	"refund_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function getRefundByCode($refund_code){
		$sql = "SELECT * FROM `app_refund_record` WHERE `refund_code` = '$refund_code'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows() == 1){
			return $dbc->db_fetch_assoc(true);
		}
		return $data;
	}
	
	function getRefundCode(){
		$sql = "SELECT `refund_code` as code FROM `".$this->table_name."` WHERE YEAR(`refund_initiated_date`) = '".date("Y")."' ORDER BY `refund_initiated_date` DESC LIMIT 1";
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
		return date("y")."R".$chrStr;
	}
	
	function getRefundLog(){
		$comlaint = new ComplaintLog();
		return $comlaint->getLog($this->id, 'R');
	}
	
	function getDetails(){
		$sql = "SELECT a.*, b.`wc_status_name` , b.`wc_status_color_code`,  
		CONCAT(cu.customer_fname, ' ', cu.customer_lname) as customer_name, cu.customer_image, 
		CONCAT(i.user_fname, ' ', i.user_lname) as initiator_name, i.user_image as  initiator_image,
		CONCAT(p.user_fname, ' ', p.user_lname) as processor_name, p.user_image as  processor_image,
		CONCAT(c.user_fname, ' ', c.user_lname) as completor_name, c.user_image as  completor_image   FROM `".$this->table_name."` AS a 
		INNER JOIN `app_customer` as cu on a.`refund_customer_id` = cu.`customer_id` 
		INNER JOIN `app_wc_status` as b on a.`refund_status`  = b.`wc_status_id`   
		LEFT JOIN `app_system_user` AS i ON a.`refund_initiated_by` = i.`user_id`   
		LEFT JOIN `app_system_user` AS p ON a.`refund_process_by` = p.`user_id`   
		LEFT JOIN `app_system_user` AS c ON a.`refund_completed_by` = c.`user_id`    
		
		WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function isRefundExist($refund_type_code, $refund_type_id, $nonCancelled=false){
		$condition = $nonCancelled ? "AND refund_status != '3'" : "";
		$sql = "SELECT `refund_id` FROM `".$this->table_name."` WHERE `refund_type_code` = '$refund_type_code' AND `refund_type_id` = '$refund_type_id' $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result);
	}
	
	function getAllRefund($refund_type_code, $refund_type_id){
		$sql = "SELECT a.*, b.`wc_status_name` , b.`wc_status_color_code`,  
		CONCAT(cu.customer_fname, ' ', cu.customer_lname) as customer_name, cu.customer_image, 
		CONCAT(i.user_fname, ' ', i.user_lname) as initiator_name, i.user_image as  initiator_image,
		CONCAT(p.user_fname, ' ', p.user_lname) as processor_name, p.user_image as  processor_image,
		CONCAT(c.user_fname, ' ', c.user_lname) as completor_name, c.user_image as  completor_image   FROM `".$this->table_name."` AS a 
		INNER JOIN `app_customer` as cu on a.`refund_customer_id` = cu.`customer_id` 
		INNER JOIN `app_wc_status` as b on a.`refund_status`  = b.`wc_status_id`   
		LEFT JOIN `app_system_user` AS i ON a.`refund_initiated_by` = i.`user_id`   
		LEFT JOIN `app_system_user` AS p ON a.`refund_process_by` = p.`user_id`   
		LEFT JOIN `app_system_user` AS c ON a.`refund_completed_by` = c.`user_id` WHERE `refund_type_code` = '$refund_type_code' AND `refund_type_id` = '$refund_type_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){
			while($data = $dbc->db_fetch_assoc(true))
			$result_array[] = $data;
		}
		return $result_array;
	}
	
	function getStoreId($code, $id){
		if($code == "S"){
			$sql = "SELECT `sales_invoice_store_id` AS store_id FROM `app_sales_invoice` WHERE `sales_invoice_id` = '$id'";
		}elseif($code == "O"){
			$sql = "SELECT `web_order_website_id` AS store_id FROM `app_website_order` WHERE `web_order_id`= '$id'";
		}
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){		
			$data = $dbc->db_fetch_assoc(true);
			return $data['store_id'];
		}
		else
			return false;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_refund_record`" => array(
															"column"=> array("`refund_id`", "`refund_code`", "`refund_reference`", "`refund_type_code`", "`refund_amount`", "`refund_amount_currency`", "`refund_pattern`", "`refund_initiated_date`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_activity_section`" => array(
															"column"=> array("activity_section_name"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_refund_record`", "on"=>array("`activity_section_code`" => "`refund_type_code`"))
															),
								"`app_customer`" => array(
															"column"=> array("`customer_fname`", "`customer_lname`", "`customer_image`"),
															"reference" => "c",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_refund_record`", "on"=>array("`customer_id`" => "`refund_customer_id`"))
															),
								"`app_wc_status`" => array(
															"column"=> array("`wc_status_name`", "`wc_status_color_code`"),
															"reference" => "d",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_refund_record`", "on"=>array("`wc_status_id`" => "`refund_status`"))
															)
								),
					"ORDER"	=>array("refund_code", "customer_fname", "`activity_section_name`", "`refund_amount`", "`refund_pattern`", "`wc_status_name`")						
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
		$this->groupby = array("a.`refund_id`");
			
		
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
								viewText($row["refund_code"]),
								$row["customer_fname"].' '.$row["customer_lname"],
								viewText($row["activity_section_name"])."<br/>".$row["refund_reference"],								
								viewText($row["refund_amount"]. ' '.$row["refund_amount_currency"]),		
								$row["refund_pattern"],
								$row["wc_status_name"],						
								dateView($row["refund_initiated_date"],"DATE"),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
	<a class=\"dropdown-item redirect\" href=\"viewrefund/".md5($row['refund_id'])."\"><i class=\"fa fa-truck fa-fw\"></i> View</a>
	<a class=\"dropdown-item redirect\" href=\"addcreditnote/".md5($row['refund_id'])."\"><i class=\"fa fa-credit-card fa-fw\"></i> Credit Note</a>
    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[refund_id]|R', '$row[refund_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a></div></div>",
								"viewrefund/".md5($row['refund_id']),
								$row['wc_status_color_code']
								
			);
		}
		return json_encode($output);		
	}
	
	function getRefundSectionList(){
		$sql = "SELECT count(*) as record, b.`activity_section_code`, b.`activity_section_name` FROM `app_refund_record` AS a INNER JOIN `app_activity_section` AS b ON a.`refund_type_code` = b.`activity_section_code` WHERE 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$result_array[] = $row;
			}
		}
		return $result_array;
	}
	
	function getInitiatorFilteration(){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`refund_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`refund_initiated_by` = b.`user_id` GROUP BY a.`refund_initiated_by` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	
	function getProcessorFilteration(){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`refund_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`refund_process_by` = b.`user_id` GROUP BY a.`refund_process_by` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	function getCompletorFilteration(){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`refund_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`refund_completed_by` = b.`user_id` GROUP BY a.`refund_completed_by` ORDER BY b.`user_fname`";
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
?>