<?php
class Credentials extends DB{
	public $id;
	public $key_id	=	"credentials_id";
	public $table_name	=	"app_login_credentials";
	public $status	=	"credentials_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	
	function getMyCredentialsList(){
		$sql = "SELECT a.* , b.user_fname, b.user_lname FROM app_login_credentials as a LEFT JOIN `app_system_user` as b ON a.`credentials_owner` = b.`user_id` WHERE a.`credentials_owner` = '$_SESSION[user_id]' OR a.`credentials_scope` = 'Public' AND `credentials_status`= '1' ";	
		$record_array = array();
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		while($row = $dbc->db_fetch_assoc()){
			$record_array[] = $row;
		}
		return $record_array;
	}
	
	function canEdit(){
		$sql = "SELECT `credentials_id` FROM `app_login_credentials` WHERE `credentials_id` = '".$this->id."' AND `credentials_owner` = '$_SESSION[user_id]'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows() ? true: false;
	}
}
?>