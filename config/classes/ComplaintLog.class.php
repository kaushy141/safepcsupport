<?php
class ComplaintLog extends DB{
	public $id;
	public $key_id		= "complaint_log_id";
	public $table_name	= "app_complaint_log";
	public $status		= "complaint_log_status";
	public $customer_id	= 0;	
	public $complaint_id	= 0;	
	public $complaint_format = '';
	public $thumbnailSize = 32;
	function __construct($complaint_log_id=0){
				
		$this->id	=	$complaint_log_id;
	}
	
	function add($complaint_id, $complaint_format, $complaint_log_text, $complaint_log_type, $complaint_log_status, $complaint_log_privacy = 0)
	{
		$complaint_user_id 		= USER=="ADMIN"?getLoginId():0;
		$complaint_customer_id 	= USER=="ADMIN"?0:getLoginId();
		
		$sql="INSERT INTO `app_complaint_log`(`complaint_id`, `complaint_format`, `complaint_customer_id`, `complaint_user_id`, `complaint_log_text`, `complaint_log_type`, `complaint_log_time`, `complaint_log_status`, `is_readed`, `complaint_log_privacy`) VALUES ('$complaint_id', '$complaint_format', '$complaint_customer_id', '$complaint_user_id', '$complaint_log_text', '$complaint_log_type', NOW(), '$complaint_log_status', '0', '$complaint_log_privacy')";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return $dbc->db_insert_id();		
	}
	
	static function getModuleParticipatedUser($complaint_format, $complaint_id){
		global $app;
		$sql="SELECT GROUP_CONCAT(`complaint_user_id`) AS tagged_user FROM `app_complaint_log` WHERE `complaint_format` = '$complaint_format' AND `complaint_id` = '$complaint_id'";
		
		$dbc 	= 	new DB();
		$tagUserArray = array();
		$result	=	$dbc->db_query($sql);
		$record = $dbc->db_fetch_assoc(true);
		if($record['tagged_user']) 
		{
			$tagUserArray = explode(',', $record['tagged_user']);
			$tagUserArray = array_unique($tagUserArray);
		}		
		return $tagUserArray;
	}
	
