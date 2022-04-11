<?php
class ChecklistReport extends DB{
	public $id;
	public $key_id	=	"checklist_id";
	public $table_name	=	"app_checklist_report";
	public $status	=	"checklist_status";	
	function __construct($checklist_id=0){
				
		$this->id	=	$checklist_id;
	}	
		
	function add($checklist_employee_name, $checklist_name, $checklist_origin, $checklist_file, $checklist_status)
	{
		$sql="INSERT INTO `app_checklist_report`(`checklist_employee_name`, `checklist_name`, `checklist_created_date`, `checklist_origin`, `checklist_file`, `checklist_status`) VALUES ('$checklist_employee_name', '$checklist_name', NOW(), '$checklist_origin', '$checklist_file', '$checklist_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	
	function getCheckListTypeCount($checklist_name){
		$sql="SELECT * FROM `app_checklist_report` WHERE LOWER(`checklist_name`) = LOWER('$checklist_name')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result);
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_checklist_report`" => array(
															"column"=> array("`checklist_id`", "`checklist_employee_name`", "`checklist_name`", "`checklist_created_date`", "checklist_origin", "checklist_file", "`checklist_status`"),
															"reference" => "a",
															"join" => NULL
															)
								
								),
					"ORDER"	=>array("a.`checklist_origin`, a.`checklist_created_date`","a.`checklist_origin`, a.`checklist_name`", "a.`checklist_origin`, a.`checklist_employee_name`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
										
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
			
		$this->condition = array(
									array("a.`checklist_status`","=",1)									
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
								dateView($row["checklist_created_date"], "DATE"),
								viewText($row["checklist_name"]),
								viewText($row["checklist_employee_name"]),
								$row['checklist_origin']==0 ? "<a class=\"btn\" target=\"new\" href=\"".$app->basePath($row['checklist_file'])."\"><i class=\"text-primary fa fa-cloud-download fa-2x\"></i></a>":"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Option</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    ".((isAdmin()|| isAdminAccess())?	
    ("<a class=\"dropdown-item redirect\" href=\"".$app->basePath("updatechecklistreport/".$row['checklist_id'])."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>	
	<a class=\"dropdown-item\" target=\"new\" href=\"".$app->basePath($row['checklist_file'])."\"><i class=\"text-primary fa fa-cloud-download fa-fw\"></i> Download</a>
	<a class=\"dropdown-item\" href=\"#\" onclick=\"removeChecklistReport($row[checklist_id])\"><i class=\"text-center icon-close fa-fw text-danger\"></i> Remove</a>"):"")." </div></div>",
								$row['checklist_id'],
								$row['checklist_origin']
			);
		}
		return json_encode($output);		
	}
	
}

class ChecklistItem extends DB{
	public $id;
	public $key_id	=	"checklist_item_id";
	public $table_name	=	"app_checklist_items";
	public $status	=	"checklist_item_status";	
	function __construct($checklist_id=0){
				
		$this->id	=	$checklist_id;
	}	
		
	function add($checklist_employee_name, $checklist_name, $checklist_origin, $checklist_file, $checklist_status)
	{
		$sql="INSERT INTO `app_checklist_report`(`checklist_employee_name`, `checklist_name`, `checklist_created_date`, `checklist_origin`, `checklist_file`, `checklist_status`) VALUES ('$checklist_employee_name', '$checklist_name', NOW(), '$checklist_origin', '$checklist_file', '$checklist_status')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	
	function getCheckListItems($checklist_item_for){
		$sql="SELECT `checklist_item_id`, `checklist_item_name`, `checklist_item_category` FROM `app_checklist_items` WHERE `checklist_item_for` = '$checklist_item_for' ORDER BY `checklist_item_order`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){							
				$record_array[] = $row;
		}
		return $record_array;	
	}
	
	function getCheckListItemsByEic($checklist_item_for, $eic_id){
		$sql="SELECT a.`checklist_item_id`, a.`checklist_item_name`, a.`checklist_item_category`,  b.`eici_checklist_item_id`, b.`eici_comment`, b.`eici_completed_date`, b.`eici_completed` FROM `app_checklist_items` as a 
		LEFT JOIN `app_employee_inducation_checklist_item` as b ON (a.`checklist_item_id` = b.`eici_checklist_item_id` AND b.`eici_eic_id` = '$eic_id') WHERE a.`checklist_item_for` = '$checklist_item_for' ORDER BY a.`checklist_item_order`";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){							
				$record_array[] = $row;
		}
		return $record_array;	
	}
	
	function getCheckListItemsByEil($checklist_item_for, $eil_id){
		$sql="SELECT a.`checklist_item_id`, a.`checklist_item_name`, a.`checklist_item_category`,  b.`eili_checklist_item_id`, b.`eili_comment`, b.`eili_completed_date`, b.`eili_completed` FROM `app_checklist_items` as a 
		LEFT JOIN `app_employee_leaver_checklist_item` as b ON (a.`checklist_item_id` = b.`eili_checklist_item_id` AND b.`eili_eil_id` = '$eil_id') WHERE a.`checklist_item_for` = '$checklist_item_for' ORDER BY a.`checklist_item_order`";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){							
				$record_array[] = $row;
		}
		return $record_array;	
	}
	
}
?>