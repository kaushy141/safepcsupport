<?php
class Shipment extends DB{
	public $id;
	public $key_id	=	"shipment_id";
	public $table_name	=	"app_shipment_record";
	public $status	=	"shipment_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function getShipmentCode(){
		$sql = "SELECT `shipment_code` as code FROM `".$this->table_name."` WHERE YEAR(`shipment_created_date`) = '".date("Y")."' ORDER BY `shipment_created_date` DESC LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$chrStr = "AA00";
		if($dbc->db_num_rows() == 1){
			$data = $dbc->db_fetch_assoc(true);
			$chrStr = substr($data['code'], 3, 4);
			$chrStr++;
		}
		if(substr($chrStr, 2, 2) === "00")
		$chrStr++;
		return date("y")."S".$chrStr;
	}
	
	function getDetails(){
		$sql = "SELECT a.*, b.*, c.*, d.*, u.user_fname, u.user_lname, u.user_image FROM `".$this->table_name."` AS a 
		INNER JOIN `app_shipment_types` AS b on a.`shipment_type_id` = b.`shipment_type_id` 
		INNER JOIN `app_shipment_shipper` AS c on a.`shipment_shipper_id` = c.`shipment_shipper_id` 
		INNER JOIN `app_shipment_origin` AS d ON a.`shipment_origin_id` = d.`shipment_origin_id`
		INNER JOIN `app_system_user` AS u ON a.`shipment_created_by` = u.`user_id`  WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function markDownloader(){
		$download_shipment_id = $this->id;
		$download_user_id = getLoginId();
		$sql= "INSERT INTO `app_shipment_label_downloader`(`download_shipment_id`, `download_user_id`, `download_date`) VALUES ('$download_shipment_id','$download_user_id',NOW())";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
	}
	
	function getDownlaodRecords(){
		$download_shipment_id = $this->id;
		$sql= "SELECT a.`download_date`, u.user_fname, u.user_lname, u.user_image FROM `app_shipment_label_downloader` as a INNER JOIN `app_system_user` AS u ON a.`download_user_id` = u.`user_id` WHERE a.`download_shipment_id` = '$download_shipment_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html = '';	
		while($row = $dbc->db_fetch_assoc()){					
			$html .= "<tr><td><img class=\"img-avatar\" src=\"".getResizeImage($row["user_image"], 50)."\" height=\"40px\"></td>"
				."<td class=\"hidden-xs hidden-md hidden-sm visible-lg\">".$row["user_fname"]." ".$row["user_lname"]."</td>"
				."<td>".dateView($row['download_date'], "FULL")."</td></tr>";
					
		}
		return $html;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_shipment_record`" => array(
															"column"=> array("`shipment_id`", "`shipment_code`", "`shipment_total_charges`", "`shipment_to_name`", "`shipment_grand_total_charges`", "`shipment_charge_currency`", "`shipment_to_country_name`", "`shipment_tracking_number`",  "`shipment_created_date`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_shipment_types`" => array(
															"column"=> array("shipment_type_name", "shipment_type_image", "`shipment_type_color`"),
															"reference" => "b",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_shipment_record`", "on"=>array("`shipment_type_id`" => "`shipment_type_id`"))
															)
								),
					"ORDER"	=>array("shipment_code", "`shipment_type_name`", "`shipment_to_name`", "`shipment_total_charges`", "`shipment_tracking_number`", "`shipment_to_country_name`", "`shipment_created_date`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
													
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`shipment_id`");
			
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(		
								viewText($row["shipment_code"]),
								"<div class=\"avatar\"><img title=\"$row[shipment_type_name]\" class=\"img\" src=\"".getResizeImage($row["shipment_type_image"],40,25)."\" height=\"24px\"></div>",
								viewText($row["shipment_to_name"]),								
								viewText($row["shipment_total_charges"]. ' '.$row["shipment_charge_currency"]),		
								$row["shipment_tracking_number"],
								$row["shipment_to_country_name"],						
								dateView($row["shipment_created_date"],"DATE"),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
	<a class=\"dropdown-item redirect\" href=\"viewshipment/".$row['shipment_id']."\"><i class=\"fa fa-truck fa-fw\"></i> View</a>
    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[shipment_id]|T', '$row[shipment_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a></div></div>",
								"viewshipment/".$row['shipment_id']
								
			);
		}
		return json_encode($output);		
	}

	static function getUPSShipmentShipingService()
	{
		return  array(
			'01' => 'UPS Next Day Air',
			'02' => 'UPS 2nd Day Air',
			'03' => 'UPS Ground',
			'07' => 'UPS Worldwide Express',
			'08' => 'UPS Worldwide Expedited',
			'11' => 'UPS Standard',
			'12' => 'UPS 3 Day Select',
			'13' => 'UPS Next Day Air Saver',
			'14' => 'UPS Next Day Air Early',
			'54' => 'UPS Worldwide Express Plus',
			'59' => 'UPS 2nd Day Air A.M.',
			'65' => 'UPS Worldwide Saver',
			'70' => 'UPS Access Point Economy',
			'71' => 'UPS Worldwide Express Freight Midday',
			'74' => 'UPS Express 12:00',
			'82' => 'UPS Today Standard',
			'83' => 'UPS Today Dedicated Courrier',
			'85' => 'UPS Today Express',
			'86' => 'UPS Today Express Saver',
			'96' => 'UPS Worldwide Express Freight',
			'59' => 'UPS Second Day Air AM',
			'65' => 'UPS Saver',
			'70' => 'UPS Access Point Economy',
			'74' => 'UPS Express 12:00',
			'93' => 'UPS Sure Post',
		);
	}	
	static function getUPSShipmentShipingServiceName($code)
	{
		$list = Shipment::getUPSShipmentShipingService();
		return $list[$code];
	}	
}
?>