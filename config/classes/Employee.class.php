<?php
class Employee extends DB{
	public $id;
	public $key_id	=	"user_id";
	public $table_name	=	"app_system_user";
	public $status	=	"user_status";
	public $extraUserLoad = array(
		'5' => array(18, 41),
		'6' => array(18, 41)
	);
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function setLive()
	{
		$sql="UPDATE `app_system_user` SET `user_is_live` = '1' WHERE `user_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);				
	}
	
	function setOffline()
	{
		$sql="UPDATE `app_system_user` SET `user_is_live` = '0' WHERE `user_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);				
	}
	
	function totalLive()
	{
		$sql="SELECT COUNT(`user_id`) as num FROM `app_system_user` WHERE `user_is_live` = 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_fetch_assoc($sql))		
			return $data['num'];
		else
			return false;
	}
	
	function getIdByEmail($md5Email)
	{
		$sql="SELECT `".$this->key_id."` FROM `".$this->table_name."` WHERE MD5(`user_email`) = '$md5Email'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_fetch_assoc($sql))		
			return $data['user_id'];
		else
			return false;
	}
	function setFCMToken($user_fcm_token)
	{
		$this->update(
						array(
								"user_fcm_token"=>$user_fcm_token
							 )
						);
	}
	
	static function isUserServiceEnabled($user_id, $servicename){
		$sql="SELECT `$servicename` FROM `app_system_user` WHERE `user_id` = '{$user_id}'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_fetch_assoc($sql))		
			return $data[$servicename] ? true : false;
		else
			return false;
	}
	
	function getFCMToken()
	{
		$sql="SELECT `user_fcm_token` FROM `".$this->table_name."` WHERE `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_fetch_assoc(true))		
			return $data['user_fcm_token'];
		else
			return false;
	}
			
