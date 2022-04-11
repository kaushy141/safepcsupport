<?php
class Recharge extends DB{
	public $id;
	public $key_id	=	"recharge_trans_id";
	public $table_name	=	"app_refund_recharge_transaction";
	public $status	=	"recharge_trans_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function creditBalance($recharge_trans_user_id, $recharge_trans_amount)
	{	
		return $this->insert(array(
			'recharge_trans_code'=> $this->getRechargeTransCode(),
			'recharge_trans_type'=> 'Credit',
			'recharge_trans_user_id' => $recharge_trans_user_id,
			'recharge_trans_amount' => $recharge_trans_amount,
			'recharge_trans_by' => getLoginId(),
			'recharge_trans_date' => 'NOW()',
			'recharge_trans_status' => 1
		));	
	}
	
	function debitBalance($recharge_trans_user_id, $recharge_trans_amount)
	{	
		return $this->insert(array(
			'recharge_trans_code'=> $this->getRechargeTransCode(),
			'recharge_trans_type'=> 'Debit',
			'recharge_trans_user_id' => $recharge_trans_user_id,
			'recharge_trans_amount' => "-$recharge_trans_amount",
			'recharge_trans_by' => getLoginId(),
			'recharge_trans_date' => 'NOW()',
			'recharge_trans_status' => 1
		));	
	}
	
	function getRechargeTransCode(){
		$sql = "SELECT `recharge_trans_code` as code FROM `".$this->table_name."` WHERE YEAR(`recharge_trans_date`) = '".date("Y")."' ORDER BY `recharge_trans_date` DESC LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$chrStr = 0;
		if($dbc->db_num_rows() == 1){
			$data = $dbc->db_fetch_assoc(true);
			$chrStr = substr($data['code'], 3, 5);
		}
		$chrStr++;
		return date("y")."H".sprintf("%05d",$chrStr);
	}	
	
	function getUserCurrentBalance($recharge_trans_user_id){
		$sql = "SELECT SUM(`recharge_trans_amount`) as balance FROM `".$this->table_name."` WHERE `recharge_trans_user_id` = '$recharge_trans_user_id' AND `recharge_trans_status` = '1'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record = 	$dbc->db_fetch_assoc(true);
		return $record['balance'] ? $record['balance'] : 0;
	}
	
	function getTodaysRefundAmount($recharge_trans_user_id){
		$sql = "SELECT SUM(`recharge_trans_amount`) as balance FROM `".$this->table_name."` WHERE `recharge_trans_user_id` = '$recharge_trans_user_id' AND `recharge_trans_status` = '1' AND DATE(`recharge_trans_date`) = CURRENT_DATE AND `recharge_trans_type` = 'Debit'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record = 	$dbc->db_fetch_assoc(true);
		return $record['balance'] ? abs($record['balance']) : 0;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter){
				//`recharge_trans_id`, `recharge_trans_code`, `recharge_trans_type`, `recharge_trans_user_id`, `recharge_trans_amount`, `recharge_trans_by`, `recharge_trans_date`, `recharge_trans_status`
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_refund_recharge_transaction`" => array(
															"column"=> array("`recharge_trans_id`", "`recharge_trans_code`", "`recharge_trans_type`", "`recharge_trans_amount`", "`recharge_trans_date`", "`recharge_trans_status`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_system_user`" => array(
															"column"=> array("`user_fname`", "`user_lname`", "`user_image`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_refund_recharge_transaction`", "on"=>array("`user_id`" => "`recharge_trans_user_id`"))
															)
								),
					"ORDER"	=>array("user_fname", "user_fname", "recharge_trans_code", "`recharge_trans_amount`", "`recharge_trans_type`", "`recharge_trans_date`")						
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
		$this->groupby = array("a.`recharge_trans_id`");
			
		
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
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".getResizeImage($row["user_image"],50)."\" style=\"width:24px; height:24px;\"></div>",			
								viewText($row["user_fname"]. ' '.$row["user_lname"]),
								viewText($row["recharge_trans_code"]),
								viewText($row["recharge_trans_amount"]),
								$row["recharge_trans_type"],						
								dateView($row["recharge_trans_date"],"DATE"),
								$row['recharge_trans_type'] == 'Credit' ? '#6dc05c':'#fc564e'						
			);
		}
		return json_encode($output);		
	}
	
	function getTransactionUser(){
		$condition = ($_SESSION['user_type_id']==ADMIN_ROLE || isAdminAccess()) ? "":" AND a.`recharge_trans_user_id` = '".getLoginId()."'";
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`recharge_trans_id`) as record FROM `".$this->table_name."` AS a LEFT JOIN `app_system_user` AS b on a.`recharge_trans_user_id` = b.`user_id` WHERE 1 $condition GROUP BY a.`recharge_trans_user_id` ORDER BY b.`user_fname`";
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