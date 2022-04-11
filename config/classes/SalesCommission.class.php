<?php
class SalesCommission extends DB{
	public $id;
	public $key_id	=	"sales_commission_id";
	public $table_name	=	"app_sales_invoice_commission";
	public $status	=	"sales_commission_status";	
	public $user		=	0;
	function __construct($id=0){
				
		$this->id	=	$id;
	}	
	
	function getSalesCommissionBatchCode(){
		$sql = "SELECT `sales_commission_batch` AS code FROM `app_sales_invoice_commission` WHERE SUBSTR(`sales_commission_batch`, 1 , 2) = DATE_FORMAT(CURDATE(), '%y') ORDER BY `sales_commission_batch` DESC LIMIT 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$chrStr = 0;
		if($dbc->db_num_rows() == 1){
			$data = $dbc->db_fetch_assoc(true);
			$chrStr = substr($data['code'], 3, 4);
			$chrStr++;
		}
		if($chrStr == 0)
		$chrStr++;
		return date("y")."M".sprintf("%04d",$chrStr);
	}
	
	function isCommissionExist($sales_commission_user_id, $sales_commission_month){
		$sql="SELECT `sales_commission_batch` FROM `app_sales_invoice_commission` WHERE `sales_commission_user_id` = ' $sales_commission_user_id' AND `sales_commission_month` = '$sales_commission_month'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() ? true : false;
	}
	
	function isSalesCommisionIssued($sales_commission_sales_id_array){
		$sales_commission_sales_ids = "''";
		if(count($sales_commission_sales_id_array))
		{
			$sales_commission_sales_ids = "'". implode("', '", $sales_commission_sales_id_array)."'";
		}
		$sql="SELECT `sales_commission_id` FROM `app_sales_invoice_commission` WHERE `sales_commission_sales_id` IN ($sales_commission_sales_ids)";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() ? true : false;
	}
	