	function getDetails()
	{
		//$user_id!=NULL?$this->user_id=$user_id:"";
		$sql="SELECT a.*, CONCAT(a.`user_fname`, ' ', a.`user_lname`) as user_name, b.`user_type_name` FROM `app_system_user` as a INNER JOIN `app_user_type` as b ON a.`user_type_id` = b.`user_type_id` WHERE `".$this->key_id."` = '".$this->id."' OR MD5(`".$this->key_id."`) = '".$this->id."' ";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function getTechnicianOption($user_id)
	{
		global $app;
		$sql="SELECT `user_id`, CONCAT(`user_fname` , ' ', `user_lname`) as user_name, `user_is_live` FROM `app_system_user` WHERE `user_type_id` = 2 AND `user_status` = 1 ORDER BY `user_fname`, `user_lname`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Technician - </option>";	
		while($row = $dbc->db_fetch_assoc()){				
			$html.="<option value=\"$row[user_id]\" ".(($user_id==$row['user_id'])?"selected":"")." >".$row['user_name']."</option>";					
		}
		return $html;
	}
	
	public static function getVotingList(){
		global $app;
		$bannedUserFromVoting = array(9,18,getLoginId());
		$sql="SELECT `user_id`, `user_fname`, `user_lname`, `user_image` FROM `app_system_user` WHERE `user_status` = 1 AND `user_id` NOT IN (".implode(',', $bannedUserFromVoting).") ORDER BY `user_fname`, `user_lname`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$resultArray = array();
		while($row = $dbc->db_fetch_assoc()){
			$row["user_image"] = getResizeImage($row["user_image"], 100);		
			$resultArray[] = $row;					
		}
		return $resultArray;
	}
	
	function getSearchedResult(){
		global $app;
		$sql="SELECT `user_id` AS id, CONCAT(`user_fname` , ' ', `user_lname`) as name, `user_image` AS avatar, `user_type_name` AS info FROM `app_system_user` as a  INNER JOIN `app_user_type` as b ON a.`user_type_id` = b.`user_type_id` WHERE `user_status` = '1' ORDER BY `user_fname`, `user_lname`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();		
		while($row = $dbc->db_fetch_assoc()){
			$row['avatar'] = getResizeImage($row["avatar"],40);
			$record_array[] = $row;					
		}
		return $record_array;
	}
	
	function getUserOption($UsertTypeId = 0, $user_id = 0, $typename=null)
	{
		global $app;
		$condition = "";
		$conditionArray = array();
		$user_type_name = "Employee";
		if($UsertTypeId != 0)
		{
			if($typename == null)
			{
				$userType = new userType($UsertTypeId);
				$userTypeData = $userType->getDetails();
				$user_type_name = $userTypeData['user_type_name'];
			}
			else
			{
				$user_type_name = $typename;
			}
			
			$conditionArray[] = "(`user_type_id` = '$UsertTypeId')";
			if($UsertTypeId == 5 ){
				$conditionArray[] = " (`user_is_collection_mgr` = '1')";
			}
		}
		if(count($conditionArray)){
			$condition = "AND (".implode(" OR ", $conditionArray).") ";
		}
		if($typename != null){
			$user_type_name = $typename;
		}
		$sql="SELECT `user_id`, CONCAT(`user_fname` , ' ', `user_lname`) as user_name FROM `app_system_user` WHERE (`user_status` = 1 $condition ) OR `user_id` = '$user_id' ORDER BY `user_fname`, `user_lname`";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select $user_type_name - </option>";	
		while($row = $dbc->db_fetch_assoc()){				
			$html.="<option value=\"$row[user_id]\" ".(($user_id==$row['user_id'])?"selected":"")." >".$row['user_name']."</option>";					
		}
		return $html;
	}	
	
	function getUserOptionSelect($UsertTypeId = 0, $user_id = 0)
	{
		global $app;				
		$condition = $UsertTypeId !=0 ? " AND `user_type_id` = '$UsertTypeId' " : "";		
		$sql="SELECT `user_id`, CONCAT(`user_fname` , ' ', `user_lname`) as user_name, `user_status` FROM `app_system_user` WHERE (1=1  $condition) OR `user_id` = '$user_id' ORDER BY `user_status` DESC, `user_fname`";	
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select $userTypeData[user_type_name] - </option>";	
		while($row = $dbc->db_fetch_assoc()){				
			$html.="<option style=\"background:".($row['user_status'] ? "":"#fadbd8")."\" value=\"$row[user_id]\" ".(($user_id==$row['user_id'])?"selected":"")." >".$row['user_name']."</option>";					
		}
		return $html;
	}	
	
	function login($username, $password)
	{
		if($username && $password)
		{
			$username = md5($username);		
			$password = md5($password);		
			$sql ="SELECT a.*, CONCAT(a.`user_fname` , ' ', a.`user_lname`) as user_name, b.`user_type_name` FROM `app_system_user` as a INNER JOIN `app_user_type` as b ON a.`user_type_id` = b.`user_type_id` WHERE (MD5(LOWER(a.`user_code`)) = '$username' OR MD5(LOWER(a.`user_email`)) = '$username') AND MD5(a.`user_password`) = '$password'";
			
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			if($dbc->db_num_rows()==1)
				return $dbc->db_fetch_assoc();
			else
				return false;
		}
	}
	
	function isDeviceFcmExist($user_fcm_token)
	{
		global $app;
		$sql="SELECT `user_email` FROM `".$this->table_name."` WHERE `user_device_token` = '$user_fcm_token'" ;	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)==1)
		{
			$record = $dbc->db_fetch_assoc(true);
			return $record['user_email'];
		}
		else
			return false;
	}
	
	function authenticate($user_email, $user_password)
	{
		global $app;
		$user_email = md5($user_email);
		$user_password = md5($user_password);
		$sql="SELECT `user_id` FROM `".$this->table_name."` WHERE (MD5(LOWER(`user_code`)) = '$user_email' OR MD5(`user_email`) = '$user_email') AND MD5(`user_password`) = '$user_password'" ;	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)==1)
		{
			$record = $dbc->db_fetch_assoc(true);
			return $record['user_id'];
		}
		else
			return false;
	}
	
	function IsEmialAvailable($user_email, $user_id = NULL)
	{
		$condition="";
		$user_email = checkData($user_email);
		if($user_id!=NULL)
		$condition=" AND `user_id`!='$user_id'";
		$sql="SELECT * FROM `app_system_user` WHERE LOWER(`user_email`) = LOWER('".$user_email."') $condition";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		return ($dbc->db_num_rows()==1)?false:true;	
	}
	
	function add($user_type_id, $user_code, $user_fname, $user_lname, $user_password, $user_email, $user_phone, $user_address, $user_image, $user_signature, $user_status, $user_created_by, $user_is_mobile_verified, $user_is_email_verified, $user_is_live, $user_qualification, $user_skills, $user_experience, $user_responsibility, $user_cv_file)
	{
		$sql="INSERT INTO `app_system_user`(`user_type_id`, `user_code`, `user_fname`, `user_lname`, `user_password`, `user_email`, `user_phone`, `user_address`, `user_image`, `user_signature`, `user_status`, `user_created_date`, `user_created_by`, `user_is_email_verified`, `user_is_mobile_verified`, `user_is_live`, `user_qualification`, `user_skills`, `user_experience`, `user_responsibility`, `user_cv_file`) VALUES ('$user_type_id', '$user_code', '$user_fname', '$user_lname', '$user_password', '$user_email', '$user_phone', '$user_address', '$user_image', '$user_signature', '$user_status', NOW(), '$user_created_by', '$user_is_mobile_verified', '$user_is_email_verified', '$user_is_live', '$user_qualification', '$user_skills', '$user_experience', '$user_responsibility', '$user_cv_file')";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
		
	function getNewCode($user_email, $name)
	{
		$email_data = explode("@",$user_email);
		$user_code = trim(strtolower($email_data[0]));
		if($user_code!="" && $name!="")
		{
			$sql="SELECT `user_id` FROM `app_system_user` WHERE LOWER(`user_code`) = '$user_code'";	
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);		
			if($dbc->db_num_rows()==0)
				return $user_code;
		}
		return "";
	}
	
	function load()
	{
		return $this->getDetails();
		
	}	
	
	function getRecords($searchKeyword=NULL, $jsonFormat = false)
	{		
		global $app;		
		$sql="SELECT a.`user_id`, a.`user_type_id`, b.`user_type_name`, a.`user_code`, a.`user_fname`, CONCAT(a.`user_fname` , ' ', a.`user_lname`) as user_name, a.`user_lname`, a.`user_password`, a.`user_email`, a.`user_phone`, a.`user_address`, a.`user_image`, a.`user_signature`, a.`user_status`, a.`user_created_date`, a.`user_created_by`, a.`user_is_email_verified`, a.`user_is_mobile_verified`, a.`user_is_live`, a.`user_allow_app`  FROM `app_system_user` as a  INNER JOIN `app_user_type` as b ON a.`user_type_id` = b.`user_type_id`  WHERE 1";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html;	
		while($row = $dbc->db_fetch_assoc(true)){								
				$html.="<tr class=\"".(!$row['user_status']?"danger":""). "\">";
				$html.="<td><img class=\"img-avatar\" src=\"".getResizeImage($row['user_image'],50)."\" width=\"32px\" /></td>";
				$html.="<td>".$row['user_name']."</td>";
				$html.="<td>".$row['user_email']."</td>";
				$html.="<td>".$row['user_phone']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".$row['user_address']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".$row['user_type_name']."</td>";
				$html.="<td class=\"hidden-sm hidden-md\">".dateView($row['user_created_date'])."</td>";
				$html.="<td>".statusView($row['user_id'], $row['user_status'])."</td>";
				$html.="<td>"."<a data-title=\"Update #".$row['user_name']." Employee\" href=\"updateemployee/".$row['user_id']."\" type=\"button\" class=\"btn btn-secondary redirect\"><i class=\"fa fa-lightbulb-o\"></i>&nbsp; View</a>"."</td>";
				$html.="</tr>";	
		}
		return $html;	
	}
	
	static function getLiveUser()
	{		
		global $app;		
		$sql="SELECT a.`user_id`, a.`user_is_live`, CONCAT(a.`user_fname` , ' ', a.`user_lname`) as user_name, a.`user_image` , count(b.`msg_id`) as msg_count, MAX(b.`msg_time`) as last_messaged FROM `app_system_user` as a LEFT JOIN `app_users_messages` as b ON a.`user_id` = b.`msg_sender` AND b.`msg_readed` = '0' WHERE a.`user_status` = '1' AND a.`user_id` != '$_SESSION[user_id]'  GROUP BY a.`user_id` ORDER BY msg_count DESC, a.`user_is_live` DESC, a.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){								
				$row['user_image'] = getResizeImage($row['user_image'],50);
				$record_array[] = $row;
		}
		return $record_array;	
	}
	
	static function getTodayBirthDayUser()
	{		
		global $app;		
		$sql="SELECT `user_id`, `user_is_live`, `user_fname`, `user_lname`, CONCAT(`user_fname` , ' ', `user_lname`) as user_name, `user_image` FROM `app_system_user` WHERE `user_status` = '1' AND day(`user_birthday`) = day(CURRENT_DATE) AND month(`user_birthday`) = month(CURRENT_DATE)";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){								
				$row['user_image'] = getResizeImage($row['user_image'],90);
				$record_array[] = $row;
		}
		return $record_array;	
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_system_user`" => array(
															"column"=> array("`user_id`", "`user_type_id`", "`user_code`", "`user_fname`", "`user_lname`", "`user_password`", "`user_email`", "`user_phone`", "`user_address`", "`user_image`", "`user_status`", "`user_created_date`", "`user_created_by`", "`user_is_email_verified`", "`user_is_mobile_verified`", "`user_is_live`", "`user_allow_app`", "`user_cv_file`", "`user_id_card`", "`user_experience_certificate`", "`user_education_doc`", "`user_releaving_date`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_user_type`" => array(
															"column"=> array("`user_type_name`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_system_user`", "on"=>array("`user_type_id`" => "`user_type_id`"))
															),
								"`app_user_pay_setup`" => array(
															"column"=> array("`user_pay_id`", "`user_pay_contract_file`", "`user_char_exp_file`"),
															"reference" => "c",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_system_user`", "on"=>array("`user_pay_user_id`" => "`user_id`"))
															)
								),
					"ORDER"	=>array("`user_is_live`", "CONCAT(`user_fname`,  `user_lname`)", "`user_email`", "`user_phone`", "`user_address`", "`user_type_name`", "`user_created_date`", "`user_status`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`user_id`");
		$this->condition = array(
									array("b.`user_type_as_supplier`","=","0"),
									//array("a.`user_id`","=","1"),
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
		while($row = $dbc->db_fetch_assoc(true)){					
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".getResizeImage($row["user_image"],50)."\" height=\"40px\"><span class=\"avatar-status badge-".($row['user_is_live']?"success":"danger")."\"></span></div>",
								"<span data-trigger=\"hover\" data-toggle=\"popover-ajax\"  data-popover-action=\"user\" data-popover-id=\"{$row['user_id']}\">".viewText($row["user_fname"]." ".$row["user_lname"])."</span>",
								viewText($row["user_email"]),
								viewText($row["user_phone"]),
								viewText($row["user_address"]),
								$row["user_type_name"],
								dateView($row["user_created_date"],'DATE'),
								statusView($row["user_id"],$row["user_status"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item redirect\" href=\"updateemployee/".$row['user_id']."\"><i class=\"fa fa-edit fa-fw\"></i> Update Info</a>
	".(($_SESSION['user_type_id']==ADMIN_ROLE || isAdminAccess())?
	($row["user_status"] ? "<a class=\"dropdown-item rechargewallet\" data-user-image=\"".getResizeImage($row["user_image"],32)."\" data-user-name=\"".viewText($row["user_fname"]." ".$row["user_lname"])."\" data-user-id=\"$row[user_id]\" href=\"#\"><i class=\"icon-wallet fa-fw\"></i> Recharge Wallet</a>":"")."
	<a class=\"dropdown-item redirect\" href=\"contract/".$row['user_id']."\"><i class=\"fa fa-file-word-o fa-fw\"></i> Edit Appointment</a>".($row['user_pay_id'] ? ("<a class=\"dropdown-item\" target=\"_blank\" href=\"".DOC::APPOINTMNET($row['user_id'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Download Appointment </a>") : "").(($row['user_pay_contract_file'] != "")?
	"<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['user_pay_contract_file'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Download Contract</a>":"").(($row['user_char_exp_file'] != "")?
	"<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['user_char_exp_file'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Char/Experience</a>":"").(($row['user_cv_file'] != "")?
	"<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['user_cv_file'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Download CV</a>":"")
	.(($row['user_id_card'] != "")?
	"<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['user_id_card'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Download ID Card</a>":"")
	.(($row['user_experience_certificate'] != "")?
	"<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['user_experience_certificate'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Experience Cerificate</a>":"")
	.(($row['user_education_doc'] != "")?
	"<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['user_education_doc'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Education document</a>":"").
	"<a class=\"dropdown-item salaryoptionbtn\" href=\"#\" data-id=\"$row[user_id]\" data-month-id=\"".date("ym")."\"><i class=\"fa fa-file-powerpoint-o fa-fw\"></i> ".date('M')." Pay Slip</a>
	<a class=\"dropdown-item redirect\" href=\"employeereport/".$row['user_id']."\"><i class=\"fa fa-calendar fa-fw text-success\"></i> WorkSheet</a>":"").($row['user_releaving_date'] == '' ? ("<a class=\"dropdown-item redirect\" href=\"addexperienceletter/".md5($row['user_id'])."\"><i class=\"fa fa-sticky-note-o fa-fw\"></i> Experience letter</a>"):"")."
  </div>
</div>"
			);
		}
		return json_encode($output);		
	}
	
		function getJsonRecordsSupplier($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_system_user`" => array(
															"column"=> array("`user_id`", "`user_type_id`", "`user_code`", "`user_fname`", "`user_lname`", "`user_password`", "`user_email`", "`user_phone`", "`user_address`", "`user_image`", "`user_status`", "`user_created_date`", "`user_created_by`", "`user_is_email_verified`", "`user_is_mobile_verified`", "`user_is_live`", "`user_allow_app`", "`user_cv_file`", "`user_id_card`", "`user_experience_certificate`", "`user_education_doc`", "`user_releaving_date`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_user_type`" => array(
															"column"=> array("`user_type_name`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_system_user`", "on"=>array("`user_type_id`" => "`user_type_id`"))
															),
								"`app_user_pay_setup`" => array(
															"column"=> array("`user_pay_id`", "`user_pay_contract_file`", "`user_char_exp_file`"),
															"reference" => "c",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_system_user`", "on"=>array("`user_pay_user_id`" => "`user_id`"))
															)
								),
					"ORDER"	=>array("`user_is_live`", "CONCAT(`user_fname`,  `user_lname`)", "`user_email`", "`user_phone`", "`user_address`", "`user_type_name`", "`user_created_date`", "`user_status`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`user_id`");
		$this->condition = array(
									array("b.`user_type_as_supplier`","=","1"),
									//array("a.`user_id`","=","1"),
									//array(
										//array("user_fname","=","Kaushal"),
										//array("user_type_name","=","technician")
										//),
									//array("user_address","like","1")
									
								);		
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($this->SqlExceptLimit);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
		
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_filtered,"data"=>array());
		while($row = $dbc->db_fetch_assoc(true)){					
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".getResizeImage($row["user_image"],50)."\" height=\"40px\"><span class=\"avatar-status badge-".($row['user_is_live']?"success":"danger")."\"></span></div>",
								"<span data-trigger=\"hover\" data-toggle=\"popover-ajax\"  data-popover-action=\"user\" data-popover-id=\"{$row['user_id']}\">".viewText($row["user_fname"]." ".$row["user_lname"])."</span>",
								viewText($row["user_email"]),
								viewText($row["user_phone"]),
								viewText($row["user_address"]),
								$row["user_type_name"],
								dateView($row["user_created_date"],'DATE'),
								statusView($row["user_id"],$row["user_status"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item redirect\" href=\"updateemployee/".$row['user_id']."\"><i class=\"fa fa-edit fa-fw\"></i> Update Info</a></div></div>"
			);
		}
		return json_encode($output);		
	}
	
	function EmployeeAddedchart()
	{
		$sql="SELECT COUNT(`user_id`) as item1, DATE_FORMAT(`user_created_date`, '\'%b-%y\'') as item2 FROM `app_system_user` WHERE 1 GROUP BY item2 ORDER BY `user_created_date`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc())	
		$data[$row['item2']]=$row['item1'];
		return $this->getChartData($data);
	}
	
	function getReportSql($format = NULL)
	{
		global $app;
		$image_path = $app->sitePath();
		$barcode_file = $app->basePath("qrcode.php?data=");
		if($format == NULL)
		return "SELECT LOWER(CONCAT('$barcode_file',LPAD(a.`user_id`,5,0), '-',a.`user_code`)) as barcode , b.`user_type_name`, CONCAT(a.`user_fname` , ' ', a.`user_lname`) as user_name,   a.`user_email`, a.`user_phone`, a.`user_address`, CONCAT('$image_path', (CASE WHEN a.`user_image` = '' THEN 'img/system/default_user_img.jpg' ELSE a.`user_image` END)) as user_image, CASE WHEN a.`user_status` = 1 THEN 'Active' ELSE 'Deactive' END as status, DATE_FORMAT(a.`user_created_date`, '%d-%b %Y %H:%i%p') as careated_date, (SELECT COUNT(`complaint_id`) FROM `app_complaint_record` WHERE `complaint_technician_id` = a.`user_id`) as total, (SELECT COUNT(`complaint_id`) FROM `app_complaint_record` WHERE `complaint_status` = '2' AND `complaint_technician_id` = a.`user_id`) as completed, (SELECT COUNT(`complaint_id`) FROM `app_complaint_record` WHERE `complaint_status` != '2' AND `complaint_technician_id` = a.`user_id`) as pending FROM `app_system_user` as a  INNER JOIN `app_user_type` as b ON a.`user_type_id` = b.`user_type_id`  WHERE 1";
	}
	
	function getListView($offset=0, $searchkeyword = "")
	{
		global $app;
		$condition = "";
		if($searchkeyword!="")
		{
			$searchkeyword = trim(strtolower($searchkeyword));
			$condition = " AND (LOWER(a.`user_fname`) LIKE '%$searchkeyword%' OR  LOWER(a.`user_lname`) LIKE '%$searchkeyword%' OR LOWER(a.`user_email`) LIKE '%$searchkeyword%'  OR LOWER(CONCAT(a.`user_fname` , ' ', a.`user_lname`)) LIKE '%$searchkeyword%' OR  LOWER(b.`user_type_name`) LIKE '%$searchkeyword%') ";
		}
		
		$sql="SELECT a.`user_id`, a.`user_code`, a.`user_fname`, CONCAT(a.`user_fname` , ' ', a.`user_lname`) as user_name, a.`user_lname`, a.`user_password`, a.`user_email`, a.`user_phone`, a.`user_address`, a.`user_image`, a.`user_signature`, a.`user_status`, a.`user_created_date`, a.`user_created_by`, a.`user_is_email_verified`, a.`user_is_mobile_verified`, a.`user_is_live`, a.`user_allow_app`, b.`user_type_name` FROM `app_system_user` as a  INNER JOIN `app_user_type` as b ON a.`user_type_id` = b.`user_type_id`  WHERE 1 $condition GROUP BY a.`user_id` ORDER BY a.`user_id` DESC Limit 10 OFFSET $offset";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){					
			$row['user_image']			= getResizeImage($row['user_image'], 50);
			$row['user_created_date']	= dateView($row['user_created_date'], "DATE");
			$row['user_status_color']	= $row['user_status']?"#4CAF50":"#F44336";
			$record_array[] = $row;		
		}
		return $record_array;	
	}
	
	function getAndroidDetails()
	{
		$sql="SELECT a.`user_id`, a.`user_type_id`, b.`user_type_name`, a.`user_code`, a.`user_fname`, a.`user_lname`, concat(a.`user_fname`, ' ', a.`user_lname`) as user_name , a.`user_password`, a.`user_email`, a.`user_phone`, a.`user_address`, a.`user_image`, a.`user_signature`, a.`user_status`, a.`user_created_date`, a.`user_created_by`, a.`user_is_email_verified`, a.`user_is_mobile_verified`, a.`user_is_live`, a.`user_device_token`, a.`user_allow_notification`, a.`user_allow_app`, a.`user_timeout`,  b.`user_type_name` FROM `app_system_user` as a INNER JOIN `app_user_type` as b ON a.`user_type_id` = b.`user_type_id` WHERE a.`user_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function getAttendaceList($user_id, $start_date, $end_date, $timeZone){
		$sql = "SELECT a.`log_user_id`, CONCAT(b.`user_fname`, ' ', b.`user_lname`) as user_name , b.`user_image`, SUM(TIMESTAMPDIFF(SECOND, a.`log_login_time`, (CASE WHEN a.`log_logout_time` is null then NOW() else a.`log_logout_time` END))) as working, min(a.`log_login_time`) as start_login, CASE WHEN MAX(a.`log_logout_time`) is null then NOW() else a.`log_logout_time` END as end_login, GROUP_CONCAT(CONCAT(a.`log_login_time`, '|' , (CASE WHEN a.`log_logout_time` is null then NOW() else a.`log_logout_time` END))) as history FROM `app_log_login` as a INNER JOIN `app_system_user` as b ON a.`log_user_id` = b.`user_id` AND a.`log_user_type` = 'E' WHERE a.`log_user_type` = 'E' AND a.`log_user_id` = '$user_id' AND a.`log_login_time` >= '$start_date' AND a.`log_login_time`<='$end_date' GROUP BY DATE_FORMAT(a.`log_login_time`, '%Y-%m-%d') ORDER BY a.`log_login_time`";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$outputArray = array();	
		$totalworking = (8*60*60);
		while($row = $dbc->db_fetch_assoc(true)){
			$record = array();				
			$record['start'] = TZDate($row['start_login'], $timeZone);
			$record['end'] = TZDate($row['start_login'], $timeZone);
			$record['url'] = "#";
			$record['title'] = "Working Time " . getTimeformatFromSeconds($row['working']);
			$record['description'] = "<b>Start time:</b> " . dateView($row['start_login'], 'SMALL')." <b>Last Logout time:</b> " . dateView($row['end_login'], 'SMALL') . " <b>Total Time Engaged:</b> " . getTimeformatFromSeconds($row['working']);
			$row['percent'] = min(floor($row['working']*100/$totalworking),100);			
			$record['color'] = $row['percent']>80 ? "#259c5d"  : ($row['percent']>60 ? "#2a8ed8"  : ($row['percent']>40 ? "#d1841c"  : "#d34a1e"));
			$history = explode(",", $row['history']);
			if(count($history)>0)
			{
				$record['description'] .= '<br/><table class="table tbl-bordered table-striped">';
				$record['description'] .= '<tr><th>LogIn</th><th>LogOut</th><th>Duration</th></tr>';
				foreach($history as $h)
				{
					$hData = explode("|", $h);
					$record['description'] .= '<tr><td>'.dateView($hData[0], 'SMALL').'</td><td>'.dateView($hData[1], 'SMALL').'</td><td>'.getTimeformatFromSeconds(strtotime($hData[1])-strtotime($hData[0])).'</td></tr>';
				}
				$record['description'] .= '</table>';
					
			}
			$outputArray[] = $record;	
		} 
		return json_encode($outputArray);		
	}
	
	function getOptions($user_type_id = NULL, $user_id = NULL)
	{
		$condition = "";
		$condition .= $user_type_id != NULL ? " AND `user_type_id` = '$user_type_id'" :"";
		$condition .= $user_id != NULL ? " AND `user_id` = '$user_id'" :"";
		$sql="SELECT `user_id`, `user_module_access`, CONCAT(`user_fname`, ' ', `user_lname`) as user_name FROM `app_system_user` WHERE 1 AND `user_status` = '1' $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option data-check=\"\" value=\"0\"> - Apply to all - </option>";	
		if($dbc->db_num_rows($result))		
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option data-check=\"$row[user_module_access]\" value=\"$row[user_id]\" ".(($user_id==$row['user_id'])?"selected":"")." >".$row['user_name']."</option>";					
		}
		return $html;	
	}
	
	
	public function getTechnicianPendingWork()
	{
		global $app;
		$sql = "SELECT a.`user_id`, a.`user_fname`, a.`user_lname`, a.`user_image`, (Select COUNT(b.`product_id`) FROM `app_batch_product` as b WHERE a.`user_id` = b.`product_under_technician_id` && b.`product_under_technician` = 1) as batch_product, (Select COUNT(c.`complaint_id`) FROM `app_complaint_record` as c WHERE a.`user_id` = c.`complaint_technician_id` AND c.`complaint_status` iN (1,3,5,7)) as complaint FROM `app_system_user` AS a where a.user_status = 1 having (complaint > 0 OR batch_product>0) ORDER BY a.`user_fname` ";
		//echo $sql;
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows()){
			while($row = $dbc->db_fetch_assoc(true)){
				$row['user_image'] =	getResizeImage($row['user_image'],50);
				$result_array[] = $row;
			}
			return $result_array;
		}
		else
			return false;	
	}
	
}

