<?php
final class Activity extends DB{
		
	public static function add($activity_message, $activity_section_group=NULL, $activity_section_id=0, $activity_user=0, $activity_user_group=NULL)
	{
		$activity_message_array = explode("|^|", $activity_message);
		$activity_user 			= $activity_user==0?getLoginId():$activity_user;
		$activity_user_group 	= $activity_user_group==NULL?getUserType():$activity_user_group;
		$activity_ip_address	= IP_ADDRESS;
		$activity_status		= 1;
		$activity_message 		= sanitizePostData($activity_message_array[0]);
		$activity_reference_code	= isset($activity_message_array[1]) ? $activity_message_array[1] : '';
		$sql="INSERT INTO `app_activity_borad`(`activity_message`, `activity_reference_code`, `activity_user`, `activity_user_group`, `activity_section_id`, `activity_section_group`, `activity_created_date`, `activity_ip_address`, `activity_status`) VALUES ('$activity_message', '$activity_reference_code', '$activity_user', '$activity_user_group', '$activity_section_id', '$activity_section_group', NOW(), '$activity_ip_address', '$activity_status')";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return $dbc->db_insert_id();		
	}
	/*
	public static function getActivity()
	{
		global $app;
		$sql="SELECT a.`activity_id`, a.`activity_message`, a.`activity_user`, a.`activity_user_group`, a.`activity_section_id`, a.`activity_section_group`, a.`activity_created_date`, a.`activity_ip_address`, a.`activity_status`, aas.activity_section_name,
		
		(CASE WHEN a.`activity_user_group` = 'C' THEN 'Customer' ELSE (CASE WHEN a.`activity_user_group` = 'E' THEN 'Employee' END) END) as user_group_name,
		
		aas.activity_section_name as  user_section_name,
		
		(CASE WHEN a.`activity_section_group` = 'C' THEN d.`complaint_ticket_number` ELSE (CASE WHEN a.`activity_section_group` = 'W' THEN e.`wc_code` END) END) as unique_code
		
		FROM `app_activity_borad` as a 
		
		LEFT JOIN `app_system_user` as b ON (a.`activity_user` = b.`user_id` AND a.`activity_user_group` = 'E')
		
		LEFT JOIN `app_activity_section` as aas ON a.`activity_section_group` = aas.`activity_section_code` 
		
		LEFT JOIN `app_customer` as c ON (a.`activity_user` = c.`customer_id` AND a.`activity_user_group` = 'C')
				
		LEFT JOIN `app_complaint_record` as d ON (a.`activity_section_id` = d.`complaint_id` AND a.`activity_section_group` = 'C')
		
		LEFT JOIN `app_wc_record` as e ON (a.`activity_section_id` = e.`wc_id` AND a.`activity_section_group` = 'W')
				
		LEFT JOIN `app_customer` as f ON (a.`activity_section_id` = f.`customer_id` AND a.`activity_section_group` = 'F')
		";	
		
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){		
			while($row = $dbc->db_fetch_assoc()){
				$row['log_time'] = dateView($row['complaint_log_time'],'NOW');
				$row['logger_image'] = $app->imagePath($row['logger_image']);
				$row['complaint_log_text'] = viewText($row['complaint_log_text']);
				
				$result_array[] = $row;
			}
			return array_reverse($result_array);
		}
		else
			return false;
	}
	*/
	
	public static function getConsolidatedTime($timeframe){
		if($timeframe != "")
		{
			$timeArray = explode(',', $timeframe);
			if(count($timeArray) > 0)
			{
				$pTime = 0;
				$initTime = 0;
				$netTime = 0;
				foreach($timeArray as $time)
				{
					$times = explode('|', $time);
					$start_time = strtotime($times[0]);
					$stop_time = strtotime($times[1]);
					
					if($pTime == 0)
					{
						$initTime = $start_time;
						$pTime = $stop_time;
						$netTime += $stop_time - $start_time;
					}
					if($pTime < $stop_time)
					{
						$netTime += $stop_time - $start_time;
						if($start_time < $pTime)
						$netTime -= $pTime - $start_time;
					}
					$pTime = $stop_time;
				}
				return $netTime;
			}
			else
				return 0;
		}
		else
			return 0;
	}
		
