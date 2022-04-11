<?php
class WcStatus extends DB{
	public $id;	
	public $key_id	=	"wc_status_id";
	public $table_name	=	"app_wc_status";
	public $status	=	"wc_status_status";
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT `wc_status_id`, `wc_status_name`, `wc_status_created_date`, `wc_status_color_code`, `wc_status_status` FROM `app_wc_status` WHERE `wc_status_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function getOptions($wc_status_id=NULL)
	{
		$sql="SELECT `wc_status_id`, `wc_status_name`, `wc_status_created_date`, `wc_status_color_code`, `wc_status_status` FROM `app_wc_status` WHERE 1 ORDER BY `wc_status_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Status - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[wc_status_id]\" ".(($wc_status_id==$row['wc_status_id'])?"selected":"")." >".$row['wc_status_name']."</option>";					
		}
		return $html;	
	}
	
}
?>