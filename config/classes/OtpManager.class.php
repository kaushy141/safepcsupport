<?php
class OtpManager extends DB{
	public $key_id		= "otp_id";
	public $table_name	= "app_otp_master";
	public $status		= "otp_status";
	public $otp_app_log_type = '';	
	public $customer_id	= 0;	
	function __construct($fcm_con_id=0){
				
		$this->key_id	=	$fcm_con_id;
		$this->otp_app_log_type = getUserType();
	}	
	
	function getOtpDetails($otp_code)
	{
		$otp_app_user 		= getLoginId();	
		$sql="SELECT `otp_id`, `otp_app_user`, `otp_app_log_type`, `otp_code`, `otp_created_date`, `otp_status` FROM `".$this->table_name."` WHERE `otp_app_log_type` = '".$this->otp_app_log_type."' AND `otp_app_user` = '$otp_app_user' ORDER BY `otp_created_date` DESC" ;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_mun_rows())		
			return $data;
		else
			return false;
	}
	function getOtp()
	{
		$otp_app_user = getLoginId();	
		$sql="SELECT `otp_code` FROM `".$this->table_name."` WHERE `otp_app_log_type` = '".$this->otp_app_log_type."' AND `otp_app_user` = '$otp_app_user' AND `otp_status` = '1' ORDER BY `otp_created_date` DESC" ;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_mun_rows())		
			return $data['otp_code'];
		else
		{
			$otp_code = rand(10000000,99999999);
			$sql = "INSERT INTO `app_otp_master`(`otp_app_user`, `otp_app_log_type`, `otp_code`, `otp_created_date`, `otp_status`) VALUES ('$otp_app_user', '".$this->otp_app_log_type."', '$otp_code', NOW(), '1')";
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			return $otp_code;
		}
	}
}
?>