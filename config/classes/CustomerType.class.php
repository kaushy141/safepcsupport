<?php
class CustomerType extends DB{
	public $id;
	public $key_id	=	"customer_type_id";
	public $table_name	=	"app_customer_type";
	public $status	=	"customer_type_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}
	
	
	function getDetails()
	{
		//$hardware_id!=NULL?$this->hardware_id=$hardware_id:"";
		$sql="SELECT `customer_type_id`, `customer_type_name`, `customer_type_created_date`, `customer_type_status` FROM `app_customer_type` WHERE `customer_type_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($result);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}	
	
	function getOptions($customer_type_id=NULL)
	{
		$sql="SELECT `customer_type_id`, `customer_type_name`, `customer_type_created_date`, `customer_type_status` FROM `app_customer_type` WHERE 1 ORDER BY `customer_type_name`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Customer Type - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[customer_type_id]\" ".(($customer_type_id==$row['customer_type_id'])?"selected":"")." >".$row['customer_type_name']."</option>";					
		}
		return $html;	
	}
	
}
?>