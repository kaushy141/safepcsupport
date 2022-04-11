<?php
class Returns extends DB{
	public $id;
	public $key_id	=	"return_id";
	public $table_name	=	"app_return_record";
	public $status	=	"return_status";
	private static $section_code = "E";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function getReturnByCode($return_code){
		$sql = "SELECT * FROM `app_return_record` WHERE `return_code` = '$return_code'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows() == 1){
			return $dbc->db_fetch_assoc(true);
		}
		return $data;
	}
	
	function getReturnCode($module_code){
		$sql = "SELECT `return_code` as code FROM `".$this->table_name."` WHERE YEAR(`return_created_date`) = '".date("Y")."' ORDER BY `return_created_date` DESC LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$chrStr = "0000";
		if($dbc->db_num_rows() == 1){
			$data = $dbc->db_fetch_assoc(true);
			$chrStr = substr($data['code'], 4, 4);
		}
		$chrStr++;
		return date("y").self::$section_code.$module_code.sprintf("%04d",$chrStr);
	}
	
	function getReturnLog(){
		$comlaint = new ComplaintLog();
		return $comlaint->getLog($this->id, self::$section_code);
	}
	
	function getDetails(){
		$sql = "SELECT a.*, c.user_fname, c.user_lname, c.user_image FROM `".$this->table_name."` AS a    
		LEFT JOIN `app_system_user` AS c ON a.`return_added_by` = c.`user_id`   
		
		WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function isReturnExist($return_module_code, $return_module_id, $nonCancelled=false){
		$condition = $nonCancelled ? "AND return_status != '3'" : "";
		$sql = "SELECT `return_id` FROM `".$this->table_name."` WHERE `return_module_code` = '$return_module_code' AND `return_module_id` = '$return_module_id' $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){
			$record = $dbc->db_fetch_assoc(true);
			return $record['return_id'];
		}
		else
			return 0;
	}
	
	function getReturnByModule($return_module_code, $return_module_id){
		if($return_id = $this->isReturnExist($return_module_code, $return_module_id)){
			$return = new Returns($return_id);
			return $return->getDetails();
		}
		else
			return null;
	}
	
	function getAllReturn($return_module_code, $return_module_id){
		$sql = "SELECT a.*, c.user_fname, c.user_lname, c.user_image FROM `".$this->table_name."` AS a    
		LEFT JOIN `app_system_user` AS c ON a.`return_added_by` = c.`user_id`  		
		WHERE `return_module_code` = '$return_module_code' AND `return_module_id` = '$return_module_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){
			while($data = $dbc->db_fetch_assoc(true))
			$result_array[] = $data;
		}
		return $result_array;
	}
		
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_return_record`" => array(
															"column"=> array("`return_id`", "`return_code`", "`return_reference`", "`return_module_code`", "`return_reference`", "`return_created_date`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_activity_section`" => array(
															"column"=> array("activity_section_name"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_return_record`", "on"=>array("`activity_section_code`" => "`return_module_code`"))
															)
								),
					"ORDER"	=>array("return_code", "customer_fname", "`activity_section_name`", "`return_amount`", "`return_pattern`", "`wc_status_name`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					foreach($values as $_val)
						$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($_val));
				}
				else
					$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($values));
				$conditionArray[] = $filedCondArray;	
			}
		}
		$this->condition = $conditionArray;
													
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`return_id`");
			
		
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
								viewText($row["return_code"]),
								viewText($row["activity_section_name"])."<br/>".$row["return_reference"],		
								$row["return_reference"],
								$row["return_reference"] ? "Returned":"Return Cancelled",						
								dateView($row["return_created_date`"],"DATE"),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
	<a class=\"dropdown-item redirect\" href=\"viewreturn/".md5($row['return_id'])."\"><i class=\"fa fa-truck fa-fw\"></i> View</a>Credit Note</a>
    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[return_id]|".self::$section_code."', '$row[return_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a></div></div>",
								"viewreturn/".md5($row['return_id']),
								$row['wc_status_color_code']
								
			);
		}
		return json_encode($output);		
	}
			
	function getAddedFilteration(){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`return_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`return_added_by` = b.`user_id` GROUP BY a.`return_added_by` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	
	function getReturnNotice($label = 'Label'){
		$returnData = $this->getDetails();
		return "<div class=\"col-xs-12 col-sm-12 col-md-12 pt-1\">
    <div class=\"alert alert-warning card\" role=\"alert\">
      <h4 class=\"alert-heading\"><img class=\"img img-circle\" src=\"".getResizeImage($returnData['user_image'], 32)."\"> &nbsp; Return Notice! <b>#{$returnData['return_code']}</b></h4>
      <p><b>{$returnData['user_fname']} {$returnData['user_lname']}</b> admitted a return for this <b>{$label}</b> on ".dateView($returnData['return_created_date'], 'FULL')." Return value dated <b>".dateView($returnData['return_date'], 'FULL')."</b></p>
      <p class=\"mb-0\"><b>Return Remark : </b> <i>\"{$returnData['return_remark']}</i>\"</p>
    </div>
  </div>";
	}
	
}
?>