	public static function todayWorking()
	{
		global $app;
		//$sql = "SELECT a.`log_user_id`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as user_name , b.`user_image`, b.`user_is_live`, SUM(TIMESTAMPDIFF(SECOND, a.`log_login_time`, COALESCE(a.`log_logout_time`, NOW())) as working, max(a.`log_login_time`) as last_login, DATE_FORMAT(a.`log_login_time`, '%b-%d') as last_login_day FROM `app_log_login` as a INNER JOIN `app_system_user` as b ON a.`log_user_id` = b.`user_id` AND a.`log_user_type` = 'E' WHERE a.`log_user_type` = 'E' GROUP BY a.`log_user_id`, DATE_FORMAT(a.`log_login_time`, '%Y-%m-%d') ORDER BY MAX(a.`log_login_time`)desc LIMIT 14 ";
		//echo $sql;
		$sql = "SELECT a.`log_user_id`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as user_name , b.`user_image`, b.`user_is_live`, GROUP_CONCAT(CONCAT(a.`log_login_time`, '|', COALESCE(a.`log_logout_time`, NOW())) ORDER BY a.`log_login_time`) as group_working, max(a.`log_login_time`) as last_login, DATE_FORMAT(a.`log_login_time`, '%b-%d') as last_login_day FROM `app_log_login` as a INNER JOIN `app_system_user` as b ON a.`log_user_id` = b.`user_id` AND a.`log_user_type` = 'E' WHERE a.`log_user_type` = 'E' GROUP BY a.`log_user_id`, DATE_FORMAT(a.`log_login_time`, '%Y-%m-%d') ORDER BY MAX(a.`log_login_time`)desc LIMIT 14 ";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		$totalworking = (8*60*60);
		if($dbc->db_num_rows()){
			while($row = $dbc->db_fetch_assoc()){
				$row['working'] = Activity::getConsolidatedTime($row['group_working']);
				$row['user_name'] = $row['user_name'];
				//$row['user_image'] = $app->siteUrl($row['user_image']);
				$row['user_image'] = getResizeImage($row['user_image'], 50,50);
				$row['last_login'] = $row['last_login']!=NULL ? dateView($row['last_login'],'NOW'):"Never loged in";
				$row['seconds'] = $row['working'];
				$row['total'] = max($totalworking,$row['working']);
				$row['percent'] = min(floor($row['seconds']*100/$row['total']),100);
				$row['working'] = getDuration($row['seconds']);
				$row['class'] = $row['percent']>80 ? "success"  : ($row['percent']>60 ? "default"  : ($row['percent']>40 ? "warning"  : "danger"));
				$result_array[] = $row;
			}
			return $result_array;
		}
		else
			return false;	
	}
	