	function getLog($complaint_id, $complaint_format, $complaint_log_id=NULL)
	{
		global $app;
		$condition=$complaint_log_id==NULL ? "":" AND a.`complaint_log_id` = '$complaint_log_id'";
		$condition.= isCustomer()? " AND (a.`complaint_log_privacy` = '1' OR `complaint_user_id` = 0)":"";
		$sql="SELECT a.`complaint_user_id`, a.`complaint_log_id`, a.`complaint_log_text`, a.`complaint_log_media`,  a.`complaint_log_time`, a.`complaint_log_privacy`, a.`complaint_log_reply_parent`, a.`complaint_notified_all`, CASE WHEN a.`complaint_user_id` = '0' THEN CONCAT(c.`customer_fname`, ' ', c.`customer_lname`) ELSE CONCAT(b.`user_fname`, ' ', b.`user_lname`) END as logger_name, CASE WHEN a.`complaint_user_id` = '0' THEN c.customer_image ELSE b.user_image END as logger_image, CASE WHEN a.`complaint_user_id` = '0' THEN 'C' ELSE 'E' END as logger_type FROM `app_complaint_log` as a LEFT JOIN `app_system_user` as b ON a.`complaint_user_id` = b.`user_id` LEFT JOIN `app_customer` as c ON a.`complaint_customer_id` = c.`customer_id` WHERE a.`complaint_id` = '$complaint_id' AND a.`complaint_format` = '$complaint_format' $condition ORDER BY a.`complaint_log_time` DESC, a.`complaint_log_reply_parent`";	
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){		
			while($row = $dbc->db_fetch_assoc(true)){
				$row['log_time'] = dateView($row['complaint_log_time'],'NOWDATE');
				$row['timestamp'] = strtotime($row['complaint_log_time']);
				$row['logger_image'] = getResizeImage($row['logger_image'], 50);
				//$row['complaint_log_media_thumb'] = $row['complaint_log_media'] ? getResizeImage($row['complaint_log_media'], $this->thumbnailSize) : null;
				$row['complaint_log_text_raw'] = $row['complaint_log_text'];
				$row['complaint_log_media'] = $row['complaint_log_media'] ? $app->basePath($row['complaint_log_media']) : null;
				$row['complaint_log_text'] = applyAnchor(Emoji::mapIcon(trim(nl2br(viewText($row['complaint_log_text'])), '<br/>')));
				$row['complaint_log_tag'] = UserTag::getLogTag($row['complaint_log_id'], true);
				
				$result_array[] = $row;
			}
			return array_reverse($result_array);
		}
		else
			return false;
	}
			
	function getCustomerLog($offset_id=0)
	{
		global $app;
		$condition = $offset_id!=0? " AND a.`complaint_log_id` > '$offset_id'":"";
		$condition.= isCustomer()? " AND a.`complaint_log_privacy` = '0'":"";
		$sql="SELECT a.`complaint_log_id`, a.`complaint_log_text`, a.`complaint_log_media`,  a.`complaint_log_time`, a.`complaint_log_reply_parent`, a.`complaint_notified_all`, CASE WHEN a.`complaint_user_id` = '0' THEN CONCAT(c.`customer_fname`, ' ', c.`customer_lname`) ELSE CONCAT(b.`user_fname`, ' ', b.`user_lname`) END as logger_name, CASE WHEN a.`complaint_user_id` = '0' THEN c.customer_image ELSE b.user_image END as logger_image, CASE WHEN a.`complaint_user_id` = '0' THEN 'C' ELSE 'E' END as logger_type 
		FROM `app_complaint_log` as a
		LEFT JOIN `app_system_user` as b ON a.`complaint_user_id` = b.`user_id` 
		LEFT JOIN `app_customer` as c ON a.`complaint_customer_id` = c.`customer_id` WHERE a.`complaint_id` = '".$this->complaint_id."' AND a.`complaint_format` = '".$this->complaint_format."' $condition ORDER BY a.`complaint_log_time` DESC , a.`complaint_log_reply_parent`";	
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){		
			while($row = $dbc->db_fetch_assoc()){
				$row['log_time'] = dateView($row['complaint_log_time'],'NOWDATE');
				$row['logger_image'] = $app->imagePath($row['logger_image']);
			//$row['complaint_log_media_thumb'] = $row['complaint_log_media'] ? getResizeImage($row['complaint_log_media'], $this->thumbnailSize) : null;
				$row['complaint_log_media'] = $row['complaint_log_media'] ? $app->basePath($row['complaint_log_media']) : null;
				$row['complaint_log_text'] = applyAnchor(Emoji::mapIcon(trim(nl2br(viewText($row['complaint_log_text'])), '<br/>')));		
				
				$result_array[] = $row;
			}
			return array_reverse($result_array);
		}
		else
			return false;
	}
	
	function getCustomerMessageNotification()
	{
		global $app;
		$sql="SELECT a.`complaint_log_id`, a.`complaint_log_text`, a.`complaint_log_media`, a.`complaint_id`, a.`complaint_format`, a.`complaint_log_time`, a.`complaint_log_reply_parent`, a.`complaint_notified_all`, CONCAT(c.`customer_fname`, ' ', c.`customer_lname`) as logger_name, c.customer_image as logger_image FROM `app_complaint_log` as a INNER JOIN `app_customer` as c ON (a.`complaint_customer_id` = c.`customer_id` AND a.`complaint_customer_id` != 0 AND a.`is_readed` = '0') ORDER BY a.`complaint_log_time` DESC , a.`complaint_log_reply_parent`";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){		
			while($row = $dbc->db_fetch_assoc(true)){
				$row['complaint_log_time'] = dateView($row['complaint_log_time'],'NOW')." ".date("d-M h:i A", strtotime($row['complaint_log_time']));
				$row['logger_image'] = $app->imagePath($row['logger_image']);
				$row['complaint_log_text'] = applyAnchor(Emoji::mapIcon(trim(nl2br(viewText($row['complaint_log_text'])), '<br/>')));
				//$row['complaint_log_media_thumb'] = $row['complaint_log_media'] ? getResizeImage($row['complaint_log_media'], $this->thumbnailSize) : null;
				$row['complaint_log_media'] = $row['complaint_log_media'] ? $app->basePath($row['complaint_log_media']) : null;				
				$result_array[] = $row;
			}
			return array_reverse($result_array);
		}
		else
			return false;
	}
	
	
	function getLogCountChartData()
	{
		global $app;
		$sql="SELECT count(CASE WHEN `complaint_customer_id` = 0 then 1 end) as log_sended , count(CASE WHEN `complaint_user_id` = 0 then 1 end) as log_received , date_format(`complaint_log_time`, '%b %y') as log_time FROM `app_complaint_log` WHERE `complaint_customer_id` = 0 group by log_time order by `complaint_log_time` DESC LIMIT 12";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){		
			while($row = $dbc->db_fetch_assoc()){
				$result_array[] = $row;
			}			
			return array(
			"LABEL"=>json_encode(array_reverse(array_column($result_array, 'log_time'))),
			"SENDED"=>json_encode(array_reverse(array_column($result_array, 'log_sended'))),
			"RECEIVED"=>json_encode(array_reverse(array_column($result_array, 'log_received')))			
			);
		}
		else
			return false;
		
	}
	
	static function drawLogRecord($logText){
	return '
	<div id="chat_msg_id_'.$logText['complaint_log_id'].'" class="callout callout-'.($logText['logger_type']=='E'?"info":"warning").' m-a-0 p-y-1">
		<div class="avatar pull-xs-left l_r_b_l">
			<img src="'.$logText['logger_image'].'" class="img-avatar" alt="'.$logText['logger_name'].'">
		</div>
		<div class="l_r_b_r logtextbox">'.$logText['complaint_log_text'].'</div>'.
		($logText['complaint_log_tag'] != null ? ('<div class="log_tag_user"><i class="icon icon-tag"></i> '.$logText['complaint_log_tag'].'</div>') : '').'
		<div class="l_r_b_c">
			<i class="fa '.($logText['complaint_log_privacy'] == 0 ? 'fa-lock text-success':'fa-unlock text-danger').'"></i>
			<small class="text-muted m-r-1">
				<i class="icon-clock"></i>&nbsp; '.$logText['log_time'].' '.dateView($logText['complaint_log_time'],'FULL').'
			</small>&nbsp; &nbsp;
			<small class="text-muted">
				<i class="icon-user"></i>&nbsp; '.$logText['logger_name'].'
			</small>
		</div>
		<hr class="m-x-1 m-y-0">
	</div>';	
	}
}
?>