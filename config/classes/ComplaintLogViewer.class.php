<?php
class ComplaintLogViewer extends DB{
	public $id;
	public $key_id	=	"log_view_id";
	public $table_name	=	"app_complaint_log_viewer";
	function __construct($log_view_id=0){
				
		$this->id	=	$log_view_id;
	}
	
	function add($complaint_id, $complaint_format)
	{
		$condition = getUserType()=="E"?" `complaint_user_id` = '0' ":" `complaint_customer_id` = '0' ";
		$sql="UPDATE `app_complaint_log` SET `is_readed` = '1' WHERE `complaint_id` = '$complaint_id' AND `complaint_format` = '$complaint_format' AND $condition ";
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		
		$log_view_user_id 	= getLoginId();
		$log_view_user_type = getUserType();
		$sql="INSERT INTO `app_complaint_log_viewer`(`complaint_id`, `complaint_format`, `log_view_user_id`, `log_view_user_type`, `log_view_time`) VALUES ('$complaint_id',  '$complaint_format', '$log_view_user_id', '$log_view_user_type', NOW())";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return $dbc->db_insert_id();		
	}
		
	
	
}
?>