	public static function getUserWorkByMonth($month, $log_user_id )
	{
		global $app;
		$sql = "SELECT GROUP_CONCAT(CONCAT(`log_login_time`, '|', COALESCE(`log_logout_time`, NOW()))  ORDER BY `log_login_time`) as group_working, MIN(DATE_FORMAT(`log_login_time`, '%h:%i%p')) as start_time,  DATE_FORMAT(`log_login_time`, '%b-%d') as login_day, DATE_FORMAT(`log_login_time`, '%d') as day_value FROM `app_log_login` WHERE `log_user_type` = 'E' AND DATE_FORMAT(`log_login_time`, '%Y-%m') = '$month' AND `log_user_id` = '$log_user_id' GROUP BY  DATE_FORMAT(`log_login_time`, '%Y-%m-%d') ORDER BY DATE_FORMAT(`log_login_time`, '%Y-%m') ";
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){
			while($row = $dbc->db_fetch_assoc(true)){
				$row['work_time'] = Activity::getConsolidatedTime($row['group_working']);
				//$row['work_time'] = min($row['work_time'], 24*60*60);
				$row['work_time_duration'] = getDuration($row['work_time']);				
				$result_array[$row['day_value']] = $row;
			}
			return $result_array;
		}
		else
			return false;	
	}
	
	public static function updateUserDaysWorkingHours($date)
	{
		global $app;
		$sql = "SELECT `log_user_id`, GROUP_CONCAT(CONCAT(`log_login_time`, '|', COALESCE(`log_logout_time`, NOW()))  ORDER BY `log_login_time`) as group_working FROM `app_log_login` WHERE `log_user_type` = 'E' AND DATE_FORMAT(`log_login_time`, '%Y-%m-%d') = '$date' GROUP BY `log_user_id` ORDER BY `log_user_id`";
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){
			while($row = $dbc->db_fetch_assoc(true)){
				$row['work_time'] = Activity::getConsolidatedTime($row['group_working']);
				$sql = "UPDATE `app_log_login` SET `log_day_work` = '$row[work_time]' WHERE `log_user_id` = '$row[log_user_id]' AND DATE_FORMAT(`log_login_time`, '%Y-%m-%d') = '$date'";
				$dbcUpdate 	= 	new DB();
				$dbcUpdate->db_query($sql);
			}
			return $result_array;
		}
		else
			return false;	
	}
	
	public static function getUserWorkByDay($day, $log_user_id )
	{
		global $app;
		$sql = "SELECT TIMESTAMPDIFF(SECOND, `log_login_time`, COALESCE(`log_logout_time`, NOW())) as work_time, log_device, log_ip_address, DATE_FORMAT(`log_login_time`, '%h:%i%p') as start_time,  DATE_FORMAT(`log_logout_time`, '%h:%i%p') as stop_time FROM `app_log_login` WHERE `log_user_type` = 'E' AND DATE_FORMAT(`log_login_time`, '%Y-%m-%d') = '$day' AND `log_user_id` = '$log_user_id' ORDER BY `log_login_time`";
		//$sql = "SELECT GROUP_CONCAT(CONCAT(`log_login_time`, '|', COALESCE(`log_logout_time`, NOW()))) as group_working, DATE_FORMAT(`log_login_time`, '%h:%i%p') as start_time,  DATE_FORMAT(`log_logout_time`, '%h:%i%p') as stop_time FROM `app_log_login` WHERE `log_user_type` = 'E' AND DATE_FORMAT(`log_login_time`, '%Y-%m-%d') = '$day' AND `log_user_id` = '$log_user_id' ORDER BY `log_login_time`";
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){
			while($row = $dbc->db_fetch_assoc(true)){
				//$row['work_time'] = Activity::getConsolidatedTime($row['group_working']);
				$row['work_time_duration'] = getDuration($row['work_time']);
				$row['log_device'] = getBrowser($row['log_device']).'-'.getOs($row['log_device']);
				$result_array[] = $row;
			}
			return $result_array;
		}
		else
			return false;	
	}
	
	public static function getUserWorkPerformance($log_user_id )
	{
		global $app;
		$sql = "SELECT COUNT(DISTINCT DATE_FORMAT(`log_login_time`, '%Y-%m-%d')) AS days, SUM( DISTINCT `log_day_work`) AS seconds, (SELECT count(`activity_id`) FROM `app_activity_borad` WHERE `activity_user` = '$log_user_id') AS activity, (SELECT count(`activity_id`) FROM `app_activity_borad`) AS total_activity, (SELECT count(`user_id`) FROM `app_system_user` WHERE `user_status` = '1') AS total_user FROM `app_log_login` WHERE `log_user_type` = 'E' AND `log_user_id` = '$log_user_id' AND log_day_work > 0  AND DATE_FORMAT(`log_login_time`, '%a') NOT IN ('Sat', 'Sun')";		
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){
			$record = $dbc->db_fetch_assoc(true);
			$record['seconds'] = $record['seconds'] ? $record['seconds'] : 0;
			$record['average_work'] = $record['days'] ? round($record['seconds']/($record['days'] * 60 * 60), 2) : 0;
			$record['max_day_work'] = 8;			
			$record['day_work_percent'] = round(($record['average_work'] * 100) / $record['max_day_work']);
			$record['work_class'] = $record['day_work_percent']>80 ? "success"  : ($record['day_work_percent']>60 ? "default"  : ($record['day_work_percent']>40 ? "warning"  : "danger"));
			$record['average_activity'] = round($record['activity']/150, 2);	
			$record['average_activity_total'] = round($record['total_activity']/($record['total_user'] * 150), 2);	
			$record['average_activity_percent'] = round(($record['average_activity'] * 100 )/$record['average_activity_total'], 2);	
			$record['activity_class'] = $record['average_activity_percent']>80 ? "success"  : ($record['average_activity_percent']>60 ? "default"  : ($record['average_activity_percent']>40 ? "warning"  : "danger"));
			return $record;			
		}
		else
			return false;	
	}
	
	public static function archiveActivity($duration = 6)
	{
		global $app;
		
		$sql = "SELECT * FROM `app_activity_borad` WHERE `activity_created_date` <= DATE_ADD(CURRENT_DATE, INTERVAL -$duration MONTH)";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows())
		{
			$dbcIns 	= 	new DB();
			$dbcIns->db_query("INSERT INTO `archive_app_activity_borad` SELECT * FROM `app_activity_borad` WHERE `activity_created_date` <= DATE_ADD(CURRENT_DATE, INTERVAL - $duration MONTH)");
			
			$dbcDel 	= 	new DB();
			$dbcDel->db_query("DELETE FROM `app_activity_borad` WHERE `activity_created_date` <= DATE_ADD(CURRENT_DATE, INTERVAL -$duration MONTH)");
			return true;
		}
		else
			return false;	
	}
	
	public static function archiveLoginLog($duration = 6)
	{
		global $app;
		
		$sql = "SELECT * FROM `app_log_login` where `log_login_time` <= DATE_ADD(CURRENT_DATE, INTERVAL -$duration MONTH)";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows())
		{
			$dbcIns 	= 	new DB();
			$dbcIns->db_query("INSERT INTO `archive_app_log_login` (SELECT * FROM `app_log_login` where `log_login_time` <= DATE_ADD(CURRENT_DATE, INTERVAL -$duration MONTH))");
			
			$dbcDel 	= 	new DB();
			$dbcDel->db_query("DELETE FROM `app_log_login` WHERE `log_login_time` <= DATE_ADD(CURRENT_DATE, INTERVAL -$duration MONTH)");
			return true;
		}
		else
			return false;	
	}

	
	
	/*public static function getMaxWorkedEmployee(){
		$maxHoursPerDay = 8*60*60; //Seconds
		$sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, `log_login_time`, COALESCE(a.`log_logout_time`, NOW()))) as work, GROUP_CONCAT(CONCAT(a.`log_login_time`, '|', COALESCE(a.`log_logout_time`, NOW()))) as group_working, a.`log_user_id` FROM `app_log_login` as a WHERE a.`log_login_time` >= DATE_ADD(NOW(), INTERVAL -30 DAY) AND a.`log_user_type` = 'E' AND a.`log_user_id` != '0' GROUP BY a.`log_user_id` ORDER BY work DESC, a.`log_login_time` LIMIT 3";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$array = array();
		if($dbc->db_num_rows() >0)
		{
			while($row = $dbc->db_fetch_assoc(true)){
				$row['work'] = Activity::getConsolidatedTime($row['group_working']);
				$array[] = $row['log_user_id'];
			}
		}
		return $array;
	}*/
	public static function getMaxWorkedEmployee(){
		$maxHoursPerDay = 8*60*60; //Seconds
		$sql = "SELECT GROUP_CONCAT(CONCAT(a.`log_login_time`, '|', COALESCE(a.`log_logout_time`, NOW())) ORDER BY a.`log_login_time`) as group_working, a.`log_user_id` FROM `app_log_login` as a WHERE a.`log_login_time` >= DATE_ADD(NOW(), INTERVAL -30 DAY) AND a.`log_user_type` = 'E' AND a.`log_user_id` != '0' GROUP BY a.`log_user_id`";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$array = array();
		if($dbc->db_num_rows() >0)
		{
			while($row = $dbc->db_fetch_assoc(true)){
				$work = Activity::getConsolidatedTime($row['group_working']);
				$array[$work] = $row['log_user_id'];
			}
		}
		krsort($array);
		return array_slice($array, 0,3);
	}
	public static function todayUserLogins($limit=24)
	{
		global $app;
		
		$sql = "SELECT NOW() as now, a.`log_device`, a.`log_user_id`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as user_name , b.`user_image`, b.`user_is_live`, MIN(a.`log_login_time`) as login_time,  GROUP_CONCAT(CONCAT(a.`log_login_time`, '|', COALESCE(a.`log_logout_time`, NOW())) ORDER BY a.`log_login_time`) as group_working FROM `app_log_login` as a INNER JOIN `app_system_user` as b ON a.`log_user_id` = b.`user_id` AND a.`log_user_type` = 'E' WHERE a.`log_user_type` = 'E' AND DATE_FORMAT(a.`log_login_time`, '%Y-%m-%d') = CURRENT_DATE GROUP BY a.`log_user_id` ORDER BY MIN(a.`log_login_time`) DESC LIMIT $limit";
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		$totalworking = (8*60*60);
		if($dbc->db_num_rows()){		
			while($row = $dbc->db_fetch_assoc()){
				$row['working'] = Activity::getConsolidatedTime($row['group_working']);
				$row['user_image'] = getResizeImage($row['user_image'], 90);	
				$row['duration'] = getDuration($row['working']);
				$row['start_login'] = dateView($row['login_time'],'TIME');	
				$row['percent'] = min(floor($row['working']*100/$totalworking),100);
				$row['working'] = getDuration($row['working']);
				$row['color'] = getProgressColor($row['percent']);		
				$row['browser'] = getBrowser($row['log_device']);		
				$row['os'] = getOs($row['log_device']);		
				$row['widget_percent'] = $row['percent'] >75 ? 100 : ($row['percent'] >50 ? 75 :($row['percent'] >25 ? 50: 25));
				$result_array[] = $row;
			}
			return $result_array;
		}
		else
			return false;	
	}
	/*		
	public function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_activity_borad`" => array(
															"column"=> array("`activity_message`", "`activity_user`", "`activity_user_group`", "`activity_section_id`", "`activity_section_group`", "`activity_created_date`", "`activity_ip_address`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_activity_section`" => array(
															"column"=> array("activity_section_name"),
															"reference" => "aac",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`activity_section_code`" => "`activity_section_group`"))
															),
								"`app_system_user`" => array(
															"column"=> array("CONCAT(b.`user_fname`, ' ', b.`user_lname`) as employee_name", "user_image", "`user_is_live`"),
															"reference" => "b",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`user_id`" => "`activity_user`", "'E'" => "`activity_user_group`"))
															),
				
								"`app_customer`" => array(
															"column"=> array("CONCAT(c.`customer_fname`, ' ', c.`customer_lname`) as customer_name", "customer_image"),
															"reference" => "c",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`customer_id`" => "`activity_user`", "'C'" => "`activity_user_group`"))
															),
								"`app_complaint_record`" => array(
															"column"=> array("`complaint_ticket_number`"),
															"reference" => "d",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`complaint_id`" => "`activity_section_id`", "'C'"=>"`activity_section_group`"))
															),
								"`app_wc_record`" => array(
															"column"=> array("wc_code", "`wc_is_drop_off`"),
															"reference" => "e",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`wc_id`" => "`activity_section_id`", "'W'"=>"`activity_section_group`"))
															),
								"`app_sales_invoice`" => array(
															"column"=> array("sales_invoice_id" , "sales_invoice_number"),
															"reference" => "g",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`sales_invoice_id`" => "`activity_section_id`", "'S'"=>"`activity_section_group`"))
															),
								"`app_website_order`" => array(
																"column"=> array("web_order_id" , "web_order_number"),
																"reference" => "h",
																"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`web_order_id`" => "`activity_section_id`", "'O'"=>"`activity_section_group`"))
																),
								"`app_batch_product`" => array(
																"column"=> array("product_id" , "product_code"),
																"reference" => "i",
																"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`product_id`" => "`activity_section_id`", "'B'"=>"`activity_section_group`"))
																),
								"`app_wc_process`" => array(
																"column"=> array("wc_process_id" , "wc_process_asset_code"),
																"reference" => "j",
																"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`wc_process_id`" => "`activity_section_id`", "'P'"=>"`activity_section_group`"))
																),
								"`app_shipment_record`" => array(
																"column"=> array("shipment_id" , "shipment_code"),
																"reference" => "k",
																"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`shipment_id`" => "`activity_section_id`", "'T'"=>"`activity_section_group`"))
																),
								"`app_product_lots`" => array(
																"column"=> array("lot_id" , "lot_code"),
																"reference" => "l",
																"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`lot_id`" => "`activity_section_id`", "'L'"=>"`activity_section_group`"))
																),
								"app_customer" => array(
															"column"=> array("customer_fname as section_user"),
															"reference" => "f",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`customer_id`" => "`activity_section_id`", "'F'"=>"`activity_section_group`"))
															)
														
							),
					"ORDER"	=>array("activity_created_date","`activity_message`")						
							  );
		
		$this->searchKeyword = $searchKeyword;			
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`activity_id`");
			
		
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
			
			$message =($row['activity_user_group']=="E"?("<a class=\"redirect\" href=\"updateemployee/$row[activity_user]\"><b>".$row['employee_name']."</a>"):("Customer: <a class=\"redirect\" href=\"updatecustomer/$row[activity_user]\"><b>".$row['customer_name']."</a>"));
			$message.="</b> ".$row['activity_message']." $row[activity_section_name] ".($row['wc_is_drop_off'] == 1 ?  "(DropOff)":"")." <a class=\"redirect\" href=\"";			
			$message.=($row['activity_section_group']=="C"?"viewcomplaint/$row[activity_section_id]\"><b>#$row[complaint_ticket_number]</b></a>":"");
			$message.=($row['activity_section_group']=="W"?"updatecollection/$row[activity_section_id]\"><b>#$row[wc_code]</b></a>":"");			
			$message.=($row['activity_section_group']=="F"?"updatecustomer/$row[activity_section_id]\"><b>#$row[section_user]</b></a>":"");
			$message.=($row['activity_section_group']=="S"?"salesinvoice/$row[activity_section_id]\"><b>#$row[sales_invoice_number]</b></a>":"");	
			$message.=($row['activity_section_group']=="O"?"viewweborder/$row[activity_section_id]\"><b>#$row[web_order_number]</b></a>":"");	
			$message.=($row['activity_section_group']=="B"?"updatebatchproduct/$row[activity_section_id]\"><b>#$row[product_code]</b></a>":"");	
			$message.=($row['activity_section_group']=="P"?"\"></a><b>#$row[wc_process_asset_code]</b>":"");	
			$message.=($row['activity_section_group']=="T"?"viewshipment/$row[activity_section_id]\"><b>#$row[shipment_code]</b></a>":"");	
			$message.=($row['activity_section_group']=="L"?"viewlotitems/$row[activity_section_id]\"><b>#$row[lot_code]</b></a>":"");	
			if(trim($row['activity_message'])=="Replied")
			{
				$message.= " <a href=\"#\"  data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[activity_section_id]|$row[activity_section_group]', '#$row[wc_code]$row[complaint_ticket_number] Log Report')\">View Log</a>";
			}
			$output["data"][] = array(
				"<div class=\"avatar\"><a class=\"redirect\" href=\"".($row['activity_user_group']=="E"?"updateemployee":"updatecustomer")."/$row[activity_user]\"><img class=\"img-avatar\" src=\"".getResizeImage($row['activity_user_group']=="E"?$row["user_image"]:$row["customer_image"],50)."\" height=\"34px\"></a>
<span class=\"avatar-status badge-".($row['user_is_live']?"success":"danger")."\"></span></div>",
				$row['activity_user_group']=="E"?viewText($row["employee_name"]):viewText($row["customer_name"]),	
				trim($message),
				dateView($row["activity_created_date"],"SMALL"),
				$row["activity_ip_address"]									
			);
		}
		return json_encode($output);		
	}
	*/
	
	public static function getModuleActivityTimeline($activity_section_group, $activity_section_id){
		global $app;
		$sql = "SELECT a.`activity_message`, a.`activity_reference_code`, aac.`activity_section_key`, a.`activity_user`, a.`activity_section_id`, a.`activity_section_group`, a.`activity_created_date`, aac.`activity_section_name`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as employee_name, b.user_image, b.`user_is_live` FROM  `app_activity_borad` AS a  
		LEFT JOIN `app_activity_section` AS aac ON ( aac.`activity_section_code` = a.`activity_section_group`  )  
		LEFT JOIN `app_system_user` AS b ON ( b.`user_id` = a.`activity_user`  AND 'E' = a.`activity_user_group`  ) 		
		WHERE a.`activity_section_group` = '$activity_section_group' AND a.`activity_section_id` = '$activity_section_id' ORDER BY activity_created_date desc";
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){
			$record = array();
			while($row = $dbc->db_fetch_assoc(true))
			{
				//$message = "<a class=\"redirect\" href=\"".$app->basePath("updateemployee/$row[activity_user]")."\"><b>".$row['employee_name']."</a>";
				$message ="</b> ".ucfirst($row['activity_message'])." ".($row['activity_reference_code'] != '' ? $row['activity_reference_code'] : "on it");
				
				if(strpos(trim($row['activity_message']), "Replied") !== false)
				{
					$message.= " <a href=\"#\"  data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[activity_section_id]|$row[activity_section_group]', 'Log Report')\">View Log</a>";
				}
				$record['user_image'] = "<div class=\"avatar\"><a class=\"redirect\" href=\"updateemployee/$row[activity_user]\"><img class=\"img-circle\" src=\"".getResizeImage($row["user_image"],50)."\" height=\"26px\"></a> <span class=\"avatar-status badge-".($row['user_is_live']?"success":"danger")."\"></span></div>";
				$record['user_name'] = viewText($row["employee_name"]);
				$record['message'] = $message;
				$record['time'] = dateView($row["activity_created_date"],"SMALL");
				$result_array[] = $record;
				}
			return $result_array;
		}
		else
			return false;
	}
	
	public function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_activity_borad`" => array(
															"column"=> array("`activity_message`", "`activity_reference_code`", "`activity_user`", "`activity_user_group`", "`activity_section_id`", "`activity_section_group`", "`activity_created_date`", "`activity_ip_address`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_activity_section`" => array(
															"column"=> array("`activity_section_key`", "`activity_section_name`"),
															"reference" => "aac",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`activity_section_code`" => "`activity_section_group`"))
															),
								"`app_system_user`" => array(
															"column"=> array("CONCAT(b.`user_fname`, ' ', b.`user_lname`) as employee_name", "`user_fname`", "`user_lname`", "user_image", "`user_is_live`"),
															"reference" => "b",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`user_id`" => "`activity_user`", "'E'" => "`activity_user_group`"))
															),
				
								"`app_customer`" => array(
															"column"=> array("CONCAT(c.`customer_fname`, ' ', c.`customer_lname`) as customer_name", "`customer_fname`", "customer_image"),
															"reference" => "c",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_activity_borad`", "on"=>array("`customer_id`" => "`activity_user`", "'C'" => "`activity_user_group`"))
															)
														
							),
					"ORDER"	=>array("activity_created_date","`activity_message`")						
							  );
		
		$this->searchKeyword = $searchKeyword;			
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`activity_id`");
			
		
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
			
			$message =($row['activity_user_group']=="E"?("<a class=\"redirect\" href=\"updateemployee/$row[activity_user]\"><b>".$row['employee_name']."</a>"):("Customer: <a class=\"redirect\" href=\"updatecustomer/$row[activity_user]\"><b>".$row['customer_name']."</a>"));
			$message.="</b> ".$row['activity_message']." $row[activity_section_name] <a class=\"redirect\" href=\"".$app->basePath($row['activity_section_key'].'/'.$row['activity_section_id'])."\"><b>".($row['activity_reference_code'] != '' ? $row['activity_reference_code'] : "View Details")."</b></a>";
				
			if(strpos(trim($row['activity_message']), "Replied") !== false)
			{
				$message.= " <a href=\"#\"  data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[activity_section_id]|$row[activity_section_group]', '$row[activity_section_name] #$row[activity_reference_code] log')\">View Log</a>";
			}
			$output["data"][] = array(
				"<div class=\"avatar\"><a class=\"redirect\" href=\"".($row['activity_user_group']=="E"?"updateemployee":"updatecustomer")."/$row[activity_user]\"><img class=\"img-avatar\" src=\"".getResizeImage($row['activity_user_group']=="E"?$row["user_image"]:$row["customer_image"],50)."\" height=\"34px\"></a>
<span class=\"avatar-status badge-".($row['user_is_live']?"success":"danger")."\"></span></div>",
				$row['activity_user_group']=="E"?viewText($row["employee_name"]):viewText($row["customer_name"]),	
				trim($message),
				dateView($row["activity_created_date"],"SMALL"),
				$row["activity_ip_address"]	,
$sql				
			);
		}
		return json_encode($output);		
	}
}
?>