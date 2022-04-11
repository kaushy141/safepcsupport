<?php
class Contract extends DB{
	public $id;
	public $key_id	=	"user_pay_id";
	public $table_name	=	"app_user_pay_setup";
	public $status	=	"user_pay_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
					
	function getDetails()
	{
		$sql="SELECT * FROM `".$this->table_name."` WHERE  `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	function getDetailsByUser($user_id)
	{
		$sql="SELECT * FROM `".$this->table_name."` WHERE  `user_pay_user_id` = '$user_id' ORDER BY `".$this->key_id."` DESC LIMIT 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
}
?>