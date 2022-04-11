<?php
class Rfc extends DB{
	public $id;
	public $key_id	=	"rfc_id";
	public $table_name	=	"app_rfc_record";
	public $status	=	"rfc_status";	
	function __construct($eqipment_disposal_id=0){
				
		$this->id	=	$eqipment_disposal_id;
	}
	
	static function getRfcNumber(){
		$sql = "SELECT COUNT(`rfc_id`) as num, NOW() as currenttime FROM `app_rfc_record` WHERE 1";
		$dbc 	= 	new DB();		
		$result	=	$dbc->db_query($sql);		
		$numcountrow = $dbc->db_fetch_assoc();		
		$numcount = $numcountrow['num']+1;		
		return strtoupper(date("ym", strtotime($numcountrow['currenttime']))."RC".sprintf("%04d",$numcount));	
	}	
	
	function add($rfc_code, $rfc_require_by_date, $rfc_date_of_request, $rfc_serial_number, $rfc_requester, $rfc_circulation_list, $rfc_request_details, $rfc_request_reason, $rfc_service_owner_approval, $rfc_back_out_paln, $rfc_not_approved_reason, $rfc_completion_acceptance, $rfc_signature, $rfc_name, $rfc_date, $rfc_status)
	{
		$sql="INSERT INTO `app_rfc_record`(`rfc_code`, `rfc_require_by_date`, `rfc_date_of_request`, `rfc_serial_number`, `rfc_requester`, `rfc_circulation_list`, `rfc_request_details`, `rfc_request_reason`, `rfc_service_owner_approval`, `rfc_back_out_paln`, `rfc_not_approved_reason`, `rfc_completion_acceptance`, `rfc_signature`, `rfc_name`, `rfc_date`, `rfc_created_date`, `rfc_status`) VALUES('$rfc_code', '$rfc_require_by_date', '$rfc_date_of_request', '$rfc_serial_number', '$rfc_requester', '$rfc_circulation_list', '$rfc_request_details', '$rfc_request_reason', '$rfc_service_owner_approval', '$rfc_back_out_paln', '$rfc_not_approved_reason', '$rfc_completion_acceptance', '$rfc_signature', '$rfc_name', '$rfc_date', NOW(), '$rfc_status')";	
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
								"`app_rfc_record`" => array(
															"column"=> array("`rfc_id`", "`rfc_request_details`", "`rfc_requester`", "`rfc_code`", "`rfc_date_of_request`"),
															"reference" => "a",
															"join" => NULL
															)
								
								),
					"ORDER"	=>array("`rfc_date_of_request`", "`rfc_request_details`", "`rfc_requester`", "`rfc_code`")						
							  );
		
		$this->searchKeyword = $searchKeyword;								
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;			
		$this->condition = array(
									array("a.`rfc_status`","=","1"),
									//array(
										//array("user_fname","=","Kaushal"),
										//array("user_type_name","=","technician")
										//),
									//array("user_address","like","1")
									
								);		
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
								dateView($row["rfc_date_of_request"], "DATE"),
								viewText($row["rfc_request_details"]),
								viewText($row["rfc_requester"]),
								viewText($row["rfc_code"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
  <a class=\"dropdown-item\" target=\"new\" href=\"".DOC::RFCDOC($row['rfc_id'])."\"><i class=\"fa fa-print fa-fw text-info\"></i> Print</a>
    ".(isAdmin()?	
    ("<a class=\"dropdown-item redirect\" href=\"".$app->basePath("updaterfc/".$row['rfc_id'])."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
	<a class=\"dropdown-item\" href=\"#\" onclick=\"removeRfc($row[rfc_id])\"><i class=\"fa fa-trash-o fa-fw text-danger\"></i> Delete</a>"):"")." </div></div>"
			);
		}
		return json_encode($output);		
	}	
	
}
?>