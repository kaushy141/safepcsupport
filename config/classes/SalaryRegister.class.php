<?php
class SalaryRegister extends DB{
	public $id;
	public $key_id	=	"pay_slip_id";
	public $table_name	=	"app_user_pay_slip";
	public $status	=	"pay_slip_paid_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
					
	function getDetails()
	{
		$sql="SELECT * FROM `".$this->table_name."` WHERE  `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
	function getDetailsByUser($user_id)
	{
		$sql="SELECT * FROM `".$this->table_name."` WHERE  `user_pay_user_id` = '$user_id' ORDER BY `".$this->key_id."` DESC LIMIT 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
	
	function getEmployeePayRecords(){
		$sql = "SELECT a.`pay_slip_id`, a.`pay_slip_month_id`, a.`pay_slip_grant_pay`, a.`pay_slip_paid_status` , CONCAT(u.`user_fname`, ' ', u.`user_lname`) as user_name, b.`user_pay_joining_date`, u.`user_id`,  u.`user_status`
		FROM `app_system_user` as u 
		INNER JOIN `app_user_pay_setup` as b ON u.`user_id` = b.`user_pay_user_id` 
		LEFT JOIN `app_user_pay_slip` as a ON a.`pay_slip_user_id` = u.`user_id`
		WHERE 1 ORDER BY u.`user_status` DESC, u.`user_fname`";
		//a.`pay_slip_month_id` > DATE_FORMAT(DATE_SUB(CURRENT_DATE, INTERVAL ".MAX_SALARY_REGISTER_VIEW." MONTH), '%Y%m')
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{	
		while($row = $dbc->db_fetch_assoc(true)){
			$result_array[$row['user_id']]['user'] = array("user_id"=>$row['user_id'], "user_name" =>$row['user_name'], "user_status" =>$row['user_status'], "user_pay_joining_date" =>$row['user_pay_joining_date']);
			$result_array[$row['user_id']]['pay'][$row['pay_slip_month_id']] = array("pay_slip_month_id"=>$row['pay_slip_month_id'], "pay_slip_grant_pay" =>$row['pay_slip_grant_pay'], "pay_slip_paid_status" =>$row['pay_slip_paid_status'], "pay_slip_id" =>$row['pay_slip_id']);
			}
		}
		return $result_array;
	}
	
	function isMprGenerated($user_id, $month_id){
		$sql = "SELECT * FROM `app_user_pay_slip` WHERE `pay_slip_user_id` = '$user_id' AND `pay_slip_month_id` = '$month_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)==1)
			return $dbc->db_fetch_assoc(true);
		else
			return NULL;
	}
	
}
?>