class ContractEmployee extends DB{
	public $id;
	public $key_id	=	"employee_id";
	public $status  =   "employee_status";
	public $table_name	=	"app_employee_contract";
	function __construct($employee_id=0){
				
		$this->id	=	$employee_id;
	}
	
	public function getContractDetailsByEmail($employee_email){
		$employee_email = strtolower(trim($employee_email));
		$sql = "SELECT * FROM `app_employee_contract` WHERE LOWER(TRIM(`employee_email`)) = '$employee_email' ORDER BY `employee_contract_date` DESC LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	public function getDropdownList($fieldName=NULL, $keyword, $extra, $condition=NULL)
	{
		if($condition != "")
		$condition = "AND $condition";
		if(isCustomer())
		{
			$condition .= " AND a.`customer_id` = '$_SESSION[customer_id]' ";
		}
		
		$sql="SELECT CONCAT(`user_fname`, ' ', `user_lname`) as user_name FROM `app_system_user` WHERE `user_status` = 1 AND (`user_fname` LIKE '%$keyword%' OR `user_lname` LIKE '%$keyword%')
		UNION 
		SELECT `employee_name` as user_name FROM `app_employee_contract` WHERE `employee_status` = 1 AND `employee_name` LIKE '%$keyword%'
		ORDER BY user_name";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($result)
		if($dbc->db_num_rows()>0)
		{	
			while($row = $dbc->db_fetch_assoc(true))	
				$data[]	= array("label"=>$row['user_name'], "data-id"=>$row['user_name'], "value"=>$row['user_name'], "extra"=>$row);
		}
		return $data;
	}
	
	function getReportSql(){
		return "SELECT `employee_name`, DATE_FORMAT(`employee_contract_date`, '%d %b-%Y') as employee_contract_date, DATE_FORMAT(`employee_employment_date`, '%d %b-%Y') as employee_employment_date, `employee_address`, `employee_country`, `employee_email`, `employee_phone`, `employee_salary_rate_mode`, `employee_salary_rate_price`, `employee_job_title` FROM `app_employee_contract` WHERE 1";
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_employee_contract`" => array(
															"column"=> array('employee_id', 'employee_name', 'employee_address', 'employee_country', 'employee_email', 'employee_phone', 'employee_id_card', 'employee_salary_rate_mode', 'employee_salary_rate_price', 'employee_signature', 'employee_job_title', 'employee_contract_date', 'employee_employment_date', 'employee_created_date', 'employee_created_by', 'employee_modified_date', 'employee_status'),
															"reference" => "a",
															"join" => NULL
															),
								
								),
					"ORDER"	=>array("`employee_name`", "`employee_country`", "`employee_email`", "`employee_job_title`", "`employee_status`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`employee_id`");
		$this->condition = array(
									//array("a.`user_id`","=","1"),
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
		$num_rows_total= $this->Count();	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								viewText($row["employee_name"]),
								viewText($row["employee_country"]),
								viewText($row["employee_email"]." (".$row["employee_phone"].")"),
								viewText($row["employee_job_title"]),
								dateView($row["employee_created_date"],'DATE'),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item redirect\" href=\"editempcontract/".$row['employee_id']."\"><i class=\"fa fa-edit fa-fw\"></i> Update Details</a>
	<a class=\"dropdown-item\" target=\"_blank\" href=\"".DOC::EMPCONTRACT($row['employee_id'])."\"><i class=\"fa fa-download fa-fw\"></i> Download Contract </a>".(($row['employee_id_card'] != "")?
	"<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['employee_id_card'])."\"><i class=\"fa fa-info fa-fw\"></i> Download ID Card</a>":"")."
	<a class=\"dropdown-item\" href=\"javascript:confirmMessage.Set('Want to send a contract copy to $row[employee_name] ...?', 'sendContractCopyToEmployee', $row[employee_id])\"><i class=\"fa fa-paper-plane-o fa-fw text-success\"></i> Send Contract mail</a>
	<a class=\"dropdown-item\" href=\"javascript:confirmMessage.Set('Want to generate signature link... ?', 'getSignatureLink', $row[employee_id])\"><i class=\"fa fa-paper-plane-o fa-fw text-success\"></i> Request Signature link</a>
	<a class=\"dropdown-item text-danger\" href=\"javascript:confirmMessage.Set('Are you sure to delete this contract...?', 'delEmpContract', $row[employee_id])\"><i class=\"fa fa-trash-o fa-fw text-danger\"></i>Delete Contract</a>
  </div>
</div>",
				$row['employee_id']
			);
		}
		return json_encode($output);		
	}
	
}

class Training extends DB{
	public $id;
	public $key_id	=	"training_id";
	public $status  =   "training_status";
	public $table_name	=	"app_employee_training";
	function __construct($training_id=0){
				
		$this->id	=	$training_id;
	}
	
	function getReportSql(){
		return "SELECT `training_id`, `training_type`, DATE_FORMAT(`training_date`, '%d %b-%Y') as `training_date`, `training_description`, `training_trainee_name`, `training_job_title`, `training_learning_detail`, `training_trainer_name`, `training_created_date`, `training_status` FROM `app_employee_training` WHERE 1";
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_employee_training`" => array(
															"column"=> array('training_id', 'training_type', 'training_date', 'training_description', 'training_trainee_name', 'training_job_title', 'training_learning_detail', 'training_trainer_name', 'training_status'),
															"reference" => "a",
															"join" => NULL
															),
								
								),
					"ORDER"	=>array("`training_date`", "`training_trainee_name`", "`training_job_title`", "`training_trainer_name`", "`training_description`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`training_id`");
		$this->condition = array(
									//array("a.`user_id`","=","1"),
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
		$num_rows_total= $this->Count();	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								dateView($row["training_date"],'DATE'),
								viewText($row["training_trainee_name"]),
								viewText($row["training_job_title"]),
								viewText($row["training_trainer_name"]),
								viewText($row["training_description"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item redirect\" href=\"editemptraining/".$row['training_id']."\"><i class=\"fa fa-edit fa-fw\"></i> Update Details</a>
	<a class=\"dropdown-item text-danger\" href=\"javascript:confirmMessage.Set('Are you sure to delete this training record...?', 'delEmpTraining', $row[training_id])\"><i class=\"fa fa-trash-o fa-fw text-danger\"></i>Delete Training</a>
  </div>
</div>",
				$row['training_id']
			);
		}
		return json_encode($output);		
	}
	
}


