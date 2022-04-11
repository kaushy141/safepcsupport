<?php
class Location extends DB{
	public $id;
	public $key_id	=	"location_id";
	public $table_name	=	"app_user_location";
	public $status	=	"loation_is_expired";	
	public $user_id	=	0;
		
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
	
	function expireMyLocation($location_user_id){
		$sql = "UPDATE `".$this->table_name."` SET `loation_is_expired` = '1' WHERE `location_user_id` = '".$location_user_id."'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getDetailsByUser($location_user_id, $limit=NULL)
	{
		$fetch_limit = $limit == NULL ? 1: $limit;
		$sql="SELECT * FROM `".$this->table_name."` WHERE `location_user_id` = '".$location_user_id."' ORDER BY `loation_save_time` DESC LIMIT $fetch_limit";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$location_array = array();
		if($dbc->db_num_rows($result))	
		{
			while($row = $dbc->db_fetch_assoc())		
			$location_array[] = $row;
			return $limit == NULL ? $location_array[0] : $location_array;
		}
		else
			return false;
	}
	
	function getAllUsersLiveLocation($timezone = NULL){
		global $app;
		$sql="SELECT a.`location_lat_lng`, a.`loation_save_time`, b.user_id, b.user_fname, b.user_lname, b.user_image FROM `".$this->table_name."` as a INNER JOIN `app_system_user` as b ON a.`location_user_id` = b.`user_id` WHERE a.loation_is_expired = '0' AND b.`user_status` = '1' GROUP BY b.`user_id` ";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$location_array = array();
		if($dbc->db_num_rows($result))	
		{
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['user_fname'] = limitText($row['user_fname'], 12);
				$row['user_image'] = getResizeImage($row['user_image'], 50);
				$row['loation_save_time_view'] = date("h:mA T", strtotime($row['loation_save_time']));
				$row['location_lat_lng'] = stripcslashes($row['location_lat_lng']);
				$location_array[] = $row;
			}
			return $location_array;
		}
		else
			return NULL;
	}
}
?>