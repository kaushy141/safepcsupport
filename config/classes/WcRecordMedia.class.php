<?php
class WcRecordMedia extends DB{
	public $id;	
	public $key_id	=	"record_id";
	public $table_name	=	"app_wc_record_images";
	public $status	=	"record_status";
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT `record_id`, `wc_id`, `record_file_name`, `record_file_path`, `record_added_by`, `record_remark`, `record_created_date`, `record_is_deleted`, `record_deleted_by`, `record_status` FROM `".$this->table_name."` WHERE `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function add($wc_id, $record_file_name, $record_file_path, $record_added_by, $record_remark, $record_status)
	{
		$sql= "INSERT INTO `".$this->table_name."` (`wc_id`, `record_file_name`, `record_file_path`, `record_added_by`, `record_remark`, `record_created_date`, `record_status`) VALUES ('$wc_id', '$record_file_name', '$record_file_path', '$record_added_by', '$record_remark', NOW(), '$record_status')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
	function removeAll($wc_id)
	{
		$sql= "DELETE FROM `".$this->table_name."` WHERE `wc_id` = '$wc_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function remove()
	{
		$sql= "DELETE FROM `".$this->table_name."` WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getRecordList($wc_id)
	{
		$sql="SELECT a.`record_id`, a.`wc_id`, a.`record_file_name`, a.`record_file_path`, a.`record_added_by`, a.`record_remark`, a.`record_created_date`, a.`record_is_deleted`, a.`record_deleted_by`, a.`record_status`, CONCAT(c.`user_fname`, ' ', c.`user_lname`) as uploader_name  FROM `app_wc_record_images` as a INNER JOIN `app_wc_record` as b ON a.`wc_id` = b.`wc_id` LEFT JOIN `app_system_user` as c ON a.`record_added_by` = c.`user_id` WHERE a.`wc_id`='$wc_id' AND a.`record_is_deleted` = '0'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc(true))
			array_push($result_array, $row);
			return $result_array;
		}
		else
			return false;
	}
	
}
?>