class EmployeeLeave extends DB{
	public $id;
	public $key_id	=	"leave_id";
	public $status  =   "leave_is_approved";
	public $table_name	=	"app_employee_leave_sheet";
	function __construct($leave_id=0){
				
		$this->id	=	$leave_id;
	}
	
	function getReportSql(){
		return "SELECT `leave_employee_id`, DATE_FORMAT(`leave_from_time`, '%d %b-%Y %h%i%A') as leave_from_time, DATE_FORMAT(`leave_end_time`, '%d %b-%Y %h%i%A') as leave_end_time, `leave_reason`, `leave_is_approved`, `leave_is_extra_hours` FROM `app_employee_leave_sheet` WHERE 1 ORDER BY `leave_created_date`";
	}
	
	function getEmployeeFilteration(){
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`leave_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`leave_employee_id` = b.`user_id` GROUP BY a.`leave_employee_id` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	
	function calcWorkingDays($init, $completeDays=0){
		$days = floor($init / (8*3600)) + $completeDays;
		$hour = floor(($init % (8*3600)) / 3600);
		$minute = floor(($init / 60) % 60);
		$second = $init % 60;
		return ($days ? ($days."d ") : "") . ($hour ? ($hour."h ") : "") . ($minute ? ($minute . "m ") : "") .($minute==0 && $second ? ($second."s"):"");
	}
	
	function getEmployeeSummary($leave_employee_id, $leave_from_time, $leave_end_time){
		$condition = "`leave_employee_id` = '$leave_employee_id'";
		if($leave_from_time)
			$condition .= " AND `leave_from_time` >= '$leave_from_time'";
		if($leave_end_time)
			$condition .= " AND `leave_end_time` <= '$leave_end_time'";
		$sql = "SELECT SUM(CASE WHEN `leave_is_extra_hours` = 0 THEN (TIMESTAMPDIFF(DAY, `leave_from_time`, `leave_end_time`) +1) * LEAST(TIMESTAMPDIFF(SECOND, `leave_from_time`, `leave_end_time`), 28800) ELSE 0 END) AS leave_seconds, SUM(CASE WHEN `leave_is_extra_hours` = 1 THEN TIMESTAMPDIFF(SECOND, `leave_from_time`, `leave_end_time`) ELSE 0 END) AS extra_seconds, SUM(CASE WHEN `leave_is_extra_hours` = 0 THEN 1 ELSE 0 END) AS leave_count, SUM(CASE WHEN `leave_is_extra_hours` = 1 THEN 1 ELSE 0 END) AS extra_count FROM `app_employee_leave_sheet` WHERE $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record = $dbc->db_fetch_assoc(true);
		$record['leave_days'] = $this->calcWorkingDays($record['leave_seconds']);
		$record['extra_days'] = $this->calcWorkingDays($record['extra_seconds']);
		$record['net_leaves'] = $this->calcWorkingDays(abs($record['leave_seconds'] - $record['extra_seconds']));
		$record['net_lables'] = $record['leave_seconds'] - $record['extra_seconds'] ? "<span class='badge badge-danger'>Leave</span>":"<span class='badge badge-success'>Extra Hours</span>";
		return $record;
	}
	
	function calOfficialHours($start, $end){
		if(strtotime($end) - strtotime($start) > 8*60*60){
			$startDateWork = min(strtotime('+1 day', strtotime(date('Y-m-d', strtotime($start)))) - strtotime($start), 8*60*60);
			$endDateWork = min(strtotime($start) - strtotime('-1 day', strtotime(date('Y-m-d', strtotime($start)))), 8*60*60);
			
			$completeDays = max(round((strtotime(date('Y-m-d', strtotime($end))) - strtotime(date('Y-m-d', strtotime($start))))/ (60 * 60 * 24)) -1, 0);
			return $this->calcWorkingDays($startDateWork + $endDateWork, $completeDays);
		}
		else
			return $this->calcWorkingDays(strtotime($end) - strtotime($start));
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_employee_leave_sheet`" => array(
															"column"=> array('leave_id', 'leave_from_time', 'leave_end_time', 'leave_is_approved', 'leave_reason', 'leave_is_extra_hours'),
															"reference" => "a",
															"join" => NULL
															),

