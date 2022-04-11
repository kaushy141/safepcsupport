<?php
class PaymentReminder extends DB{
	public $id;
	public $key_id	=	"payment_reminder_id";
	public $table_name	=	"app_paymnet_reminder";
	public $status	=	"payment_reminder_status";
	
	
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	static function getPaymentReminderPeriod(){
		return array(
			5, 2, 0
		);
	}
	static function getPaymentReminderCycleClock($id = NULL, $keyname = 'label'){
		$cycleClockArray = array(
			array("id" => 0, "label" => "Once", "db"=>"0 day"),
			array("id" => 1, "label" => "Weekly", "db"=>"1 week"),
			array("id" => 2, "label" => "Bimonthly", "db"=>"15 day"),
			array("id" => 3, "label" => "Monthly", "db"=>"1 month"),
			array("id" => 4, "label" => "Quaterly", "db"=>"3 month"),
			array("id" => 5, "label" => "Half yearly", "db"=>"6 month"),
			array("id" => 6, "label" => "Yearly", "db"=>"1 year")
		);
		if($id !== NULL)
		{
			return $cycleClockArray[$id][$keyname];
		}
		else
			return $cycleClockArray;
	}
	
	static function getAbsoluteNextInervalDate($startDate, $id){
		if(strtotime($startDate) < time()){
			while(strtotime($startDate) < time())
			{
				$startDate = date('Y-m-d H:i:s', strtotime('+ '.self::getPaymentReminderCycleClock($id, 'db'), strtotime($startDate)));
			}
		}
		return $startDate;
	}
	function getPaymentReminderCondition(){
		$conditionArray = array();
		foreach(self::getPaymentReminderPeriod() as $_period){
			$conditionArray[] = "DATE_FORMAT(DATE_ADD(`payment_reminder_due_date`, INTERVAL -$_period DAY), '%Y-%m-%d') =  CURRENT_DATE";
		}
		$conditionArray[] = "(DATEDIFF(CURRENT_DATE, `payment_reminder_due_date`) > 5 AND `payment_reminder_status` IN (2,4,5))";
		return "(" . implode(" OR ", $conditionArray). ")";
	}
	
	static function getNextReminderIntervalClock($payment_reminder_due_date, $payment_reminder_cycle_clock){
		if($payment_reminder_cycle_clock > 0)
		return date('Y-m-d H:i:s', strtotime('+ '.self::getPaymentReminderCycleClock($payment_reminder_cycle_clock, 'db'), strtotime($payment_reminder_due_date)));
		else
		return NULL;
	}
	
