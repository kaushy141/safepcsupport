<?php
class CarrierVehicle extends DB{
	public $id;
	public $key_id	=	"vehicle_id";
	public $table_name	=	"app_wc_carrier_vehicle";
	public $status	=	"vehicle_status";
		
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
	
					
	function getDetails()
	{
		$sql="SELECT `vehicle_id`, `vehicle_carrier_id`, `vehicle_registration_number`, `vehicle_name`, `vehicle_company`, `vehicle_model`, `vehicle_color`, `vehicle_created_date`, `vehicle_status` FROM `".$this->table_name."` WHERE  `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
	
	function getOptions($vehicle_id, $vehicle_carrier_id = 0)
	{
		global $app;
		$condition = $vehicle_carrier_id != 0 ? " AND `vehicle_carrier_id` = '$vehicle_carrier_id'":"";
		$sql="SELECT `vehicle_id`, CONCAT(`vehicle_name`, ' - ',  `vehicle_registration_number`, ' - ', `vehicle_company`, ' - ',  `vehicle_model`)  as vehicle_name_detail FROM `".$this->table_name."` WHERE (`vehicle_status` = '1' $condition) OR `vehicle_id` = '$vehicle_id'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select shipping Carrier Vehicle - </option>";			
		while($row = $dbc->db_fetch_assoc()){				
			$html.="<option value=\"$row[vehicle_id]\" ".(($vehicle_id==$row['vehicle_id'])?"selected":"")." >".$row['vehicle_name_detail']."</option>";					
		}
		return $html;
	}	
	
	function add($vehicle_carrier_id, $vehicle_registration_number, $vehicle_name, $vehicle_company, $vehicle_model, $vehicle_color, $vehicle_created_date, $vehicle_status)
	{
		$sql="INSERT INTO `app_wc_carrier_vehicle`(`vehicle_carrier_id`, `vehicle_registration_number`, `vehicle_name`, `vehicle_company`, `vehicle_model`, `vehicle_color`, `vehicle_created_date`, `vehicle_status`) VALUES ('$vehicle_carrier_id', '$vehicle_registration_number', '$vehicle_name', '$vehicle_company', '$vehicle_model', '$vehicle_color', CURRENT_TIMESTAMP, '$vehicle_status')";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function getRecords($searchKeyword=NULL, $jsonFormat = false)
	{		
		global $app;		
		$sql="SELECT a.`vehicle_id`, a.`vehicle_carrier_id`, a.`vehicle_registration_number`, a.`vehicle_name`, a.`vehicle_company`, a.`vehicle_model`, a.`vehicle_color`, a.`vehicle_created_date`, a.`vehicle_status`, b.`carrier_name`, b.`carrier_logo_image` FROM `app_wc_carrier_vehicle` as a INNER JOIN `app_wc_carrier` as b ON a.`vehicle_carrier_id` = b.`carrier_id` WHERE 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html;	
		while($row = $dbc->db_fetch_assoc()){								
				$html="<tr class=\"".(!$row['vehicle_status']?"danger":""). "\">";
				$html.="<td><img class=\"img-avatar\" src=\"".$app->imagePath($row['carrier_logo_image'])."\" width=\"32px\" /></td>";
				$html.="<td>".$row['vehicle_name']."</td>";
				$html.="<td>".$row['vehicle_registration_number']."</td>";
				$html.="<td>".$row['vehicle_company']." - ".$row['vehicle_model']." - ".$row['vehicle_color']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['vehicle_created_date'])."</td>";
				$html.="<td>".statusView($row['vehicle_id'], $row['vehicle_status'])."</td>";
				$html.="<td>"."<a data-title=\"Update #".$row['vehicle_name']." Vehicle\" href=\"updatevehicle/".$row['vehicle_id']."\" type=\"button\" class=\"btn btn-secondary redirect\"><i class=\"fa fa-lightbulb-o\"></i>&nbsp; View</a>"."</td>";
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
		return "SELECT a.`vehicle_id`, a.`vehicle_carrier_id`, a.`vehicle_registration_number`, a.`vehicle_name`, a.`vehicle_company`, a.`vehicle_model`, a.`vehicle_color`, a.`vehicle_created_date`, a.`vehicle_status`, b.`carrier_name`, b.`carrier_logo_image` FROM `app_wc_carrier_vehicle` as a INNER JOIN `app_wc_carrier` as b ON a.`vehicle_carrier_id` = b.`carrier_id` WHERE 1";
	}

}
?>