								"`app_system_user`" => array(

															"column"=> array("`user_id`", "`user_fname`", "`user_lname`", "`user_image`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_employee_leave_sheet`", "on"=>array("`user_id`" => "`leave_employee_id`"))

															)
								
								),
					"ORDER"	=>array("`user_fname`", "`user_fname`", "leave_is_extra_hours", "`leave_from_time`", "`leave_end_time`", "`leave_reason`", "`leave_is_approved`")						
							  );
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $field=>$values){
				$fieldCondArray = array();
				if(!in_array($field, array('leave_from_time', 'leave_end_time')))
				{
					if(is_array($values)){
						foreach($values as $_val)
							$fieldCondArray[] = array("a.".$field, "=", sanitizePostData($_val));
					}
					else
						$fieldCondArray[] = array("a.".$field, "=", sanitizePostData($values));
					$conditionArray[] = $fieldCondArray;
				}
				else
				{		
					if($values != "")			
					$conditionArray[] = array("a.".$field, $field == "leave_from_time" ? ">=":"<=", sanitizePostData($values));
				}
					
			}
		}
		$this->condition = $conditionArray;
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`leave_id`");
				
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
		
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array(), $sql);
		while($row = $dbc->db_fetch_assoc(true)){					
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".getResizeImage($row["user_image"],50)."\" height=\"40px\"></div>",
								viewText($row["user_fname"].' '.$row["user_lname"]),
								$row["leave_is_extra_hours"] ? "Extra hours" : "Leave",
								$this->calOfficialHours($row["leave_from_time"], $row["leave_end_time"]),
								dateView($row["leave_from_time"], "FULL"),
								dateView($row["leave_end_time"], "FULL"),
								viewText($row["leave_reason"]),
								$row["leave_is_approved"] ? "<span title='Approved' class='badge badge-success'><i class='fa fa-check'></i></span>":"<span title='Leave' class='badge badge-danger'><i class='fa fa-close'></i></span>",
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item redirect\" href=\"editempleave/".$row['leave_id']."\"><i class=\"fa fa-edit fa-fw\"></i> Update Leave</a>
	<a class=\"dropdown-item leavesummary\" data-user-image=\"".getResizeImage($row["user_image"],32)."\" data-user-name=\"".viewText($row["user_fname"]." ".$row["user_lname"])."\" data-user-id=\"$row[user_id]\" href=\"#\"><i class=\"fa fa-list fa-fw\"></i> Summary Details</a>
	<a class=\"dropdown-item text-danger\" href=\"javascript:confirmMessage.Set('Are you sure to delete this leave...?', 'delEmpLeave', $row[leave_id])\"><i class=\"fa fa-trash-o fa-fw text-danger\"></i>Delete Leave</a>
  </div>
</div>",
				$row['leave_id']
			);
		}
		return json_encode($output);		
	}	
}

