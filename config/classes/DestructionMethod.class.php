<?php
class DestructionMethod extends DB{
	public $id;
	public $key_id	=	"eqipment_disposal_id";
	public $table_name	=	"app_equipment_dosposal";
	public $status	=	"eqipment_disposal_status";	
	function __construct($eqipment_disposal_id=0){
				
		$this->id	=	$eqipment_disposal_id;
	}
	
	static function getDestructionLogNumber(){
		$sql = "SELECT COUNT(`eqipment_disposal_id`) as num, NOW() as currenttime FROM `app_equipment_dosposal` WHERE 1";
		$dbc 	= 	new DB();		
		$result	=	$dbc->db_query($sql);		
		$numcountrow = $dbc->db_fetch_assoc();		
		$numcount = $numcountrow['num']+1;		
		return strtoupper(date("ym", strtotime($numcountrow['currenttime']))."D".sprintf("%04d",$numcount));	
	}	
	
	function add($eqipment_disposal_hardware_id, $eqipment_disposal_manufacturer, $eqipment_disposal_model, $eqipment_disposal_serial_number, $eqipment_disposal_log_no, $eqipment_disposal_disposed_to, $eqipment_disposal_reason, $eqipment_disposal_destruction_method, $eqipment_disposal_destroyed_date, $eqipment_disposal_destroyed_by, $eqipment_disposal_created_user_id,  $eqipment_disposal_status)
	{
		$sql="INSERT INTO `app_equipment_dosposal`(`eqipment_disposal_hardware_id`, `eqipment_disposal_manufacturer`, `eqipment_disposal_model`, `eqipment_disposal_serial_number`, `eqipment_disposal_log_no`, `eqipment_disposal_disposed_to`, `eqipment_disposal_reason`, `eqipment_disposal_destruction_method`, `eqipment_disposal_destroyed_date`, `eqipment_disposal_destroyed_by`, `eqipment_disposal_created_user_id`, `eqipment_disposal_created_date`, `eqipment_disposal_status`) VALUES ('$eqipment_disposal_hardware_id', '$eqipment_disposal_manufacturer', '$eqipment_disposal_model', '$eqipment_disposal_serial_number', '$eqipment_disposal_log_no', '$eqipment_disposal_disposed_to', '$eqipment_disposal_reason', '$eqipment_disposal_destruction_method', '$eqipment_disposal_destroyed_date', '$eqipment_disposal_destroyed_by', '$eqipment_disposal_created_user_id', NOW(), '$eqipment_disposal_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_equipment_dosposal`" => array(
															"column"=> array("`eqipment_disposal_id`", "`eqipment_disposal_manufacturer`", "`eqipment_disposal_model`", "`eqipment_disposal_serial_number`", "`eqipment_disposal_log_no`", "`eqipment_disposal_destroyed_date`", "`eqipment_disposal_destroyed_by`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_hardware_type`" => array(
															"column"=> array("`hardware_name`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_equipment_dosposal`", "on"=>array("`hardware_id`" => "`eqipment_disposal_hardware_id`"))
															)
								
								),
					"ORDER"	=>array("eqipment_disposal_destroyed_date", "`eqipment_disposal_log_no`", "`hardware_name`", "`eqipment_disposal_model`", "`eqipment_disposal_destroyed_by`")						
							  );
		
		$this->searchKeyword = $searchKeyword;								
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;			
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		$baseUrl = $app->basePath();	
		while($row = $dbc->db_fetch_assoc()){							
			$output["data"][] = array(	
								dateView($row["eqipment_disposal_destroyed_date"], "DATE"),
								viewText($row["eqipment_disposal_log_no"]),
								viewText($row["hardware_name"]),
								viewText($row["eqipment_disposal_model"]),
								viewText($row["eqipment_disposal_destroyed_by"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
  <a class=\"dropdown-item\" target=\"new\" href=\"".DOC::EQUIPMENTDISPOSAL($row['eqipment_disposal_id'])."\"><i class=\"fa fa-print fa-fw text-info\"></i> Print</a>
    ".(isAdmin()?	
    ("<a class=\"dropdown-item redirect\" href=\"".$app->basePath("updatedestructionmethod/".$row['eqipment_disposal_id'])."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	<a class=\"dropdown-item\" href=\"#\" onclick=\"removeDisposalDestructionLog($row[eqipment_disposal_id])\"><i class=\"fa fa-trash-o fa-fw text-danger\"></i> Delete</a>"):"")." </div></div>"
			);
		}
		return json_encode($output);		
	}	
	
}
?>