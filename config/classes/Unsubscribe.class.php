<?php
class Unsubscribe extends DB{
	public $id;
	public $key_id	=	"id";
	public $table_name	=	"app_unsubscribe_user";
	public $status	=	"status";	
	function __construct($id=0){
				
		$this->id	=	$id;
	}
		
	function add($email, $resion, $remark, $ip_address){	
		$sql= "INSERT INTO `app_unsubscribe_user`(`email`, `resion`, `remark`, `ip_address`, `created_date`, `status`) VALUES ('$email', '$resion', '$remark', '$ip_address', NOW(), '1')";		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
}
?>