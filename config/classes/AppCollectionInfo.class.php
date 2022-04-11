<?php
class AppCollectionInfo extends DB{
	public $id;
	public $key_id	=	"info_app_id";
	public $table_name	=	"aap_application_info";
	public $status	=	"info_app_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT `info_app_id`, `info_app_bank_details`, `info_app_address`, `info_app_contact`, `info_app_invoice_acknowledge`, `info_app_invoice_happytext`, `info_app_invoice_terms`,  `info_app_disclaimer`, `info_app_created_date`, `info_app_status` FROM `aap_application_info` WHERE 1 ORDER BY `info_app_created_date` DESC LIMIT 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}	
	
	function add($info_app_bank_details, $info_app_address, $info_app_contact, $info_app_invoice_acknowledge, $info_app_invoice_happytext, $info_app_invoice_terms, $info_app_disclaimer){
	
		$sql= "INSERT INTO `aap_application_info`(`info_app_bank_details`, `info_app_address`, `info_app_contact`, `info_app_invoice_acknowledge`, `info_app_invoice_happytext`, `info_app_invoice_terms`, `info_app_created_date`, `info_app_status`) VALUES ('$info_app_bank_details', '$info_app_address', '$info_app_contact', '$info_app_invoice_acknowledge', '$info_app_invoice_happytext', '$info_app_invoice_terms', '$info_app_disclaimer', NOW(), 1)";		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
}
?>