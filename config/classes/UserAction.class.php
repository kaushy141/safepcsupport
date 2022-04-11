<?php
class UserAction extends DB{
	public $id;
	public $key_id	=	"ua_id";
	public $table_name	=	"app_user_action";
	public $status = "ua_is_readed";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}	
		
	function isUserActionExist($ua_module_code, $ua_module_id, $ua_user_id){
		$sql = "SELECT `ua_id` FROM `".$this->table_name."` WHERE `ua_module_code` = '$ua_module_code' AND `ua_module_id` = '$ua_module_id' AND `ua_user_id` = '$ua_user_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result);
	}
	
	function removeAction($ua_module_code, $ua_module_id, $ua_user_id){
		$sql = "DELETE FROM `".$this->table_name."` WHERE `ua_module_code` = '$ua_module_code' AND `ua_module_id` = '$ua_module_id' AND `ua_user_id` = '$ua_user_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
	}	
	
	function getUserAction($offsetActionId = 0, $ua_user_id=0, $limit = 20){
		global $app;
		$ua_user_id = $ua_user_id ? $ua_user_id : getLoginId();
		$condition = $offsetActionId ? "  AND a.`ua_id` < $offsetActionId ":"";
		$sql = "SELECT a.`ua_id`, a.`ua_type`, a.`ua_module_code`, a.`ua_module_id`, a.`ua_time`, a.`ua_is_readed`, a.`ua_text`, a.`ua_log_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, c.`activity_section_name`, c.`activity_section_key`, c.`activity_section_color` FROM `app_user_action` AS a 
		INNER JOIN `app_system_user` AS b ON a.`ua_mentioner_id` = b.`user_id` 
		INNER JOIN `app_activity_section` AS c ON a.`ua_module_code` = c.`activity_section_code` 
		WHERE `ua_user_id` = '$ua_user_id' $condition ORDER BY a.`ua_id` DESC LIMIT $limit";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$result_array[] = $this->uaPretty($row);
			}
		}
		return array_reverse($result_array);
	}
	
	public static function markUserActionRead($ua_module_code=null, $ua_module_id=0, $ua_user_id = 0){
		$ua_user_id = $ua_user_id ? $ua_user_id : getLoginId();
		if($ua_module_code != null && $ua_module_id != 0 && $ua_user_id != 0){
			$sql = "UPDATE `app_user_action` SET `ua_is_readed` = '1', `ua_read_time` = NOW() WHERE `ua_module_code` = '$ua_module_code' AND `ua_module_id` = '$ua_module_id' AND `ua_user_id` = '$ua_user_id' AND `ua_is_readed` = '0'";
			$dbc 	= 	new DB();
			return $dbc->db_query($sql);
		}
		else
			return null;
	}
	
	static function saveModuleAction($ua_module_code, $ua_module_id, $uaUserArray, $ua_log_id = 0, $ua_text, $ua_type = 'uaged'){
	$userArray = UserAction::getModuleAction($ua_module_code, $ua_module_id, false);
	$savedActionUserIds = count($userArray) ? array_column($userArray, 'user_id') : array();
	$newUserActionIds = array_diff($uaUserArray, $savedActionUserIds);
	//$delUserActionIds = array_diff($savedActionUserIds, $uaUserArray);
	if(count($newUserActionIds)){
		foreach($newUserActionIds as $user_id){
			$userAction = new UserAction();
			$userAction->insert(array(
					"ua_mentioner_id" 	=> getLoginId(), 
					"ua_user_id" 		=> $user_id, 
					"ua_module_code"	=> $ua_module_code, 
					"ua_module_id"		=> $ua_module_id, 
					"ua_time"			=> 'NOW()', 
					"ua_is_readed"		=> 0, 
					"ua_type"			=> $ua_type, 
					"ua_text"			=> $ua_text,
					"ua_log_id"		=> $ua_log_id
				)
			);
		}
	}
	/*
	if(count($delUserActionIds)){
		foreach($newUserActionIds as $user_id){
			$userAction = new UserAction();
			$userAction->removeAction($ua_module_code, $ua_module_id, $user_id);
		}
	}
	*/
}

	
	public static function getLogAction($ua_log_id, $html = true){
		global $app;
		$sql = "SELECT a.`ua_read_time`, b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image` FROM `app_user_action` AS a 
		INNER JOIN `app_system_user` AS b ON a.`ua_user_id` = b.`user_id` 
		WHERE a.`ua_log_id` = '$ua_log_id' ORDER BY a.`ua_id`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['user_image'] = getResizeImage($row["user_image"],32);
				if($html){
					$result_array[] = "<div class=\"log_ua_box\"><img src=\"{$row['user_image']}\" class=\"img-avatar\" alt=\"{$row['user_fname']}\"> {$row['user_fname']} {$row['user_lname']} <span class=\"read_status\">".($row['ua_read_time'] != null ? ('<i title=\''.dateView($row['ua_read_time'], 'FULL').'('.dateView($row['ua_read_time'], 'NOW').')\' class=\'fa fa-check-circle text-info\'></i> ') : '<i title=\'Not Readed\' class=\'fa fa-check-circle text-muted\'></i>')."</span></div>";
				}
				else{
					$result_array[] = $row;
				}
			}
		}
		return $html ? (count($result_array) ? implode("&nbsp;",$result_array) : null) : array_reverse($result_array);
	}
	
	public static function getModuleAction($ua_module_code=null, $ua_module_id=0, $html = true){
		global $app;
		$sql = "SELECT a.`ua_read_time`, b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image` FROM `app_user_action` AS a 
		INNER JOIN `app_system_user` AS b ON a.`ua_user_id` = b.`user_id` 
		WHERE a.`ua_module_code` = '$ua_module_code' AND a.`ua_module_id` = '$ua_module_id' GROUP BY a.`ua_user_id` ORDER BY a.`ua_id`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['user_image'] = getResizeImage($row["user_image"],32);
				if($html){
					$result_array[] = "<div class=\"log_ua_box\"><img src=\"{$row['user_image']}\" class=\"img-avatar\" alt=\"{$row['user_fname']}\"> {$row['user_fname']} {$row['user_lname']} <span class=\"read_status\">".($row['ua_read_time'] != null ? ('<i title=\''.dateView($row['ua_read_time'], 'FULL').'('.dateView($row['ua_read_time'], 'NOW').')\' class=\'fa fa-check-circle text-info\'></i> ') : '<i title=\'Not Readed\' class=\'fa fa-check-circle text-muted\'></i>')."</span></div>";
				}
				else{
					$result_array[] = $row;
				}
			}
		}
		return $html ? (count($result_array) ? implode("&nbsp;",$result_array) : null) : array_reverse($result_array);
	}
	
	function getUserNewAction($offsetActionId = 0, $ua_user_id=0){
		global $app;
		$ua_user_id = $ua_user_id ? $ua_user_id : getLoginId();
		$condition = $offsetActionId ? "  AND a.`ua_id` > $offsetActionId ":"";
		$sql = "SELECT a.`ua_id`, a.`ua_type`,  a.`ua_module_code`, a.`ua_module_id`, a.`ua_time`, a.`ua_is_readed`, a.`ua_text`, a.`ua_log_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, c.`activity_section_name`, c.`activity_section_key`, c.`activity_section_color` FROM `app_user_action` AS a 
		INNER JOIN `app_system_user` AS b ON a.`ua_mentioner_id` = b.`user_id` 
		INNER JOIN `app_activity_section` AS c ON a.`ua_module_code` = c.`activity_section_code` 
		WHERE `ua_user_id` = '$ua_user_id' $condition ORDER BY a.`ua_id` DESC LIMIT 20";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$result_array[] = $this->uaPretty($row);
			}
		}
		return array_reverse($result_array);
	}
	
	private function uaPretty($row){
		global $app;
		$ua = array();
		$ua['ua_id'] = $row['ua_id'];
		$ua['ua_readed'] = $row['ua_is_readed'];
		$ua['activity_section_color'] = $row['activity_section_color'];
		$ua['user_image'] = getResizeImage($row["user_image"],32);
		$ua['ua_text'] = "{$row['user_fname']} {$row['user_lname']} {$row['ua_type']} you in a {$row['activity_section_name']} ";
		if($row['ua_module_code'] == 'A')
			$ua['ua_text'] .= "<a class='schedule_opener' data-id='{$row['ua_module_id']}' href='#'> #{$row['ua_text']}</a>";
		else
			$ua['ua_text'] .= "<a class='redirect' href='{$app->basePath($row['activity_section_key'])}/{$row['ua_module_id']}'> #{$row['ua_text']}</a>";
		$ua['ua_time'] = dateView($row['ua_time'], 'FULL');
		$ua['ua_log_modal'] = $row['ua_log_id'] ? "data-toggle=\"modal\" data-id=\"$row[ua_module_id]|$row[ua_module_code]\" data-heading=\"$row[activity_section_name] #$row[ua_text] Log\" data-target=\"#appModal\"" : null;
		return $ua;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				//`lot_id`, `lot_code`, `lot_name`, `lot_created_date`, `lot_created_by`, `lot_status`
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_user_action`" => array(
															"column"=> array("`ua_id`", "`ua_type`", "`ua_time`", "`ua_is_readed`", "`ua_module_id`", "`ua_module_code`", "`ua_text`", "`ua_log_id`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_system_user`" => array(
															"column"=> array("`user_fname`", "`user_lname`", "`user_image`"),
															"reference" => "c",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_user_action`", "on"=>array("`user_id`" => "`ua_mentioner_id`"))
															),
								"`app_activity_section`" => array(
															"column"=> array("`activity_section_name`", "`activity_section_key`", "`activity_section_color`"),
															"reference" => "d",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_user_action`", "on"=>array("`activity_section_code`" => "`ua_module_code`"))
															)
								),
					"ORDER"	=>array("user_fname", "ua_time", "`ua_text`", "`activity_section_name`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
															
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		//$this->groupby = array("a.`ua_id`");
		$this->condition = array(array("a.`ua_user_id`", "=", getLoginId()));
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
			$ua = $this->uaPretty($row);
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".$ua["user_image"]."\" height=\"40px\"></div>",
								dateView($row["ua_time"],"FULL"),
								$ua['ua_text'],
								viewText($row["activity_section_name"]),								
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">	
    <a class=\"dropdown-item comments_log_operner\" href=\"#\" {$ua['ua_log_modal']} \"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
	<a href=\"#\" data-id=\"{$row['ua_id']}\" class=\"dropdown-item ".($row['ua_is_readed'] == 1 ? 'uareaded':'markuaread')."\"><i class=\"fa fa-check-circle\"></i> ".($row['ua_is_readed'] == 1 ? "Readed":"Mark read")."</a></div></div>",
								$row['ua_id'],
								$row['ua_is_readed'] == 1 ? "#fffff":"#EAEAEA"
								
			);
		}
		return json_encode($output);		
	}
}
?>