class EmployeeInduction extends DB{
	public $id;
	public $key_id	=	"eic_id";
	public $status  =   "eic_status";
	public $table_name	=	"app_employee_inducation_checklist";
	function __construct($leave_id=0){
				
		$this->id	=	$leave_id;
	}
	
	function clearItem(){
		$sql = "DELETE FROM `app_employee_inducation_checklist_item` WHERE `eici_eic_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function saveItem($array){
		extract($array);
		$sql = "INSERT INTO `app_employee_inducation_checklist_item`(`eici_eic_id`, `eici_checklist_item_id`, `eici_comment`, `eici_completed_date`, `eici_completed`) VALUES ('$eici_eic_id', '$eici_checklist_item_id', '$eici_comment', '$eici_completed_date', '$eici_completed')";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getReportSql(){
		return "SELECT * FROM `app_employee_inducation_checklist` WHERE 1 ORDER BY `eic_created_date`";
	}
	
	function getItemsReportHtml(){
		$sql =  "SELECT a.`checklist_item_name`, a.`checklist_item_category`, b.`eici_comment`, b.`eici_completed_date`, b.`eici_completed` FROM `app_checklist_items` as a 
		LEFT JOIN `app_employee_inducation_checklist_item` as b ON (a.`checklist_item_id` = b.`eici_checklist_item_id` AND b.`eici_eic_id` = '{$this->id}') WHERE a.`checklist_item_for` = '1' ORDER BY a.`checklist_item_order`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<table style=\"width:100%\"><tr style=\"background-color:#AAAAAA;\">
		<th style=\"width:50%\"><b>Induction Items</b></th>
		<th style=\"width:30%\">Comments</th>
		<th style=\"width:12%\">Completed On</th>
		<th style=\"width:8%\">Completed</th>
		</tr>";	
		$categoryHtmlArray = array();
		if($dbc->db_num_rows($result))		
		while($row = $dbc->db_fetch_assoc(true)){
			if(!in_array($row['checklist_item_category'], $categoryHtmlArray) && $row['checklist_item_category']!='Primary'){
				$html.="<tr style=\"background-color:#CCCCCC;\"><td colspan=\"4\"><b>$row[checklist_item_category]</b></td></tr>";
				array_push($categoryHtmlArray, $row['checklist_item_category']);
			}
			$html.="<tr style=\"border:1px;\">
			<td style=\"width:50%\">".$row['checklist_item_name']."</td>
			<td style=\"width:30%\">".$row['eici_comment']."</td>
			<td style=\"width:12%; text-align:center\">".($row['eici_completed_date']!= "0000-00-00" ? date('d/m/Y', strtotime($row['eici_completed_date'])):"-")."</td>
			<td style=\"width:8%; text-align:center\">".($row['eici_completed'] ? "<b style=\"color:green\">Yes</b>" : "<b style=\"color:red\">No</b>")."</td>
			</tr>";					
		}
		$html .= "</table>";
		return $html;	
		
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_employee_inducation_checklist`" => array(
															"column"=> array('eic_id', 'eic_employee_name', 'eic_created_date', 'eic_submitted', 'eic_review_status', 'eic_status'),
															"reference" => "a",
															"join" => NULL
															),
								
								),
					"ORDER"	=>array("`eic_employee_name`", "`eic_created_date`", "`eic_submitted`", "`eic_status`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`eic_id`");
		$this->condition = array(									
								);		
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		$num_rows_total= $this->Count();	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								viewText($row["eic_employee_name"]),
								dateView($row["eic_created_date"], "FULL"),
								$row["eic_submitted"] ? "<span class='badge badge-success'>Submited</span>":"<span class='badge badge-warning'>Open</span>",
								statusView($row["eic_id"],$row["eic_status"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    ".(($row['eic_submitted']  == 0 || isAdminRole()) ? "<a class=\"dropdown-item redirect\" href=\"editempinduction/".$row['eic_id']."\"><i class=\"fa fa-edit fa-fw\"></i> Update induction</a>
	<a class=\"dropdown-item text-success completeinduction\" href=\"javascript:confirmMessage.Set('Are you sure to submit this induction report...?<br/>Note: Following things will take place...<br><ul><li>You will no longer acceess to update this record</li><li>Report will submit to respective department for review.</li></ul>', 'completeEmpInduction', $row[eic_id])\"><i class=\"fa fa-check-circle-o fa-fw text-success\"></i>Complete Induction</a>
	<a class=\"dropdown-item text-danger\" href=\"javascript:confirmMessage.Set('Are you sure to delete this induction record...?', 'delEmpInduction', $row[eic_id])\"><i class=\"fa fa-trash-o fa-fw text-danger\"></i>Delete Induction</a>":"<a class=\"dropdown-item dissabled\"> <i class=\"fa fa-search fa-fw text-warning\"></i>Under review</a>").(($row['eic_review_status'])?"<a class=\"dropdown-item\" target=\"_blank\" href=\"".DOC::EMPINDUCTION($row['eic_id'])."\"><i class=\"fa fa-download fa-fw\"></i> Download Document </a>":"")."	
  </div>
</div>",
				$row['eic_id']
			);
		}
		return json_encode($output);		
	}
	
}

#=============================================Leaver------------------------------------


class EmployeeLeaver extends DB{
	public $id;
	public $key_id	=	"eil_id";
	public $status  =   "eil_status";
	public $table_name	=	"app_employee_leaver_checklist";
	function __construct($leave_id=0){
				
		$this->id	=	$leave_id;
	}
	
	function clearItem(){
		$sql = "DELETE FROM `app_employee_leaver_checklist_item` WHERE `eili_eil_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function saveItem($array){
		extract($array);
		$sql = "INSERT INTO `app_employee_leaver_checklist_item`(`eili_eil_id`, `eili_checklist_item_id`, `eili_comment`, `eili_completed_date`, `eili_completed`) VALUES ('$eili_eil_id', '$eili_checklist_item_id', '$eili_comment', '$eili_completed_date', '$eili_completed')";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getItemsReportHtml(){
		$sql =  "SELECT a.`checklist_item_name`, a.`checklist_item_category`, b.`eili_comment`, b.`eili_completed_date`, b.`eili_completed` FROM `app_checklist_items` as a 
		LEFT JOIN `app_employee_leaver_checklist_item` as b ON (a.`checklist_item_id` = b.`eili_checklist_item_id` AND b.`eili_eil_id` = '{$this->id}') WHERE a.`checklist_item_for` = '2' ORDER BY a.`checklist_item_order`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<table style=\"width:100%\"><tr style=\"background-color:#AAAAAA;\">
		<th style=\"width:50%\">Termination Items</th>
		<th style=\"width:30%\">Comments</th>
		<th style=\"width:12%\">Completed On</th>
		<th style=\"width:18%\">Completed</th></tr>";	
		
		if($dbc->db_num_rows($result))		
		while($row = $dbc->db_fetch_assoc(true)){
			
			$html.="<tr style=\"border:1px;\">
			<td style=\"width:50%\">".$row['checklist_item_name']."</td>
			<td style=\"width:30%\">".$row['eili_comment']."</td>
			<td style=\"width:12%; text-align:center;\">".($row['eili_completed_date']!= "0000-00-00" ? date('d/m/Y', strtotime($row['eili_completed_date'])):"-")."</td>
			<td style=\"width:8%; text-align:center\">".($row['eili_completed'] ? "<b style=\"color:green\">Yes</b>" : "<b style=\"color:red\">No</b>")."</td>
			</tr>";					
		}
		$html .= "</table>";
		return $html;	
		
	}
	
	function getReportSql(){
		return "SELECT * FROM `app_employee_leaver_checklist` WHERE 1 ORDER BY `eil_created_date`";
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_employee_leaver_checklist`" => array(
															"column"=> array('eil_id', 'eil_employee_name', 'eil_created_date', 'eil_submitted', 'eil_review_status', 'eil_status'),
															"reference" => "a",
															"join" => NULL
															),
								
								),
					"ORDER"	=>array("`eil_employee_name`", "`eil_created_date`", "`eil_submitted`", "`eil_status`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`eil_id`");
		$this->condition = array(									
								);		
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		$num_rows_total= $this->Count();	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								viewText($row["eil_employee_name"]),
								dateView($row["eil_created_date"], "FULL"),
								$row["eil_submitted"] ? "<span class='badge badge-success'>Submited</span>":"<span class='badge badge-warning'>Open</span>",
								statusView($row["eil_id"],$row["eil_status"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    ".(($row['eil_submitted']  == 0 || isAdminRole()) ? "<a class=\"dropdown-item redirect\" href=\"editempleaver/".$row['eil_id']."\"><i class=\"fa fa-edit fa-fw\"></i> Update leaver</a>
	<a class=\"dropdown-item text-success completeleaver\" href=\"javascript:confirmMessage.Set('Are you sure to submit this leaver report...?<br/>Note: Following things will take place...<br><ul><li>You will no longer acceess to update this record</li><li>Report will submit to respective department for review.</li></ul>', 'completeEmpLeaver', $row[eil_id])\"><i class=\"fa fa-check-circle-o fa-fw text-success\"></i>Complete Leaver</a>
	<a class=\"dropdown-item text-danger\" href=\"javascript:confirmMessage.Set('Are you sure to delete this leaver record...?', 'delEmpLeaver', $row[eil_id])\"><i class=\"fa fa-trash-o fa-fw text-danger\"></i>Delete Leaver</a>":"<a class=\"dropdown-item dissabled\"> <i class=\"fa fa-search fa-fw text-warning\"></i>Under review</a>").(($row['eil_review_status'])?"<a class=\"dropdown-item\" target=\"_blank\" href=\"".DOC::EMPLEAVER($row['eil_id'])."\"><i class=\"fa fa-download fa-fw\"></i> Download Document </a>":"")."	
  </div>
</div>",
				$row['eil_id']
			);
		}
		return json_encode($output);		
	}
	
}
#=============================================Leaver------------------------------------


class EmployeeNotice extends DB{
	public $id;
	public $key_id	=	"notice_id";
	public $status  =   "notice_status";
	public $table_name	=	"app_employee_notice";
	function __construct($notice_id=0){
				
		$this->id	=	$notice_id;
	}
	
	function getEmpNoticeCount($notice_emp_id){
		$sql="SELECT COUNT(`notice_emp_id`) as count FROM `app_employee_notice` WHERE `notice_emp_id` = '$notice_emp_id' AND `notice_status` = '1'";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$row = $dbc->db_fetch_assoc(true);
		return $row['count'];
	}
	
	static function empNoticeSummary()
	{		
		global $app;		
		$sql="SELECT a.`user_id`, CONCAT(a.`user_fname` , ' ', a.`user_lname`) as user_name, a.`user_image` , count(b.`notice_id`) as notice_count, MAX(b.`notice_date`) as last_notice, GROUP_CONCAT(b.`notice_id`) as notice_list FROM `app_system_user` as a INNER JOIN `app_employee_notice` as b ON a.`user_id` = b.`notice_emp_id` WHERE b.`notice_status` = '1' GROUP BY a.`user_id` ORDER BY notice_count DESC, last_notice DESC, a.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){								
				$row['user_image'] = getResizeImage($row['user_image'],50);
				$record_array[] = $row;
		}
		return $record_array;	
	}
		
	function getReportSql(){
		return "SELECT * FROM `app_employee_leaver_checklist` WHERE 1 ORDER BY `eil_created_date`";
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_employee_notice`" => array(
															"column"=> array('notice_id', 'notice_count', 'notice_reason', 'notice_date', 'notice_attachement', 'notice_status'),
															"reference" => "a",
															"join" => NULL
															),
								"`app_system_user`" => array(
															"column"=> array("`user_id`", "`user_fname`", "`user_lname`", "`user_image`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_employee_notice`", "on"=>array("`user_id`" => "`notice_emp_id`"))
															),
								
								),
					"ORDER"	=>array("user_image", "`notice_date`", "`notice_count`", "`user_fname`", "`notice_reason`", "`notice_status`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`notice_id`");
		$this->condition = array(									
								);		
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		$num_rows_total= $this->Count();	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".getResizeImage($row["user_image"],50)."\" height=\"40px\"><span class=\"avatar-status fa fa-bell text-".($row['notice_status']?"danger":"success")."\" style='background:transparent; border:none;'></span></div>",
								dateView($row["notice_date"], "FULL"),
								viewText($row["notice_count"]),
								viewText($row["user_fname"]. " ".$row["user_lname"]),
								viewText($row["notice_reason"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item redirect\" href=\"editempnotice/".$row['notice_id']."\"><i class=\"fa fa-edit fa-fw\"></i> Update notice</a>
	".(($row['notice_attachement'] != "")?
	"<a class=\"dropdown-item\" target=\"_blank\" href=\"".$app->basePath($row['notice_attachement'])."\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Download Attachement</a>":"")."
  </div>
</div>",
				$row['notice_id']
			);
		}
		return json_encode($output);		
	}
	
}

