<?php
class UserTag extends DB{
	public $id;
	public $key_id	=	"tag_id";
	public $table_name	=	"app_complaint_log_user_tag";
	public $status = "tag_is_readed";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}	
		
	function isTagExist($tag_module_code, $tag_module_id, $tag_user_id){
		$sql = "SELECT `tag_id` FROM `".$this->table_name."` WHERE `tag_module_code` = '$tag_module_code' AND `tag_module_id` = '$tag_module_id' AND `tag_user_id` = '$tag_user_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result);
	}
	
	static function getModuleParticipatedUser($tag_module_code, $tag_module_id){
		$sql = "SELECT GROUP_CONCAT(`tag_user_id`) AS tagged_user FROM `app_complaint_log_user_tag` WHERE `tag_module_code` = '$tag_module_code' AND `tag_module_id` = '$tag_module_id'";
		$dbc 	= 	new DB();
		$tagUserArray = array();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){
			$record = $dbc->db_fetch_assoc(true);
			if($record['tagged_user']) 
			{
				$tagUserArray = explode(',', $record['tagged_user']);
				$tagUserArray = array_unique($tagUserArray);
			}
		}		
		return $tagUserArray;
	}
	
	function removeTag($tag_module_code, $tag_module_id, $tag_user_id){
		$sql = "DELETE FROM `".$this->table_name."` WHERE `tag_module_code` = '$tag_module_code' AND `tag_module_id` = '$tag_module_id' AND `tag_user_id` = '$tag_user_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
	}	
	
	function getUserTag($offsetTagId = 0, $tag_user_id=0, $limit = 20){
		global $app;
		$tag_user_id = $tag_user_id ? $tag_user_id : getLoginId();
		$condition = $offsetTagId ? "  AND a.`tag_id` < $offsetTagId ":"";
		$sql = "SELECT a.`tag_id`, a.`tag_type`, a.`tag_module_code`, a.`tag_module_id`, a.`tag_time`, a.`tag_is_readed`, a.`tag_text`, a.`tag_log_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, c.`activity_section_name`, c.`activity_section_key`, c.`activity_section_color` FROM `app_complaint_log_user_tag` AS a 
		INNER JOIN `app_system_user` AS b ON a.`tag_mentioner_id` = b.`user_id` 
		INNER JOIN `app_activity_section` AS c ON a.`tag_module_code` = c.`activity_section_code` 
		WHERE `tag_user_id` = '$tag_user_id' $condition AND a.`tag_is_tagged` = '1' ORDER BY a.`tag_id` DESC LIMIT $limit";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$result_array[] = $this->tagPretty($row);
			}
		}
		return array_reverse($result_array);
	}
	
	public static function markUserTagRead($tag_module_code=null, $tag_module_id=0, $tag_user_id = 0){
		$tag_user_id = $tag_user_id ? $tag_user_id : getLoginId();
		if($tag_module_code != null && $tag_module_id != 0 && $tag_user_id != 0){
			$sql = "UPDATE `app_complaint_log_user_tag` SET `tag_is_readed` = '1', `tag_read_time` = NOW() WHERE `tag_module_code` = '$tag_module_code' AND `tag_module_id` = '$tag_module_id' AND `tag_user_id` = '$tag_user_id' AND `tag_is_readed` = '0'";
			$dbc 	= 	new DB();
			return $dbc->db_query($sql);
		}
		else
			return null;
	}
	
	public static function markUserTagAllRead($tag_user_id = 0){
		$tag_user_id = $tag_user_id ? $tag_user_id : getLoginId();
		if($tag_user_id != 0){
			$sql = "UPDATE `app_complaint_log_user_tag` SET `tag_is_readed` = '1', `tag_read_time` = NOW() WHERE `tag_user_id` = '$tag_user_id' AND `tag_is_readed` = '0' AND `tag_module_code` != 'Y'";
			$dbc 	= 	new DB();
			$result = $dbc->db_query($sql);
			return $dbc->getRowCount();
		}
		else
			return 0;
	}
	
	public static function markUserPaymentReminderAllRead($tag_user_id = 0){
		$tag_user_id = $tag_user_id ? $tag_user_id : getLoginId();
		if($tag_user_id != 0){
			$sql = "UPDATE `app_complaint_log_user_tag` SET `tag_is_readed` = '1', `tag_read_time` = NOW() WHERE `tag_user_id` = '$tag_user_id' AND `tag_is_readed` = '0' AND `tag_module_code` = 'Y'";
			$dbc 	= 	new DB();
			$result = $dbc->db_query($sql);
			return $dbc->getRowCount();
		}
		else
			return 0;
	}
	
	static function saveModuleTag($tag_module_code, $tag_module_id, $tagUserArray, $tag_log_id = 0, $tag_text, $tag_type = 'tagged'){
	$userArray = UserTag::getModuleTag($tag_module_code, $tag_module_id, false);
	$savedTagUserIds = count($userArray) ? array_column($userArray, 'user_id') : array();
	$newUserTagIds = array_diff($tagUserArray, $savedTagUserIds);
	//$delUserTagIds = array_diff($savedTagUserIds, $tagUserArray);
	if(count($newUserTagIds)){
		foreach($newUserTagIds as $user_id){
			$userTag = new UserTag();
			$userTag->insert(array(
					"tag_mentioner_id" 	=> getLoginId(), 
					"tag_user_id" 		=> $user_id, 
					"tag_module_code"	=> $tag_module_code, 
					"tag_module_id"		=> $tag_module_id, 
					"tag_time"			=> 'NOW()', 
					"tag_is_readed"		=> 0, 
					"tag_type"			=> $tag_type, 
					"tag_text"			=> $tag_text,
					"tag_log_id"		=> $tag_log_id,
					"tag_is_tagged"		=> 1
				)
			);
		}
	}
	/*
	if(count($delUserTagIds)){
		foreach($newUserTagIds as $user_id){
			$userTag = new UserTag();
			$userTag->removeTag($tag_module_code, $tag_module_id, $user_id);
		}
	}
	*/
}

	
	public static function getLogTag($tag_log_id, $html = true){
		global $app;
		$sql = "SELECT a.`tag_read_time`, b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image` FROM `app_complaint_log_user_tag` AS a 
		INNER JOIN `app_system_user` AS b ON a.`tag_user_id` = b.`user_id` 
		WHERE a.`tag_log_id` = '$tag_log_id' AND a.`tag_is_tagged` = '1' ORDER BY a.`tag_id`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['user_image'] = getResizeImage($row["user_image"],32);
				if($html){
					$result_array[] = "<div class=\"log_tag_box\"><img src=\"{$row['user_image']}\" class=\"img-avatar\" alt=\"{$row['user_fname']}\"> {$row['user_fname']} {$row['user_lname']} <span class=\"read_status\">".($row['tag_read_time'] != null ? ('<i title=\''.dateView($row['tag_read_time'], 'FULL').'('.dateView($row['tag_read_time'], 'NOW').')\' class=\'fa fa-check-circle text-info\'></i> ') : '<i title=\'Not Readed\' class=\'fa fa-check-circle text-muted\'></i>')."</span></div>";
				}
				else{
					$result_array[] = $row;
				}
			}
		}
		return $html ? (count($result_array) ? implode("&nbsp;",$result_array) : null) : array_reverse($result_array);
	}
	
	public static function getModuleTag($tag_module_code=null, $tag_module_id=0, $html = true){
		global $app;
		$sql = "SELECT a.`tag_read_time`, b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image` FROM `app_complaint_log_user_tag` AS a 
		INNER JOIN `app_system_user` AS b ON a.`tag_user_id` = b.`user_id` 
		WHERE a.`tag_module_code` = '$tag_module_code' AND a.`tag_module_id` = '$tag_module_id' AND a.`tag_is_tagged` = '1' GROUP BY a.`tag_user_id` ORDER BY a.`tag_id`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['user_image'] = getResizeImage($row["user_image"],32);
				if($html){
					$result_array[] = "<div class=\"log_tag_box\"><img src=\"{$row['user_image']}\" class=\"img-avatar\" alt=\"{$row['user_fname']}\"> {$row['user_fname']} {$row['user_lname']} <span class=\"read_status\">".($row['tag_read_time'] != null ? ('<i title=\''.dateView($row['tag_read_time'], 'FULL').'('.dateView($row['tag_read_time'], 'NOW').')\' class=\'fa fa-check-circle text-info\'></i> ') : '<i title=\'Not Readed\' class=\'fa fa-check-circle text-muted\'></i>')."</span></div>";
				}
				else{
					$result_array[] = $row;
				}
			}
		}
		return $html ? (count($result_array) ? implode("&nbsp;",$result_array) : null) : array_reverse($result_array);
	}
	
	function getUserNewTag($offsetTagId = 0, $tag_user_id=0){
		global $app;
		$tag_user_id = $tag_user_id ? $tag_user_id : getLoginId();
		$condition = $offsetTagId ? "  AND a.`tag_id` > $offsetTagId ":"";
		$sql = "SELECT a.`tag_id`, a.`tag_type`,  a.`tag_module_code`, a.`tag_module_id`, a.`tag_time`, a.`tag_is_readed`, a.`tag_text`, a.`tag_log_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, c.`activity_section_name`, c.`activity_section_key`, c.`activity_section_color` FROM `app_complaint_log_user_tag` AS a 
		INNER JOIN `app_system_user` AS b ON a.`tag_mentioner_id` = b.`user_id` 
		INNER JOIN `app_activity_section` AS c ON a.`tag_module_code` = c.`activity_section_code` 
		WHERE `tag_user_id` = '$tag_user_id' $condition ORDER BY a.`tag_id` DESC LIMIT 20";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows($result))
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$result_array[] = $this->tagPretty($row);
			}
		}
		return array_reverse($result_array);
	}
	
	private function tagPretty($row){
		global $app;
		$tag = array();
		$tag['tag_id'] = $row['tag_id'];
		$tag['tag_readed'] = $row['tag_is_readed'];
		$tag['activity_section_color'] = $row['activity_section_color'];
		$tag['user_image'] = getResizeImage($row["user_image"],32);
		$tag['tag_text'] = "{$row['user_fname']} {$row['user_lname']} {$row['tag_type']} you in a {$row['activity_section_name']} ";
		if($row['tag_module_code'] == 'A')
			$tag['tag_text'] .= "<a class='schedule_opener' data-id='{$row['tag_module_id']}' href='#'> #{$row['tag_text']}</a>";
		else
			$tag['tag_text'] .= "<a class='redirect' href='{$app->basePath($row['activity_section_key'])}/{$row['tag_module_id']}'> #{$row['tag_text']}</a>";
		$tag['tag_time'] = dateView($row['tag_time'], 'FULL');
		$tag['tag_log_modal'] = $row['tag_log_id'] ? "data-toggle=\"modal\" data-id=\"$row[tag_module_id]|$row[tag_module_code]\" data-heading=\"$row[activity_section_name] #$row[tag_text] Log\" data-target=\"#appModal\"" : null;
		return $tag;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				//`lot_id`, `lot_code`, `lot_name`, `lot_created_date`, `lot_created_by`, `lot_status`
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_complaint_log_user_tag`" => array(
															"column"=> array("`tag_id`", "`tag_type`", "`tag_time`", "`tag_is_readed`", "`tag_module_id`", "`tag_module_code`", "`tag_text`", "`tag_log_id`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_system_user`" => array(
															"column"=> array("`user_fname`", "`user_lname`", "`user_image`"),
															"reference" => "c",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_complaint_log_user_tag`", "on"=>array("`user_id`" => "`tag_mentioner_id`"))
															),
								"`app_activity_section`" => array(
															"column"=> array("`activity_section_name`", "`activity_section_key`", "`activity_section_color`"),
															"reference" => "d",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_complaint_log_user_tag`", "on"=>array("`activity_section_code`" => "`tag_module_code`"))
															)
								),
					"ORDER"	=>array("user_fname", "tag_time", "`tag_text`", "`activity_section_name`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
															
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		//$this->groupby = array("a.`tag_id`");
		$this->condition = array(array("a.`tag_user_id`", "=", getLoginId()));
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
			$tag = $this->tagPretty($row);
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".$tag["user_image"]."\" height=\"40px\"></div>",
								dateView($row["tag_time"],"FULL"),
								$tag['tag_text'],
								viewText($row["activity_section_name"]),								
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">	
    <a class=\"dropdown-item comments_log_operner\" href=\"#\" {$tag['tag_log_modal']} \"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>
	<a href=\"#\" data-id=\"{$row['tag_id']}\" class=\"dropdown-item ".($row['tag_is_readed'] == 1 ? 'tagreaded':'marktagread')."\"><i class=\"fa fa-check-circle\"></i> ".($row['tag_is_readed'] == 1 ? "Readed":"Mark read")."</a></div></div>",
								$row['tag_id'],
								$row['tag_is_readed'] == 1 ? "#fffff":"#EAEAEA"
								
			);
		}
		return json_encode($output);		
	}
}
?>