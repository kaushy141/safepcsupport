<?php
class userType extends DB{
	public $id;
	public $key_id	=	"user_type_id";
	public $table_name	=	"app_user_type";
	public $status	=	"user_type_status";	
	function __construct($user_type_id=0){
				
		$this->id	=	$user_type_id;
	}
	
	
	function getDetails()
	{
		//$hardware_id!=NULL?$this->hardware_id=$hardware_id:"";
		$sql="SELECT `user_type_id`, `user_type_name`, `user_type_created_date`, `user_type_module_permission`, `user_type_status` FROM `app_user_type` WHERE `user_type_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function getOptions($user_type_id=NULL)
	{
		$sql="SELECT `user_type_id`, `user_type_name`, `user_type_created_date`, `user_type_status` FROM `app_user_type` WHERE 1 ORDER BY `user_type_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select user Type - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[user_type_id]\" ".(($user_type_id==$row['user_type_id'])?"selected":"")." >".$row['user_type_name']."</option>";					
		}
		return $html;	
	}
	
}
?>