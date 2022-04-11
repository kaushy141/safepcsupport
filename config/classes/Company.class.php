<?php
class Company extends DB{
	public $id;
	public $key_id	=	"company_id";
	public $table_name	=	"app_company_record";
	public $status	=	"company_status";	
	function __construct($company_id=0){
				
		$this->id	=	$company_id;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_company_record`" => array(
															"column"=> array("`company_id`", "`company_name`", "`company_address`", "`company_trademark`", "`company_status`", "`company_created_date`"),
															"reference" => "a",
															"join" => NULL
															),								
								),
					"ORDER"	=>array("`company_name`", "`company_address`", "`company_created_dae`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`company_id`");			
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		$num_rows_total= $this->Count();	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".$app->imagePath($row["company_trademark"])."\" height=\"40px\"><span class=\"avatar-status badge-".($row['company_status']?"success":"danger")."\"></span></div>",
								viewText($row["company_name"]),
								viewText($row["company_address"]),
								dateView($row["company_created_date"],'DATE'),
								statusView($row["company_id"],$row["company_status"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item redirect\" href=\"".$app->basePath("updatecompany/".$row['company_id'])."\"><i class=\"fa fa-edit fa-fw\"></i> Update</a>
	
	<a class=\"dropdown-item\" target=\"new\" href=\"".DOC::COMPANY($row['company_id'])."\"><i class=\"fa fa-print fa-fw text-primary\"></i> Print</a>
  </div>
</div>",
								$row["company_id"]

			);
		}
		return json_encode($output);
	}
}
?>