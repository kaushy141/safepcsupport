<?php
class Schedule extends DB{
	public $id;
	public $key_id		= "schedule_id";
	public $table_name	= "app_schedule_diary";
	public $status		= "schedule_status";
	function __construct($id=0){				
		$this->id	=	$id;
	}
	
	function add($schedule_user_id, $schedule_customer_id, $schedule_title, $schedule_scope, $schedule_due_date, $schedule_status)
	{		
		$sql="INSERT INTO `app_schedule_diary`(`schedule_user_id`, `schedule_customer_id`, `schedule_title`, `schedule_created_date`, `schedule_scope`, `schedule_due_date`, `schedule_status`, `schedule_mark_as_read`) VALUES ('$schedule_user_id', '$schedule_customer_id', '$schedule_title', NOW(), '$schedule_scope', '$schedule_due_date', '$schedule_status', '0')";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return $dbc->db_insert_id();		
	}
	
	function getDetails()
	{
		global $app;
		$sql="SELECT a.*, b.`customer_email` FROM `app_schedule_diary` AS a LEFT JOIN `app_customer` AS b ON a.`schedule_customer_id` = b.`customer_id` WHERE  MD5(a.`schedule_id`) = MD5('".$this->id."')";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows())
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
			
		
	function getRecords()
	{
		global $app;
		$sql="SELECT a.`schedule_id`, a.`schedule_user_id`, a.`schedule_customer_id`, a.`schedule_title`, a.`schedule_created_date`, a.`schedule_scope`, a.`schedule_due_date`, a.`schedule_status`, a.`schedule_mark_as_read`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as user_name, b.`user_image` , c.`schedule_status_name`, c.`schedule_status_color`, CONCAT(`customer_fname`, ' ', `customer_lname`) as customer_name, `customer_image`, `customer_email`
		FROM `app_schedule_diary` as a
		INNER JOIN `app_system_user` as b ON a.`schedule_user_id` = b.`user_id`
		INNER JOIN `app_schedule_status` as c ON a.`schedule_status` = c.`schedule_status_id` 	
		LEFT JOIN `app_customer` as d ON a.`schedule_customer_id` = d.`customer_id` 
		LEFT JOIN `app_complaint_log_user_tag` AS e ON a.`schedule_id` = e.`tag_module_id` AND e.`tag_module_code` = 'A'
		WHERE a.`schedule_scope` = 'Public' OR a.`schedule_user_id` = '".getLoginId()."' OR e.`tag_user_id` = '".getLoginId()."' GROUP BY a.`schedule_id` ORDER BY a.`schedule_due_date` DESC";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc(true)){					
			$html.="<tr style=\"background:$row[schedule_status_color]\">";
				$html.="<td><img title=\"Added by $row[user_name]\" class=\"img-avatar\" src=\"".$app->imagePath($row["user_image"])."\" height=\"40px\"></td>";
				$html.="<td>".($row['customer_name']?:"No Customer")."</td>";
				$html.="<td>".$row['schedule_title']."</td>";
				$html.="<td>".dateView($row['schedule_due_date'])."</td>";
				$html.="<td>".$row['schedule_status_name']."</td>";
				$html.="<td><a class=\"dropdown-item schedule_opener\" href='#' data-id=\"$row[schedule_id]\"><i class=\"fa fa-eye fa-fw\"></i> View</a></td>";
			$html.="</tr>";		
		}
		return $html;	
	}
	
	function getOptions($schedule_status_id=NULL)
	{
		$sql="SELECT `schedule_status_id`, `schedule_status_name`, `schedule_status_color`, `schedule_status_created_date`, `schedule_status_flag` FROM `app_schedule_status` WHERE `schedule_status_flag`= 1 OR `schedule_status_id` = '$schedule_status_id'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select schedule status - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[schedule_status_id]\" ".(($schedule_status_id==$row['schedule_status_id'])?"selected":"")." >".$row['schedule_status_name']."</option>";					
		}
		return $html;	
	}
	
	function isPermission(){
		$sql = "SELECT a.`schedule_id` FROM `app_schedule_diary` as a LEFT JOIN `app_complaint_log_user_tag` AS b ON a.`schedule_id` = b.`tag_module_id` AND b.`tag_module_code` = 'A' WHERE a.`schedule_scope` = 'Public' OR a.`schedule_user_id` = '".getLoginId()."' OR b.`tag_user_id` = '".getLoginId()."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return $dbc->db_num_rows();
	}
	
	function fetchNotification()
	{
		global $app;
		$sql="SELECT a.`schedule_id`, a.`schedule_title`, a.`schedule_created_date`, a.`schedule_scope`, a.`schedule_due_date`, a.`schedule_status`, a.`schedule_mark_as_read`, b.`user_fname`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as user_name, b.`user_image` , c.`schedule_status_name`, c.`schedule_status_color`, CONCAT(`customer_fname`, ' ', `customer_lname`) as customer_name, `customer_image`, `customer_email`
		FROM `app_schedule_diary` as a
		INNER JOIN `app_system_user` as b ON a.`schedule_user_id` = b.`user_id`
		INNER JOIN `app_schedule_status` as c ON a.`schedule_status` = c.`schedule_status_id` 	
		LEFT JOIN `app_customer` as d ON a.`schedule_customer_id` = d.`customer_id` WHERE (a.`schedule_scope` = 'Public' OR a.`schedule_user_id` = '".getLoginId()."') AND a.`schedule_mark_as_read` = '0' AND a.`schedule_due_date` < DATE_ADD(NOW(), INTERVAL 10 MINUTE)  ORDER BY a.`schedule_due_date` DESC LIMIT 5";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$outputArray = array();
		if($dbc->db_num_rows())
		{
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row["user_image"] = $app->imagePath($row["user_image"]);
				$row["customer_image"] = $app->imagePath($row["customer_image"]);
				$row['schedule_due_date_view'] = dateView($row['schedule_due_date']);
				$row['schedule_created_date_view'] = dateView($row['schedule_created_date']);
				$outputArray[$row["schedule_id"]] = $row;	
			}
			return $outputArray;
		}
		else
			return NULL;
	}
}
?>