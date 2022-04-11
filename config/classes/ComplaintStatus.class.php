<?php
class ComplaintStatus extends DB{
	public $id;	
	public $key_id	=	"complaint_status_id";
	public $table_name	=	"app_complaint_status_master";
	public $status	=	"complaint_status";
	function __construct($complaint_status_id=0){
				
		$this->id	=	$complaint_status_id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT `complaint_status_id`, `complaint_status_name`, `complaint_status_created_date`, `complaint_status` FROM `app_complaint_status_master` WHERE `complaint_status_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function getOptions($complaint_status_id=NULL)
	{
		$sql="SELECT `complaint_status_id`, `complaint_status_name`, `complaint_status_created_date`, `complaint_status` FROM `app_complaint_status_master` WHERE 1 ORDER BY `complaint_status_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Status - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[complaint_status_id]\" ".(($complaint_status_id==$row['complaint_status_id'])?"selected":"")." >".$row['complaint_status_name']."</option>";					
		}
		return $html;	
	}
	
}
?>