	static function createCycleClockReminder(){
		$sql = "SELECT * FROM `app_paymnet_reminder` WHERE `payment_reminder_cycle_clock` != '0' AND `payment_reminder_is_active` = '1' AND (`payment_reminder_cycle_next_clock` < CURRENT_DATE OR `payment_reminder_cycle_next_clock` IS NULL)";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows() > 0){
			while($row = $dbc->db_fetch_assoc(true))
			{
				$nextCycleClock = $row['payment_reminder_cycle_next_clock'] ? $row['payment_reminder_cycle_next_clock'] : self::getNextReminderIntervalClock($row['payment_reminder_due_date'], $row['payment_reminder_cycle_clock']);
				
				$master_payment_reminder_id = $row['payment_reminder_id'];
				unset($row['payment_reminder_id']);
				$row['payment_reminder_due_date'] = $nextCycleClock;
				$row['payment_reminder_trig_time']	= self::getNextReminderDate($nextCycleClock);
				$row['payment_reminder_cycle_next_clock'] = self::getNextReminderIntervalClock($nextCycleClock, $row['payment_reminder_cycle_clock']);
				
				$row['payment_reminder_code'] = self::getPaymentReminderCode();
				
				$row['payment_reminder_cycle_clock'] = 0;	
				$row['payment_reminder_created_date'] = "NOW()";	
				$row['payment_reminder_updated_date'] = "NOW()";
				$row['payment_reminder_status'] = 2;
				$row['payment_reminder_is_active'] = 0;
				 
				$paymentReminder = new PaymentReminder();
				$payment_reminder_id = $paymentReminder->insert($row);
				$taggedUser = UserTag::getModuleTag('Y', $tag_module_id, false);
				$taggedUserIds = count($taggedUser) ? array_column($taggedUser, 'user_id') : array();
				if(count($taggedUserIds))
				UserTag::saveModuleTag('Y', $payment_reminder_id, $taggedUserIds, 0, $row['payment_reminder_code']);
				
				#==============================================================================================================
				
				$masterPaymentReminder = new PaymentReminder($master_payment_reminder_id);
				$masterPaymentReminder->update(array('payment_reminder_cycle_next_clock' => $row['payment_reminder_due_date']));
				
				
			}
		}		
	}
	
	
	static function isTodaysReminder($date){
		foreach(self::getPaymentReminderPeriod() as $_period){
			if(date('Y-m-d', strtotime($date. " - $_period days")) == date('Y-m-d'))
			return true;
		}
		return false;
	}
	static function getNextReminderDate($date){
		foreach(self::getPaymentReminderPeriod() as $_period){
			$reminderDate = date('Y-m-d', strtotime($date. " - $_period days"));
			if($reminderDate >= date('Y-m-d'))
			return $reminderDate;
		}
		return $date;
	}
	
	function getMyPaymentReminder(){
		global $app; 
		$user_id = getLoginId();
		$sql = "SELECT DISTINCT `payment_reminder_id`, `payment_reminder_type`, `payment_reminder_due_date`, `payment_reminder_recipients`, `payment_reminder_amount`, `payment_reminder_currency`, `payment_reminder_code`, `payment_reminder_trig_time`, `user_id`, `user_fname`, `user_lname`, `user_image`, `wc_status_name`, `store_title` FROM ".$this->table_name."  
		INNER JOIN `app_wc_status` on `app_paymnet_reminder`.`payment_reminder_status`  = `app_wc_status`.`wc_status_id`  
		INNER JOIN `app_system_user` ON `app_paymnet_reminder`.`payment_reminder_creator_user` = `app_system_user`.`user_id` 
		INNER JOIN `app_store_master` ON `app_paymnet_reminder`.`payment_reminder_related_store` = `app_store_master`.`store_id` 
		LEFT JOIN `app_complaint_log_user_tag` ON `tag_module_code` = 'Y' AND `tag_module_id` = `payment_reminder_id`
		WHERE ".$this->getPaymentReminderCondition(). " AND (`tag_mentioner_id` = '$user_id' OR `tag_user_id` = '$user_id') AND `payment_reminder_status` IN (2,4,5)";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows() > 0){
			while($row = $dbc->db_fetch_assoc(true)){
				$row['user_image'] = getResizeImage($row["user_image"],32);
				$row['reminder_text'] = "Payment of $row[payment_reminder_currency] $row[payment_reminder_amount] for #<b>$row[payment_reminder_code]</b> towords <b>$row[payment_reminder_recipients]</b> for $row[store_title] is scheduled to ".($row['payment_reminder_type'] == 1 ? 'pay' : 'receive')." on <b>".date('D,d M-Y', strtotime($row['payment_reminder_due_date']))."</b> reminder added by ".($row['user_id'] == $user_id ? "you" : ($row['user_fname'].' '.$row['user_lname']));
				$row['tag_log_modal'] = "data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[payment_reminder_id]|Y', '#$row[payment_reminder_code] Log Report')\"";
				$row['tag_time'] = dateView($row['payment_reminder_trig_time'], 'DATE');
				$row['payment_reminder_link'] = $app->basePath('paymentreminder/'.md5($row['payment_reminder_id']));
				$data[] = $row;
			}
		}
		//$data['sql'] = $sql;
		return $data;
	}
	
	function getPaymentReminderByCode($payment_reminder_code){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `payment_reminder_code` = '$payment_reminder_code'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($dbc->db_num_rows() == 1){
			return $dbc->db_fetch_assoc(true);
		}
		return $data;
	}
	
	static function getPaymentReminderCode(){
		$sql = "SELECT `payment_reminder_code` as code FROM `app_paymnet_reminder` WHERE YEAR(`payment_reminder_created_date`) = YEAR(NOW()) AND MONTH(`payment_reminder_created_date`) = MONTH(NOW()) ORDER BY `payment_reminder_created_date` DESC LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$chrStr = "000";
		if($dbc->db_num_rows() == 1){
			$data = $dbc->db_fetch_assoc(true);
			$chrStr = substr($data['code'], 6, 3);
		}
		$chrStr++;
		return "PR".date("ym").sprintf("%03d",$chrStr);
	}
	
		
	function getDetails(){
		$sql = "SELECT a.*, b.`wc_status_name` , b.`wc_status_color_code`,  
		c.user_fname , c.user_lname, c.user_image FROM `".$this->table_name."` AS a 
		INNER JOIN `app_wc_status` as b on a.`payment_reminder_status`  = b.`wc_status_id`  
		INNER JOIN `app_system_user` AS c ON a.`payment_reminder_creator_user` = c.`user_id` 
		INNER JOIN `app_store_master` AS d ON a.`payment_reminder_related_store` = d.`store_id` 				
		WHERE `".$this->key_id."` = '".$this->id."' OR MD5(`".$this->key_id."`) = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function getPaymentReminderTagUser($html = true){
		return UserTag::getModuleTag('Y', $this->id, $html);
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_paymnet_reminder`" => array(
															"column"=> array("`payment_reminder_id`", "`payment_reminder_type`", "`payment_reminder_recipients`", "`payment_reminder_code`", "`payment_reminder_amount`", "`payment_reminder_currency`", "`payment_reminder_amount`", "`payment_reminder_currency`", "`payment_reminder_title`", "`payment_reminder_due_date`", "`payment_reminder_trig_time`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_store_master`" => array(
															"column"=> array("`store_name`", "`store_icon`", "`store_title`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_paymnet_reminder`", "on"=>array("`store_id`" => "`payment_reminder_related_store`"))
															),
								"`app_system_user`" => array(
															"column"=> array("`user_fname`", "`user_lname`", "`user_image`"),
															"reference" => "c",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_paymnet_reminder`", "on"=>array("`user_id`" => "`payment_reminder_creator_user`"))
															),
								"`app_wc_status`" => array(
															"column"=> array("`wc_status_name`", "`wc_status_color_code`"),
															"reference" => "d",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_paymnet_reminder`", "on"=>array("`wc_status_id`" => "`payment_reminder_status`"))
															)
								),
					"ORDER"	=>array("user_fname", "payment_reminder_due_date", "payment_reminder_title", "`payment_reminder_amount`",  "`wc_status_name`")						
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
		$this->groupby = array("a.`payment_reminder_id`");
			
		
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
				"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".getResizeImage($row["user_image"],50)."\" height=\"40px\"></div>",
				dateView($row["payment_reminder_due_date"], 'DATE')." ".($row['payment_reminder_type'] == 1 ? "<i class='fa fa-arrow-up text-danger'></i>" : "<i class='fa fa-arrow-down text-success'></i>")."<br/><i class='text-muted small'>".viewText($row["store_title"])."</i>",
				viewText($row["payment_reminder_recipients"])."<br/><i class='text-muted small'>".viewText(limitText($row["payment_reminder_title"], 20))."</i>",
				$row["payment_reminder_amount"].' '.$row["payment_reminder_currency"],
				viewText($row["wc_status_name"]),
				"<div class=\"btn-group\">
				  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
					<i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
				  </button>
				  <div class=\"dropdown-menu dropdown-menu-right\">
					<a class=\"dropdown-item redirect\" href=\"paymentreminder/".md5($row['payment_reminder_id'])."\"><i class=\"fa fa-eye fa-fw\"></i> View</a>
					<a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[payment_reminder_id]|Y', '$row[payment_reminder_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a></div>
				  </div>",
				"paymentreminder/".md5($row['payment_reminder_id']),
				$row['wc_status_color_code`']								
			);
		}
		return json_encode($output);		
	}
	
	function getWebsiteFilteration(){
		$sql = "SELECT b.`store_id`, b.`store_name`, b.`store_icon`, COUNT(a.`payment_reminder_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`payment_reminder_related_store` = b.`store_id` GROUP BY a.`payment_reminder_related_store` ORDER BY `store_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	
	function getStatusFilteration(){
		$sql = "SELECT b.`wc_status_id`, b.`wc_status_name`, COUNT(a.`payment_reminder_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_wc_status` AS b ON a.`payment_reminder_status` = b.`wc_status_id` GROUP BY a.`payment_reminder_status` ORDER BY `wc_status_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	
	function getCreatorFilteration(){
		
		$sql = "SELECT b.`user_id`, b.user_fname, b.`user_lname`, b.`user_image`, COUNT(a.`payment_reminder_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b ON a.`payment_reminder_creator_user` = b.`user_id` GROUP BY a.`payment_reminder_creator_user` ORDER BY `user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	
	static function getPaymentReminderStatusCount(){
		$sql = "SELECT b.`wc_status_name` AS status, b.`wc_status_color_code` AS color, COUNT(a.`payment_reminder_id`) as record FROM `app_paymnet_reminder` AS a INNER JOIN `app_wc_status` AS b ON a.`payment_reminder_status` = b.`wc_status_id` GROUP BY a.`payment_reminder_status` ORDER BY `wc_status_name`";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		if($dbc->db_num_rows()>0)
		{		
			while($row = $dbc->db_fetch_assoc(true))
			{
				$record_array[] = $row;
			}
		}
		return $record_array;		
	}
	
}
?>