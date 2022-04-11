<?php
class DeviceManager extends DB{
	public $id;
	public $key_id		= "fab_id";
	public $table_name	= "app_firebase_device_master";
	public $status		= "fab_is_active";
	public $fab_user_id	= 0;
	public $fab_user_type = "";
	function __construct($key_id=0){
						
		$this->id	=	$key_id;
		return $this;
	}
	
	function setUser($fab_user_id=0, $fab_user_type = NULL){
						
		$this->fab_user_id	 =	$fab_user_id;
		$this->fab_user_type =  $fab_user_type==NULL?getUserType():$fab_user_type;
		return $this;
	}
	
	function addDevice($fab_code_key, $fab_device_name="")
	{	
		$fab_user_id	= $this->fab_user_id;	
		$fab_user_type	= $this->fab_user_type;	
		$fab_is_active = 1;
		$fab_ip_address	= IP_ADDRESS;
		$sql="INSERT INTO `app_firebase_device_master`(`fab_user_id`, `fab_user_type`, `fab_code_key`, `fab_device_name`, `fab_is_active`, `fab_created_date`, `fab_ip_address`) VALUES ('$fab_user_id', '$fab_user_type', '$fab_code_key', '$fab_device_name', '$fab_is_active', NOW(), '$fab_ip_address')";
			$dbc 	= 	new DB();
			$dbc->db_query($sql);
			return $dbc->db_insert_id();				
	}	
	
	function checkUserDevice($fab_code_key)
	{			
		$sql="SELECT `fab_id`, `fab_user_id`, `fab_user_type`, `fab_code_key`, `fab_device_name`, `fab_is_active`, `fab_created_date` FROM `app_firebase_device_master` WHERE `fab_code_key` = '$fab_code_key' AND `fab_is_active` = '1'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows())
		{
			return $dbc->db_fetch_assoc(true);
		}
		return 	false;	
	}
	
	function getUserDevice($fab_user_id = NULL , $fab_user_type = NULL )
	{
		$fui = $fab_user_id 	== NULL ? getLoginId()	: $fab_user_id;
		$fut = $fab_user_type	== NULL ? getUserType()	: $fab_user_type;
		$sql="SELECT GROUP_CONCAT(`fab_code_key`) as keys FROM `app_firebase_device_master` WHERE `fab_user_id` = '$fui' AND `fab_user_type` = '$fab_user_type' AND `fab_is_active` = '1'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows())
		{
			$record =  $dbc->db_fetch_assoc(true);
			return $record['keys'];
		}
		return 	false;	
	}	
	
	function getAllUserDevice()
	{
		$sql="SELECT GROUP_CONCAT(a.`fab_code_key`) as firebase_ids FROM `app_firebase_device_master` as a  INNER JOIN `app_system_user` AS b ON a.`fab_user_id` = b.`user_id` WHERE a.`fab_is_active` = '1'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows())
		{
			$record =  $dbc->db_fetch_assoc(true);
			return $record['firebase_ids'];
		}
		return 	false;	
	}	
}
?>