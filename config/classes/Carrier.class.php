<?php
class Carrier extends DB{
	public $id;
	public $key_id	=	"carrier_id";
	public $table_name	=	"app_wc_carrier";
	public $status	=	"carrier_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function setDefault()
	{
		$sql="UPDATE `".$this->table_name."` SET `carrier_is_default` = '0' WHERE 1";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		
		$sql="UPDATE `".$this->table_name."` SET `carrier_is_default` = '1' WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);				
	}
	
	
	function getOptions($carrier_id)
	{
		global $app;
		$sql="SELECT `carrier_id`, `carrier_name`, `carrier_logo_image`, `carrier_is_default` FROM `app_wc_carrier` WHERE `carrier_status` = '1'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select shipping carrier - </option>";			
		while($row = $dbc->db_fetch_assoc()){				
			$html.="<option value=\"$row[carrier_id]\" ".(($carrier_id!=0?(($carrier_id==$row['carrier_id'])?"selected":""):(($row['carrier_is_default']==1)?"selected":"")))." >".$row['carrier_name']."</option>";					
		}
		return $html;
	}	
	
	function add($carrier_name, $carrier_licence_number, $carrier_hazardous_waste_licence_number, $carrier_environment_number, $carrier_vehicle_reg_number, $carrier_address, $carrier_contact_number, $carrier_email, $carrier_fax_number, $carrier_logo_image, $carrier_is_default, $carrier_status, $carrier_created_by, $carrier_ip_address)
	{
		$sql="INSERT INTO `app_wc_carrier`(`carrier_name`, `carrier_licence_number`, `carrier_hazardous_waste_licence_number`, `carrier_environment_number`, `carrier_vehicle_reg_number`, `carrier_address`, `carrier_contact_number`, `carrier_email`, `carrier_fax_number`, `carrier_logo_image`, `carrier_is_default`, `carrier_status`, `carrier_created_by`, `carrier_created_date`, `carrier_ip_address`) VALUES ('$carrier_name', '$carrier_licence_number', '$carrier_hazardous_waste_licence_number', '$carrier_environment_number', '$carrier_vehicle_reg_number', '$carrier_address', '$carrier_contact_number', '$carrier_email', '$carrier_fax_number', '$carrier_logo_image', '$carrier_is_default', '$carrier_status', '$carrier_created_by', NOW(), '$carrier_ip_address')";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function getRecords($searchKeyword=NULL, $jsonFormat = false)
	{		
		global $app;		
		$sql="SELECT `carrier_id`, `carrier_name`, `carrier_licence_number`, `carrier_hazardous_waste_licence_number`, `carrier_environment_number`, `carrier_vehicle_reg_number`, `carrier_address`, `carrier_contact_number`, `carrier_email`, `carrier_fax_number`, `carrier_logo_image`, `carrier_is_default`, `carrier_status`, `carrier_created_by`, `carrier_created_date`, `carrier_ip_address` FROM `".$this->table_name."` WHERE 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html;	
		while($row = $dbc->db_fetch_assoc()){								
				$html="<tr class=\"".(!$row['carrier_status']?"danger":""). "\">";
				$html.="<td><img class=\"img-avatar\" src=\"".$app->imagePath($row['carrier_logo_image'])."\" width=\"32px\" /></td>";
				$html.="<td>".$row['carrier_name']."</td>";
				$html.="<td>".$row['carrier_email']."</td>";
				$html.="<td>".$row['carrier_contact_number']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".$row['carrier_address']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".$row['carrier_licence_number']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['carrier_created_date'])."</td>";
				$html.="<td>".statusView($row['carrier_id'], $row['carrier_status'])."</td>";
				$html.="<td>"."<a data-title=\"Update #".$row['carrier_name']." Carrier\" href=\"updatecarrier/".$row['carrier_id']."\" type=\"button\" class=\"btn btn-secondary redirect\"><i class=\"fa fa-lightbulb-o\"></i>&nbsp; View</a>"."</td>";
				$html.="</tr>";	
		}
		return $html;	
	}
	
	
	function getReportSql($format = NULL)
	{
		global $app;
		$image_path = $app->sitePath();
		$barcode_file = $app->basePath("qrcode.php?data=");
		if($format == NULL)
		return "SELECT `carrier_id`, `carrier_name`, `carrier_licence_number`, `carrier_hazardous_waste_licence_number`, `carrier_environment_number`, `carrier_vehicle_reg_number`, `carrier_address`, `carrier_contact_number`, `carrier_email_id`, `carrier_fax_number`, `carrier_is_default`, `carrier_status`, `carrier_created_by`, `carrier_created_date`, `carrier_ip_address` FROM `".$this->table_name."` WHERE 1";
	}

}
?>