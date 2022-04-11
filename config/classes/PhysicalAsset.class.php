<?php
class PhysicalAsset extends DB{
	public $id;
	public $key_id	=	"asset_id";
	public $table_name	=	"app_physical_asset_info";
	public $status	=	"asset_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}
	
		
	static function getAssetTag(){
		$sql = "SELECT COUNT(`asset_id`) as num, NOW() as currenttime FROM `app_physical_asset_info` WHERE 1";
		$dbc 	= 	new DB();		
		$result	=	$dbc->db_query($sql);		
		$numcountrow = $dbc->db_fetch_assoc();		
		$numcount = $numcountrow['num']+1;		
		return strtoupper(date("ym", strtotime($numcountrow['currenttime']))."P".sprintf("%04d",$numcount));	
	}
	
	function add($asset_desciption, $asset_manufaturer, $asset_model, $asset_serial_number, $asset_tag, $asset_processor, $asset_ram, $asset_size, $asset_owner, $asset_managed_by, $asset_used_by, $asset_location, $asset_overview, $asset_risk, $asset_cia, $asset_impact, $asset_risk_rating, $asset_suspect_vulnerabilities, $asset_action_plan, $asset_created_by,  $asset_status)
	{
		$sql="INSERT INTO `app_physical_asset_info`(`asset_desciption`, `asset_manufaturer`, `asset_model`, `asset_serial_number`, `asset_tag`, `asset_processor`, `asset_ram`, `asset_size`, `asset_owner`, `asset_managed_by`, `asset_used_by`, `asset_location`, `asset_overview`, `asset_risk`, `asset_cia`, `asset_impact`, `asset_risk_rating`, `asset_suspect_vulnerabilities`, `asset_action_plan`, `asset_created_by`, `asset_created_date`, `asset_status`) 
		VALUES ('$asset_desciption', '$asset_manufaturer', '$asset_model', '$asset_serial_number', '$asset_tag', '$asset_processor', '$asset_ram', '$asset_size', '$asset_owner', '$asset_managed_by', '$asset_used_by', '$asset_location', '$asset_overview', '$asset_risk', '$asset_cia', '$asset_impact', '$asset_risk_rating', '$asset_suspect_vulnerabilities', '$asset_action_plan', '$asset_created_by', NOW(), '$asset_status')";	
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
								"`app_physical_asset_info`" => array(
															"column"=> array("`asset_id`", "`asset_desciption`", "`asset_owner`", "`asset_used_by`", "`asset_location`", "`asset_created_date`"),
															"reference" => "a",
															"join" => NULL
															)
								
								),
					"ORDER"	=>array("asset_created_date","`asset_desciption`", "`asset_owner`", "`asset_used_by`", "`asset_location`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->condition = array(
									array("a.`asset_status`","=",1)									
								);									
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
								dateView($row["asset_created_date"], "DATE"),
								viewText($row["asset_desciption"]),
								viewText($row["asset_owner"]),
								viewText($row["asset_used_by"]),
								viewText($row["asset_location"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
  <a class=\"dropdown-item\" target=\"new\" href=\"".DOC::PHYCASSETS($row['asset_id'])."\"><i class=\"fa fa-print fa-fw text-info\"></i> Print</a>
    ".(isAdmin()?	
    ("<a class=\"dropdown-item redirect\" href=\"".$app->basePath("updatephysicalasset/".$row['asset_id'])."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	
	<a class=\"dropdown-item\" href=\"#\" onclick=\"removePhysicalAsset($row[asset_id])\"><i class=\"fa fa-trash-o fa-fw text-danger\"></i> Delete</a>"):"")." </div></div>"
			);
		}
		return json_encode($output);		
	}
	
}
?>