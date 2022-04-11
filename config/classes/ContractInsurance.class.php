<?php
class ContractInsurance extends DB{
	public $id;
	public $key_id	=	"cont_ins_id";
	public $table_name	=	"app_contract_insurance";
	public $status	=	"cont_ins_status";	
	function __construct($customer_type_id=0){
				
		$this->id	=	$customer_type_id;
	}
		
	function add($cont_ins_description, $cont_ins_owner, $cont_ins_risk, $cont_ins_risk_prob, $cont_ins_risk_rating, $cont_ins_risk_treatment, $cont_ins_file_path, $cont_ins_status)
	{
		$sql="INSERT INTO `app_contract_insurance`(`cont_ins_description`, `cont_ins_owner`, `cont_ins_risk`, `cont_ins_risk_prob`, `cont_ins_risk_rating`, `cont_ins_risk_treatment`, `cont_ins_file_path`, `cont_ins_created_date`, `cont_ins_status`) 
		VALUES ('$cont_ins_description', '$cont_ins_owner', '$cont_ins_risk', '$cont_ins_risk_prob', '$cont_ins_risk_rating', '$cont_ins_risk_treatment', '$cont_ins_file_path', NOW(), '$cont_ins_status')";	
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
								"`app_contract_insurance`" => array(
															"column"=> array("`cont_ins_id`", "`cont_ins_description`", "`cont_ins_owner`", "`cont_ins_risk`", "`cont_ins_risk_rating`", "`cont_ins_risk_treatment`", "`cont_ins_file_path`", "`cont_ins_created_date`"),
															"reference" => "a",
															"join" => NULL
															)
								
								),
					"ORDER"	=>array("cont_ins_description","`cont_ins_created_date`", "`cont_ins_owner`", "`cont_ins_risk_rating`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->condition = array(
									array("a.`cont_ins_status`","=",1)									
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
								viewText($row["cont_ins_description"]),
								dateView($row["cont_ins_created_date"], "DATE"),
								viewText($row["cont_ins_owner"]),
								viewText($row["cont_ins_risk_rating"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
  <a class=\"dropdown-item\" target=\"new\" href=\"".$app->basePath($row['cont_ins_file_path'])."\"><i class=\"text-success fa fa-cloud-download fa-fw\"></i> Download</a> 
  <a class=\"dropdown-item\" target=\"new\" href=\"".DOC::CONTRACTINSURANCE($row['cont_ins_id'])."\"><i class=\"fa fa-print fa-fw text-primary\"></i> Print</a>
  <a class=\"dropdown-item\" href=\"#\" onclick=\"removeContract($row[cont_ins_id])\"><i class=\"text-center icon-close icons  fa-fw text-danger\"></i> Delete</a></div></div>",
								$row['cont_ins_id']
			);
		}
		return json_encode($output);		
	}
	
}
?>