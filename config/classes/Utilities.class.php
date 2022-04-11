<?php
class Utilities extends DB{
	public $id;
	public $key_id	=	"utility_id";
	public $table_name	=	"app_utilities";
	public $status	=	"utility_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}	
		
	function add($utility_description, $utility_used_by, $utility_managed_by, $utility_owner, $utility_location, $utility_risk, $utility_cia, $utility_impact, $utility_risk_rating, $utility_contract_number, $utility_person_to_contact, $utility_contact_number, $utility_overview, $utility_key_security_tool, $utility_potential_action, $utility_action_plan,  $utility_status)
	{
		$sql="INSERT INTO `app_utilities`(`utility_description`, `utility_used_by`, `utility_managed_by`, `utility_owner`, `utility_location`, `utility_risk`, `utility_cia`, `utility_impact`, `utility_risk_rating`, `utility_contract_number`, `utility_person_to_contact`, `utility_contact_number`, `utility_overview`, `utility_key_security_tool`, `utility_potential_action`, `utility_action_plan`, `utility_created_date`, `utility_status`) VALUES ('$utility_description', '$utility_used_by', '$utility_managed_by', '$utility_owner', '$utility_location', '$utility_risk', '$utility_cia', '$utility_impact', '$utility_risk_rating', '$utility_contract_number', '$utility_person_to_contact', '$utility_contact_number', '$utility_overview', '$utility_key_security_tool', '$utility_potential_action', '$utility_action_plan', NOW(), '$utility_status')";	
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
								"`app_utilities`" => array(
															"column"=> array("`utility_id`", "`utility_description`", "`utility_owner`", "`utility_used_by`", "`utility_location`", "`utility_created_date`", "`utility_doc_file`"),
															"reference" => "a",
															"join" => NULL
															)
								
								),
					"ORDER"	=>array("utility_created_date","`utility_description`", "`utility_owner`", "`utility_used_by`", "`utility_location`")						
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
								dateView($row["utility_created_date"], "DATE"),
								viewText($row["utility_description"]),
								viewText($row["utility_owner"]),
								viewText($row["utility_used_by"]),
								viewText($row["utility_location"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Option</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    ".((isAdmin() || isAdminAccess())?	
    ("<a class=\"dropdown-item redirect\" href=\"".$app->basePath("updateutilities/".$row['utility_id'])."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>"):"")." 
	<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['utility_doc_file'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Download Document</a>
	<a class=\"dropdown-item\" target=\"new\" href=\"".DOC::UTILITIES($row['utility_id'])."\"><i class=\"fa fa-print fa-fw text-info\"></i> Print</a></div></div>",
								$row['utility_id']
			);
		}
		return json_encode($output);		
	}
	
}
?>