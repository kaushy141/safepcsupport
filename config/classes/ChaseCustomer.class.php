<?php
class ChaseCustomer extends DB{
	public $id;
	public $key_id	=	"chase_customer_id";
	public $table_name	=	"app_chase_customer";
	public static $CHASE_CUSTOMER_ACTIVE = "Active";
	public static $CHASE_CUSTOMER_INACTIVE = "In active";
	public static $CHASE_CUSTOMER_ONHOLD = "On hold";
	public static $CHASE_CUSTOMER_BOOKED_CLIENTS = "Booked Clients";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}

	static function isChaseCustomerExist($chase_customer_email, $chase_customer_id = 0){
		$chase_customer_email = strtolower(trim($chase_customer_email));
		$condition = "";
		if($chase_customer_id !=0)
		{
			$condition = " AND `chase_customer_id` != '$chase_customer_id'";
		}
		$sql = "SELECT chase_customer_id FROM app_chase_customer WHERE LOWER(chase_customer_email) = '$chase_customer_email' $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_num_rows($result);
	}
	
	function getChaseCustomer($keyword, $type="", $chase_customer=0)
	{
		$condition = $type != "" ? "AND `chase_customer_type` = '$type'" : "";
		$keyword = trim(strtolower(str_replace(" ", "%", $keyword)));
		$sql="SELECT * FROM `app_chase_customer` 
		WHERE (LOWER(`chase_customer_name`) LIKE '%$keyword%' 
		OR LOWER(`chase_customer_email`) LIKE '%$keyword%' 
		OR LOWER(`chase_customer_contact`) LIKE '%$keyword%' 
		OR LOWER(`chase_customer_company`) LIKE '%$keyword%' 
		OR LOWER(`chase_customer_status`) LIKE '%$keyword%' 
		OR LOWER(`chase_customer_address`) LIKE '%$keyword%' 
		OR LOWER(`chase_customer_type`) LIKE '%$keyword%' 
		OR `chase_customer_id` = '$chase_customer') $condition  
		ORDER BY `chase_customer_id` = '$chase_customer' DESC, `chase_customer_last_time` DESC, `chase_customer_name` LIMIT 50";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$output_array = array();	
		while($row = $dbc->db_fetch_assoc(true))
		{		
			$row['last_chase_date)'] = self::getLastChasedOn($row);		
			$row['chase_customer_image'] = getResizeImage(DEFAULT_USER_IMAGE, 50);								
			$output_array[]=$row;			
		}
		return $output_array;	
	}
	
	
	static function getLastChasedOn($record){
		if(isset($record['chase_customer_last_email_on']) || isset($record['chase_customer_last_call_on'])){
			if($record['chase_customer_last_email_on'] != null && $record['chase_customer_last_call_on'] != null){
				return dateView(max($record['chase_customer_last_email_on'], $record['chase_customer_last_call_on']), 'FULL');
			}
			elseif($record['chase_customer_last_email_on'] != null){
				return dateView($record['chase_customer_last_email_on'], 'FULL');
			}
			else{
				return dateView($record['chase_customer_last_call_on'], 'FULL');
			}
		}
		else{
			return "Not available";
		}
	}
	
	static function getScheduledCustomer($nextInSeconds = 60, $conditions = ''){
		$sql = "SELECT * FROM `app_chase_customer` WHERE `chase_customer_schedule_date` IS NOT NULL AND `chase_customer_schedule_date` !='' AND `chase_customer_schedule_date` <= (SELECT NOW()- INTERVAL $nextInSeconds SECOND) $conditions ORDER BY `chase_customer_schedule_date`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$dataArray = array();
		if($dbc->db_num_rows($result)){
			while($row = $dbc->db_fetch_assoc(true)){
				$row['chase_customer_image'] = getResizeImage(DEFAULT_USER_IMAGE, 50);
				$row['chase_customer_schedule_time'] = date("dM h:iA", strtotime($row['chase_customer_schedule_date']));
				$dataArray[] = $row;
			}
		}
		return $dataArray;
	}
	
	function getCustomerChaseRecord(){
		$chaseCustomerRecord = new ChaseCustomerRecord();
		return $chaseCustomerRecord->getCustomerChaseRecord($this->id);
	}
	
	function markPrimeCustomer(){
		$this->update(array('chase_customer_is_prime' => 1));
	}
	
	function unMarkPrimeCustomer(){
		$this->update(array('chase_customer_is_prime' => 0));
	}
	
	function getChaseCustomerStatusOptions($status){
		$statusArray = array(self::$CHASE_CUSTOMER_ACTIVE, self::$CHASE_CUSTOMER_INACTIVE, self::$CHASE_CUSTOMER_ONHOLD, self::$CHASE_CUSTOMER_BOOKED_CLIENTS);
		$option = "<option value=''>-- Select Status --</option>";
		foreach($statusArray as $_status){
			$option .= "<option ".($status == $_status ? "selected":"")." value='{$_status}'>{$_status}</option>";
		}
		return $option;
	}
	
	static function getChaseCustomerTypeArray(){
		$sql = "SELECT DISTINCT `chase_customer_type` FROM `app_chase_customer` WHERE `chase_customer_type` != '' && `chase_customer_type` is not null ORDER BY `chase_customer_type`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);		
		$dataArray = array();
		if($dbc->db_num_rows($result)){
			while($row = $dbc->db_fetch_assoc(true)){
				$dataArray[] = $row['chase_customer_type'];
			}
		}		
		return $dataArray;
	}
	
	function getChaseCustomerAttributeOptions($key, $value, $title = "Select"){
		$sql = "SELECT DISTINCT $key FROM `app_chase_customer` WHERE 1 ORDER BY $key";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$option = "<option value=''>-- $title --</option>";
		$dataArray = array();
		if($dbc->db_num_rows($result)){
			while($row = $dbc->db_fetch_assoc(true)){
				$option .= "<option ".($row[$key] == $value ? "selected":"")." value='{$row[$key]}'>{$row[$key]}</option>";
			}
		}		
		return $option;
	}
	
	function getCustomerIdByRecord($ccr_id){
		$chaseCustomerRecord = new ChaseCustomerRecord();
		return $chaseCustomerRecord->getCustomerIdByRecord($ccr_id);
	}
	
	function deleteRecord(){
		$chaseCustomerRecord = new ChaseCustomerRecord();
		return $chaseCustomerRecord->deleteCustomerChaseRecord($this->id);
	}
	
}
class ChaseCustomerRecord extends DB{
	public $id;
	public $key_id	=	"ccr_id";
	public $table_name	=	"app_chase_customer_record";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function deleteCustomerChaseRecord($chase_customer_id){
		$sql = "DELETE FROM app_chase_customer_record WHERE `ccr_customer_id` = '$chase_customer_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getCustomerChaseRecord($chase_customer_id){
		$sql = "SELECT a.*, b.*, c.user_id, c.user_fname, c.user_lname, c.user_image FROM app_chase_customer_record AS a 
		INNER JOIN app_chase_customer AS b on a.`ccr_customer_id` =  b.`chase_customer_id` 
		INNER JOIN app_system_user AS c on a.`ccr_user_id` =  c.`user_id` 
		WHERE a.`ccr_customer_id` = '$chase_customer_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$recordArray = array();
		if($dbc->db_num_rows($result)){		
			while($row = $dbc->db_fetch_assoc(true)){
				$row["user_image"] = getResizeImage($row["user_image"], 50);
				$row["ccr_date"] = dateView($row["ccr_date"], 'FULL');
				$row['complaint_log_tag'] = UserTag::getModuleTag('G', $row['ccr_id']);
				$recordArray[] = $row;
			}			
		}
		return $recordArray;
	}
	
	function getCustomerIdByRecord($ccr_id){
		$sql = "SELECT ccr_customer_id FROM app_chase_customer_record WHERE `ccr_id` = '$ccr_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))
		{		
			$row = $dbc->db_fetch_assoc(true);
			return $row["ccr_customer_id"];					
		}
		else
			return 0;
	}
	
}
?>