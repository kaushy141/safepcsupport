<?php
class WcItemType extends DB{
	public $id;	
	public $key_id	=	"wci_type_id";
	public $table_name	=	"app_wci_type_master";
	public $status	=	"wci_type_status";
	function __construct($wci_type_id=0){
				
		$this->id	=	$wci_type_id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT `wci_type_id`, `wci_type_name`, `wci_type_created_date`, `wci_type_status` FROM `".$this->table_name."` WHERE `wci_type_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function getOptions($wci_type_id=NULL)
	{
		$sql="SELECT `wci_type_id`, `wci_type_name`, `wci_type_created_date`, `wci_type_status` FROM `".$this->table_name."` WHERE 1 ORDER BY `wci_type_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Collection Item Type - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[wci_type_id]\" ".(($wci_type_id==$row['wci_type_id'])?"selected":"")." >".$row['wci_type_name']."</option>";					
		}
		return $html;	
	}	
}
?>