	static function getUserCompletedCommisionList($user_id){
		$sql="
		SELECT 'S' AS type, a.`sales_invoice_id` AS id, a.`sales_invoice_number` AS code,  DATE_FORMAT(a.`sales_invoice_created_date`, '%d-%M %Y') AS created_date, ROUND((a.`sales_invoice_total_amount` - a.`sales_discount_amount`),2) as amount, a.`sales_invoice_currency` AS currency FROM `app_sales_invoice` AS a WHERE a.`sales_invoice_creator` = '$user_id' AND a.`sales_invoice_status` = '1' AND a.`sales_invoice_is_cancelled` = '0' AND a.`sales_invoice_is_generated` = '1' AND a.`sales_invoice_commission_processed` = '0' GROUP BY a.`sales_invoice_id`
 UNION 
 
		SELECT 'O' AS type, a.`web_order_id` AS id, a.`web_order_number` AS code, DATE_FORMAT(a.`web_order_created_date`, '%d-%M %Y') AS created_date, ROUND(a.`web_order_total_amount`,2) AS amount, a.`web_order_currency` AS currency FROM `app_website_order` AS a WHERE a.`web_order_commission_user` = '$user_id' AND a.`web_order_status` = '1' AND a.`web_order_commission_processed` = '0'

		ORDER BY type, created_date";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{			
			while($row = $dbc->db_fetch_assoc(true)){
				$row['amount'] = getBaseAmount($row['amount'], $row['currency'] ? $row['currency'] : 'GBP');
				$result_array[] = $row;
			}
		}			
		return $result_array;
	}
	
	
	static function fixedCommissionAmountIssue(){
		$sql1 = "UPDATE `app_sales_invoice_commission` set `sales_commission_invoice_amount` = (SELECT (`sales_invoice_total_amount` - `sales_discount_amount`) FROM `app_sales_invoice` WHERE `sales_invoice_id` = `sales_commission_sales_id`) WHERE `sales_commission_type_code` = 'S' AND `sales_commission_invoice_amount` = '0.00'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql1);
		
		$sql2 = "UPDATE `app_sales_invoice_commission` set `sales_commission_invoice_amount` = (SELECT ROUND(`web_order_total_amount`, 2) FROM `app_website_order` WHERE `web_order_id` = `sales_commission_sales_id`) WHERE `sales_commission_type_code` = 'O' AND `sales_commission_invoice_amount` = '0.00'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql2);
		
		$sql3 = "UPDATE `app_sales_invoice_commission` SET `sales_commission_amount` = ROUND((`sales_commission_invoice_amount` * `sales_commission_percentage`)/100, 2) WHERE `sales_commission_amount` = '0'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql3);		
	}
	

	static function getSalesInvoiceAmount($code, $record_id, $user_id = 0){
		if($code == 'S')
		{
			$cond = $user_id ? " AND `sales_invoice_creator` = '$user_id' " : "";
			$sql="SELECT ROUND(`sales_invoice_total_amount` - `sales_discount_amount`,2) as amount , `sales_invoice_currency` as currency FROM `app_sales_invoice` WHERE `sales_invoice_id` = '$record_id' AND `sales_invoice_status` = '1' AND `sales_invoice_is_cancelled` = '0' AND `sales_invoice_is_generated` = '1' AND `sales_invoice_commission_processed` = '0' $cond";	
		}
		elseif($code == 'O')
		{
			$cond = $user_id ? " AND `web_order_commission_user` = '$user_id' " : "";
			$sql="SELECT ROUND(`web_order_total_amount`,2) as amount , `web_order_currency` as currency FROM `app_website_order`  WHERE `web_order_id` = '$record_id' AND `web_order_status` = '1' AND `web_order_commission_processed` = '0' $cond";	
		}
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$amount = 0;
		if($dbc->db_num_rows($result))
		{			
			$record = $dbc->db_fetch_assoc(true);
			$amount = getBaseAmount($record['amount'], $record['currency']); ;
		}			
		return $amount;
	}
	
	static function markCommissionProcessed($code, $record_id){
		if($code == 'S'){
			$sql = "UPDATE `app_sales_invoice` SET `sales_invoice_commission_processed` = 1 WHERE `sales_invoice_id` = '$record_id'";
		}elseif($code == 'O'){
			$sql = "UPDATE `app_website_order` SET `web_order_commission_processed` = 1 WHERE `web_order_id` = '$record_id'";
		}
		if(isset($sql)){
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
		}		
	}
	
	function getEmployeeFilteration(){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(DISTINCT a.`sales_commission_batch`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`sales_commission_user_id` = b.`user_id` GROUP BY a.`sales_commission_user_id` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	
	static function getTotalSalesCommission(){
		$sql = "SELECT SUM(`sales_commission_amount`) AS amount, SUM(`sales_commission_percentage`) AS percentage, COUNT(`sales_commission_id`) as record FROM `app_sales_invoice_commission` WHERE `sales_commission_status` = '1'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);		
		$record = $dbc->db_fetch_assoc(true);
		$record['total'] = $record['amount'] ? round(($record['amount'] * 100) / ($record['percentage']/$record['record']), 2) : 0;
		$record['percentage'] = round($record['percentage']/$record['record'], 2);
		return $record;
	}
	
	function getCommissionDetails($sales_commission_batch){
		$sql = "SELECT a.`sales_commission_created_date`, a.`sales_commission_month`, a.`sales_commission_invoice_amount`, a.`sales_commission_percentage`, a.`sales_commission_amount`, b.`sales_invoice_number`, b.`sales_invoice_created_date` FROM `".$this->table_name."` AS a INNER JOIN `app_sales_invoice` AS b ON a.`sales_commission_sales_id` = b.`sales_invoice_id` AND a.`sales_commission_type_code` = 'S' WHERE a.`sales_commission_batch` = '$sales_commission_batch' 
		UNION 
		SELECT a.`sales_commission_created_date`, a.`sales_commission_month`, a.`sales_commission_invoice_amount`, a.`sales_commission_percentage`, a.`sales_commission_amount`, b.`web_order_number` AS sales_invoice_number, b.`web_order_created_date` AS sales_invoice_created_date FROM `".$this->table_name."` AS a INNER JOIN `app_website_order` AS b ON a.`sales_commission_sales_id` = b.`web_order_id` AND a.`sales_commission_type_code` = 'O' WHERE a.`sales_commission_batch` = '$sales_commission_batch'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
			$row['sales_invoice_date'] = dateView($row['sales_invoice_created_date'], 'DATE');
			$data[] = $row;
		}
		return $data;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_sales_invoice_commission`" => array(
															"column"=> array("`sales_commission_month`", "`sales_commission_created_date`", "SUM(`sales_commission_amount`) as commission_amount", "`sales_commission_batch`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_system_user`" => array(
															"column"=> array("user_id", "CONCAT(b.`user_fname`, ' ', b.`user_lname`) as user_name", "user_image"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_sales_invoice_commission`", "on"=>array("`user_id`" => "`sales_commission_user_id`"))
															),								
								
								),
					"ORDER"	=>array("user_fname", "user_fname", "`sales_commission_batch`", "`sales_commission_month`", "SUM(`sales_commission_amount`)", "`sales_commission_created_date`")						
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
		$this->groupby = array("a.`sales_commission_batch`");
			
		
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
				viewText($row["user_name"]),
				viewText($row["sales_commission_batch"]),
				date('M-y', strtotime($row['sales_commission_month'])),
				"&pound; ".$row["commission_amount"],								
				dateView($row["sales_commission_created_date"],"DATE"),
				"<div class=\"btn-group\">
					<button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
						<i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\"></span>
					</button>
					<div class=\"dropdown-menu dropdown-menu-right\">
						<a class=\"dropdown-item commissionsummary\" data-user-image=\"".getResizeImage($row["user_image"],32)."\" data-user-name=\"".viewText($row["user_name"])."\" data-commission-code=\"$row[sales_commission_batch]\" href=\"#\"><i class=\"fa fa-list fa-fw\"></i> Summary Details</a>
						<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[sales_commission_batch]|M', '$row[sales_commission_batch] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
					</div>
				</div>"								
			);
		}
		return json_encode($output);		
	}	
}



?>