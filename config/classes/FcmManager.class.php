<?php
class FcmManager extends DB{
	public $id;
	public $key_id		= "fcm_con_id";
	public $table_name	= "app_fcm_connection";
	public $status		= "fcm_con_status";
	public $user_id	= 0;	
	public $customer_id	= 0;	
	function __construct($fcm_con_id=0){
				
		$this->id	=	$fcm_con_id;
	}
	
	function setFCMCustomer($fcm_con_customer_id)
	{
		$fcm_con_user_id 		= getLoginId();	
		$this->customer_id = $fcm_con_customer_id;
		$sql = "SELECT `fcm_con_id`, `fcm_con_user_id`, `fcm_con_customer_id` FROM `app_fcm_connection` WHERE 1=1 ORDER BY `fcm_con_time` DESC LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = $dbc->db_fetch_assoc(true);
		
		if(isset($data['fcm_con_customer_id']) && $data['fcm_con_customer_id']==$fcm_con_customer_id && $data['fcm_con_user_id']==$fcm_con_user_id)
		return $data['fcm_con_id'];
		else
		{	
			$sql="INSERT INTO `app_fcm_connection`(`fcm_con_user_id`, `fcm_con_customer_id`, `fcm_con_time`, `fcm_con_status`) VALUES ('$fcm_con_user_id', '$fcm_con_customer_id', NOW(), '1')";
			$dbc 	= 	new DB();
			$dbc->db_query($sql);
			return $dbc->db_insert_id();
		}		
	}
	
	function getFCMUser()
	{
		$sql="SELECT a.`fcm_con_user_id` FROM `".$this->table_name."` as a WHERE a.`fcm_con_customer_id` = '".$this->customer_id."'" ;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_fetch_assoc(true))		
			return $data['fcm_con_user_id'];
		else
			return false;
	}
	function getFCMToken()
	{
		if(getUserType()=="E")
		$sql="SELECT `customer_fcm_token` as token FROM `app_customer` WHERE `customer_id` = '".$this->customer_id."'" ;
		else
		$sql="SELECT a.`user_fcm_token` as token FROM `app_system_user` as a LEFT JOIN `".$this->table_name."` as b ON a.`user_id` = b.`fcm_con_user_id` WHERE b.`fcm_con_customer_id` = '".$this->customer_id."' AND b.`fcm_con_status`='1' ORDER BY b.`fcm_con_time` DESC, a.`user_fcm_token` DESC LIMIT 1" ;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_fetch_assoc(true))		
			return $data['token'];
		else
			return false;
	}
}
?>