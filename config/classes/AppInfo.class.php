<?php
class AppInfo extends DB{
	public $id;
	public $key_id	=	"info_app_id";
	public $table_name	=	"aap_application_info";
	public $status	=	"info_app_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT * FROM `aap_application_info` WHERE 1 ORDER BY `info_app_created_date` DESC LIMIT 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}	
	
	function add($info_app_bank_details, $info_app_address, $info_app_contact, $info_app_invoice_acknowledge, $info_app_invoice_happytext, $info_app_invoice_terms, $info_app_disclaimer, $wc_carrier_certificate_text, $wc_consigner_certificate_text, $wc_carrier_licence_number, $wc_hazardous_waste_licence_number, $wc_environment_permit, $wc_vehicle_registration, $wc_authority_name, $wc_declaration_tex, $wc_collection_declaration_text){
	
		$sql= "INSERT INTO `aap_application_info`(`info_app_bank_details`, `info_app_address`, `info_app_contact`, `info_app_invoice_acknowledge`, `info_app_invoice_happytext`, `info_app_invoice_terms`, `info_app_disclaimer`, `wc_carrier_certificate_text`, `wc_consigner_certificate_text`, `wc_carrier_licence_number`, `wc_hazardous_waste_licence_number`, `wc_environment_permit`, `wc_vehicle_registration`, `wc_authority_name`, `wc_declaration_text`, `wc_collection_declaration_text`, `info_app_created_date`, `info_app_status`) VALUES ('$info_app_bank_details', '$info_app_address', '$info_app_contact', '$info_app_invoice_acknowledge', '$info_app_invoice_happytext', '$info_app_invoice_terms', '$info_app_disclaimer', '$wc_carrier_certificate_text', '$wc_consigner_certificate_text', '$wc_carrier_licence_number', '$wc_hazardous_waste_licence_number', '$wc_environment_permit', '$wc_vehicle_registration', '$wc_authority_name', '$wc_declaration_text', '$wc_collection_declaration_text', NOW(), 1)";		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
}
?>