<?php
class SoftwareAssets extends DB{
	public $id;
	public $key_id	=	"soft_asset_id";
	public $table_name	=	"app_software_asset_info";
	public $status	=	"soft_asset_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}
	
	static function getAssetTag(){
		$sql = "SELECT COUNT(`".$this->key_id."`) as num, NOW() as currenttime FROM `".$this->table_name."` WHERE 1";
		$dbc 	= 	new DB();		
		$result	=	$dbc->db_query($sql);		
		$numcountrow = $dbc->db_fetch_assoc();		
		$numcount = $numcountrow['num']+1;		
		return strtoupper(date("ym", strtotime($numcountrow['currenttime']))."S".sprintf("%04d",$numcount));	
	}
	
	function add($soft_asset_type, $soft_asset_version, $soft_asset_reg_code, $soft_asset_allocated_install_to, $soft_asset_licence_number, $soft_asset_owner, $soft_asset_used_by, $soft_asset_managed_by, $soft_asset_location, $soft_asset_overview, $soft_asset_risk, $soft_asset_cia, $soft_asset_impact, $soft_asset_risk_rating, $soft_asset_suspect_vulnerabilities, $soft_asset_action_plan, $soft_asset_key_security_control, $soft_asset_potential_action, $soft_asset_status)
	{
		$sql="INSERT INTO `app_software_asset_info`(`soft_asset_type`, `soft_asset_version`, `soft_asset_reg_code`, `soft_asset_allocated_install_to`, `soft_asset_licence_number`, `soft_asset_owner`, `soft_asset_used_by`, `soft_asset_managed_by`, `soft_asset_location`, `soft_asset_overview`, `soft_asset_risk`, `soft_asset_cia`, `soft_asset_impact`, `soft_asset_risk_rating`, `soft_asset_suspect_vulnerabilities`, `soft_asset_action_plan`, `soft_asset_key_security_control`, `soft_asset_potential_action`, `soft_asset_created_date`, `soft_asset_status`) VALUES
		 ('$soft_asset_type', '$soft_asset_version', '$soft_asset_reg_code', '$soft_asset_allocated_install_to', '$soft_asset_licence_number', '$soft_asset_owner', '$soft_asset_used_by', '$soft_asset_managed_by', '$soft_asset_location', '$soft_asset_overview', '$soft_asset_risk', '$soft_asset_cia', '$soft_asset_impact', '$soft_asset_risk_rating', '$soft_asset_suspect_vulnerabilities', '$soft_asset_action_plan', '$soft_asset_key_security_control', '$soft_asset_potential_action', NOW(), '$soft_asset_status')";	
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
								"`app_software_asset_info`" => array(
															"column"=> array("`soft_asset_id`", "`soft_asset_type`", "`soft_asset_owner`", "`soft_asset_used_by`", "`soft_asset_location`", "`soft_asset_created_date`"),
															"reference" => "a",
															"join" => NULL
															)
								
								),
					"ORDER"	=>array("`soft_asset_created_date`","`soft_asset_type`", "`soft_asset_owner`", "`soft_asset_used_by`", "`soft_asset_location`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->condition = array(
									//array("a.`soft_asset_id`","=",1)									
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
		while($row = $dbc->db_fetch_assoc()){							
			$output["data"][] = array(	
								dateView($row["soft_asset_created_date"], "DATE"),
								viewText($row["soft_asset_type"]),
								viewText($row["soft_asset_owner"]),
								viewText($row["soft_asset_used_by"]),
								viewText($row["soft_asset_location"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    ".(isAdmin()?	
    ("<a class=\"dropdown-item redirect\" href=\"".$app->basePath("updatesoftwareasset/".$row['soft_asset_id'])."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	<a class=\"dropdown-item\" target=\"new\" href=\"".DOC::SOFTASSETS($row['soft_asset_id'])."\"><i class=\"fa fa-print fa-fw text-info\"></i> Print</a>
	<a class=\"dropdown-item\" href=\"#\" onclick=\"removeSoftwareAsset($row[soft_asset_id])\"><i class=\"fa fa-trash-o fa-fw text-danger\"></i> Delete</a>"):"")." </div></div>",
	$row["soft_asset_id"]
			);
		}
		return json_encode($output);		
	}
	
}
?>