class EmployeeReleaving extends DB{
	public $id;
	public $key_id	=	"releaving_id";
	public $table_name	=	"app_user_releaving";
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	public static function isExpLetterExist($releaving_user_id){
		$sql="SELECT `releaving_id` FROM `app_user_releaving` WHERE `releaving_user_id` = '$releaving_user_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()==1){
			$record = $dbc->db_fetch_assoc(true);
			return $record['releaving_id'];
		}
		else
			return 0;
	}
	
	function getDetails(){
		$sql="SELECT * FROM `app_user_releaving` AS a LEFT JOIN `app_system_user` AS b ON a.`releaving_user_id` = b.`user_id` WHERE a.`releaving_id` = '".$this->id."' OR MD5(a.`releaving_id`) = '".$this->id."' ";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()==1){
			$record = $dbc->db_fetch_assoc(true);
			$this->id = $record[$this->key_id];
			return $record;
		}
		else
			return false;
	}
		
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_user_releaving`" => array(
															"column"=> array('releaving_id', 'releaving_user_id', 'releaving_date', 'releaving_last_position', 'releaving_created_date', 'releaving_created_by', 'releaving_is_exp_letter_sent', 'releaving_exp_letter_sent_date'),
															"reference" => "a",
															"join" => NULL
															),
								"`app_system_user`" => array(
															"column"=> array("`user_id`", "`user_fname`", "`user_lname`", "`user_image`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_user_releaving`", "on"=>array("`user_id`" => "`releaving_user_id`"))
															),
								
								),
					"ORDER"	=>array("user_image", "`releaving_date`", "`releaving_last_position`", "`releaving_exp_letter_sent_date`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`notice_id`");
		$this->condition = array(									
								);		
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		$num_rows_total= $this->Count();	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								"<div class=\"avatar\"><img class=\"img-avatar\" src=\"".getResizeImage($row["user_image"],50)."\" height=\"40px\"></div>",
								dateView($row["releaving_date"], "DATE"),
								viewText($row["releaving_last_position"]),
								$row["releaving_is_exp_letter_sent"] ? dateView($row['releaving_exp_letter_sent_date'], 'FULL') : 'No',
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">    
    <a class=\"dropdown-item redirect\" href=\"experienceletter/".md5($row['releaving_id'])."\"><i class=\"fa fa-edit fa-fw\"></i> Update</a>
	<a class=\"dropdown-item\" target=\"_blank\" href=\"".DOC::EMPEXPLETTER(md5($row['releaving_id']))."\"><i class=\"fa fa-download fa-fw\"></i> Download Document </a>
  </div>
</div>",
				$row['notice_id']
			);
		}
		return json_encode($output);		
	}
	
}
?>