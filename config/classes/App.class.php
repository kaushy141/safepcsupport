<?php
class App extends DB{
	public $siteTitle;
	public $siteDescription;
	public $siteMeta;
	public $siteKeyword;
	public $siteIcon;
	public $siteUrl;
	public $siteUrlUnsecure;
	public $siteName;

	function __construct(){
		if(isset($_SESSION['SITE']))
		{
			$this->siteTitle		=	$_SESSION['SITE']["app_site_name"];
			$this->siteDescription	=	$_SESSION['SITE']["app_site_description"];
			$this->siteMeta			=	$_SESSION['SITE']["app_site_meta"];
			$this->siteKeyword		=	$_SESSION['SITE']["app_site_keyword"];
			$this->siteIcon			=	$_SESSION['SITE']["app_site_icon"];
			$this->siteUrl			=	$_SESSION['SITE']["app_site_url"];
			$this->siteUrlUnsecure	=	$_SESSION['SITE']["app_site_url_unsecure"];
			$this->siteName			=	$_SESSION['SITE']["site_name"];
			return $this;
		}
		else
		{
			$sql="SELECT `app_site_name`, `app_site_description`, `app_site_meta`, `app_site_keyword`, `app_site_icon`, `app_site_url` FROM `app_core_configuration` WHERE 1";
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			$record	= 	$dbc->db_fetch_assoc();

			$_SESSION['SITE']["app_site_name"]			=	$this->siteTitle		=	$record["app_site_name"];
			$_SESSION['SITE']["app_site_description"]	=	$this->siteDescription	=	$record["app_site_description"];
			$_SESSION['SITE']["app_site_meta"]			=	$this->siteMeta			=	$record["app_site_meta"];
			$_SESSION['SITE']["app_site_keyword"]		=	$this->siteKeyword		=	$record["app_site_keyword"];
			$_SESSION['SITE']["app_site_url"]			=	$this->siteUrl			=	$record["app_site_url"];
			$_SESSION['SITE']["app_site_url_unsecure"]	=	$this->siteUrlUnsecure	=	str_replace("https","http",$record["app_site_url"]);
			$_SESSION['SITE']["app_site_icon"]			=	$this->siteIcon			=	$this->imagePath($record["app_site_icon"]);
			$_SESSION['SITE']["site_name"]				=	$this->siteName			=	"safePcSupport";
			return $this;
		}
	}

	function record_count()
	{
		$sql = "SELECT
		 (SELECT COUNT(`wc_id`) FROM `app_wc_record`) as collection,
		 (SELECT COUNT(`complaint_id`) FROM `app_complaint_record`) as complaint,
		 (SELECT COUNT(`user_id`) FROM `app_system_user`) as employee,
		 (SELECT COUNT(`customer_id`) FROM `app_customer`) as customer
		";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}

	function getuserEmailImageByEmail($email_id)
	{
		global $app;
		$sql = "
		SELECT user_image as image FROM app_system_user WHERE LOWER(`user_email`)  = LOWER('$email_id')
		UNION
		SELECT customer_image as image FROM app_customer WHERE LOWER(`customer_email`)  = LOWER('$email_id')
		";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))
		{
			$data = $dbc->db_fetch_assoc(true);
			return $app->imagePath($data['image']);
		}
		else
			return false;
	}

	function refresh()
	{
		if(isset($_SESSION['SITE']))
		unset($_SESSION['SITE']);
		return self::__construct();
	}

	static function sitePath($path=NULL)
	{
		if($path!=NULL)
			return BP.$path;
		else
			return BP;
	}

	function cachePath($path=NULL)
	{
		if($path!=NULL)
			return $this->siteUrl.CACHE_DIR_NAME.$path;
		else
			return $this->siteUrl.CACHE_DIR_NAME;
	}

	function basePath($path=NULL, $secure = true)
	{
		if($path!=NULL)
			return ($secure?$this->siteUrl:$this->siteUrlUnsecure).$path;
		else
			return $secure?$this->siteUrl:$this->siteUrlUnsecure;
	}

	function siteUrl($path=NULL, $secure = true)
	{
		return $this->basePath($path, $secure);
	}

	function jsPath($path=NULL, $secure = true)
	{
		if($path!=NULL)
			return strtolower(($secure?$this->siteUrl:$this->siteUrlUnsecure)."js/".$path.".js?ver=".VERSION);
		else
			return "";
	}

	function cssPath($path=NULL)
	{
		if($path!=NULL)
			return strtolower($this->siteUrl."css/".$path.".css?ver=".VERSION);
		else
			return "";
	}

	function imagePath($path=NULL, $secure = true)
	{
		if(!filter_var($path, FILTER_VALIDATE_URL))
		{
			if(trim($path)!=NULL && trim($path)!="" && file_exists(BP.$path))
				return strtolower(($secure?$this->siteUrl:$this->siteUrlUnsecure).$path);
			else
				return ($secure?$this->siteUrl:$this->siteUrlUnsecure).DEFAULT_IMAGE_PATH;
		}
		else
			return $path;
	}

	function mediaPath($path=NULL, $secure = true)
	{
		return $this->imagePath($path, $secure);
	}

	function xmlPath($path = NULL)
	{
		if($path!=NULL)
		{
			$file = "jrxml/$path.jrxml";
			if(file_exists(BP.$file))
				return BP.$file;
			else
				return false;
		}
		else
			return false;
	}

	function insertIpDetail()
	{
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$sql = "SELECT `ip_info_id` FROM `app_ip_info_table` WHERE `ip_info_ip` = '$ip_address'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)==0)
		{
			$value = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=$ip_address");
			//var_dump($value);
			if(isset($value->geoplugin_status) && $value->geoplugin_status<300)
			{
				$ip_info_ip = $ip_address;
				$ip_info_city = $value->geoplugin_city;
				$ip_info_region = $value->geoplugin_region;
				$ip_info_areacode = $value->geoplugin_areaCode;
				$ip_info_dmacode = $value->geoplugin_dmaCode;
				$ip_info_country_code = $value->geoplugin_countryCode;
				$ip_info_country_name = $value->geoplugin_countryName;
				$ip_info_lat = $value->geoplugin_continentCode;
				$ip_info_long = $value->geoplugin_latitude;
				$ip_info_region_code = $value->geoplugin_longitude;
				$ip_info_region_code = $value->geoplugin_regionCode;
				$ip_info_region_name = $value->geoplugin_regionName;

				$sqlIns = "INSERT INTO `app_ip_info_table`(`ip_info_ip`, `ip_info_city`, `ip_info_region`, `ip_info_areacode`, `ip_info_dmacode`, `ip_info_country_code`, `ip_info_country_name`, `ip_info_lat`, `ip_info_long`, `ip_info_region_code`, `ip_info_region_name`) VALUES ('$ip_info_ip', '$ip_info_city', '$ip_info_region', '$ip_info_areacode', '$ip_info_dmacode', '$ip_info_country_code', '$ip_info_country_name', '$ip_info_lat', '$ip_info_long', '$ip_info_region_code', '$ip_info_region_name')";
				$dbc 	= 	new DB();
				$dbc->db_query($sqlIns);
			}
		}
	}

	function logLogin()
	{
		$log_session_id = session_id();
		$log_user_id = getLoginId();
		$log_out_timeout = isset($_SESSION['user_timeout']) ? $_SESSION['user_timeout'] : 0;
		$sql = "INSERT INTO `app_log_login`(`log_user_id`, `log_session_id`, `log_login_time`, `log_user_type`, `log_ip_address`, `log_out_timeout`, `log_device`) VALUES ('$log_user_id', '$log_session_id', NOW(), '$_SESSION[app_log_type]', '$_SERVER[REMOTE_ADDR]', '$log_out_timeout', '$_SERVER[HTTP_USER_AGENT]')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$log_id = $dbc->db_insert_id();
		$this->insertIpDetail();
		return $log_id;
	}

	function logLogout($log_user_id=NULL, $log_session_id=NULL, $log_user_type=NULL, $log_out_remark = 'LogOut')
	{
		if($log_user_id==NULL && $log_session_id==NULL && $log_user_type==NULL)
		{
			$condition = "`log_id` = '$_SESSION[app_log_login_id]'";
			$login_user_id = getLoginId();
		}
		else
		{
			$condition = "`log_session_id` = '$log_session_id' && `log_user_id` = '$log_user_id' && `log_user_type` = '$log_user_type'";
			$login_user_id = $log_user_id;
		}

		if((isset($_SESSION['app_log_type']) && isAdmin()) || $log_user_type == 'E')
			$user = new Employee($login_user_id);
		else
			$user = new Customer($login_user_id);
		$user->setOffline();

		$sql = "UPDATE `app_log_login` SET `log_logout_time` = NOW(), `log_out_remark` = '$log_out_remark' WHERE $condition";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $result;
	}

	function logLoginChart()
	{
		$sql="SELECT COUNT(`log_id`) as item1, DATE_FORMAT(`log_login_time`, '\'%d-%b\'') as item2 FROM `app_log_login` WHERE 1 GROUP BY DATE_FORMAT(`log_login_time`, '%d%b%Y') ORDER BY `log_login_time` DESC LIMIT 30";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		while($row = $dbc->db_fetch_assoc())
		$data[$row['item2']]=$row['item1'];
		return $this->getChartData(array_reverse($data));
	}

	function logInSignInuser($log_user_id=NULL, $log_session_id=NULL, $log_user_type=NULL, $log_out_remark = ''){
		$_SESSION['app_log_login_id'] = $this->logLogin();
	}

	function logOffUser($log_user_id=NULL, $log_session_id=NULL, $log_user_type=NULL, $log_out_remark = 'LogOff')
	{
		if($log_user_id==NULL && $log_session_id==NULL && $log_user_type==NULL)
		{
			$condition = "`log_id` = '$_SESSION[app_log_login_id]'";
			$login_user_id = getLoginId();
		}
		else
		{
			$condition = "`log_session_id` = '$log_session_id' && `log_user_id` = '$log_user_id' && `log_user_type` = '$log_user_type'";
			$login_user_id = $log_user_id;
		}

		if((isset($_SESSION['app_log_type']) && isAdmin()) || $log_user_type == 'E')
			$user = new Employee($login_user_id);
		else
			$user = new Customer($login_user_id);
		$user->setOffline();

		$sql = "UPDATE `app_log_login` SET `log_logout_time` = NOW(), `log_out_remark` = '$log_out_remark' WHERE $condition";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $result;
	}

	function clearSession()
	{
		$sql="SELECT `log_user_id`, `log_session_id`, `log_user_type`, `log_out_timeout` FROM `app_log_login` WHERE `log_logout_time` is NULL OR `log_logout_time` = '' ORDER BY `log_login_time`";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		while($row = $dbc->db_fetch_assoc())
		{
			if(!file_exists(SESSION_SAVE_PATH.'sess_'.$row['log_session_id']))
			{
				$this->logLogout($row['log_user_id'], $row['log_session_id'], $row['log_user_type']);
			}
			elseif((time() - filemtime(SESSION_SAVE_PATH.'sess_'.$row['log_session_id'])) > ($row['log_out_timeout'] > 0 ? $row['log_out_timeout'] : MAX_IDLE_SESSION_TIMEOUT))
			{
				$this->logLogout($row['log_user_id'], $row['log_session_id'], $row['log_user_type']);
				// 1. commit session if it's started.
				if (session_id()) {
					session_commit();
				}

				// 2. store current session id
				session_start();
				$current_session_id = session_id();
				session_commit();

				// 3. hijack then destroy session specified.
				session_id($row['log_session_id']);
				session_start();
				session_destroy();
				session_unset();
				session_commit();

				// 4. restore current session id. If don't restore it, your current session will refer to the session you just destroyed!
				session_id($current_session_id);
				session_start();
				session_commit();
			}
		}
	}

	static function getFormcode()
	{
		if(!isset($_SESSION['FORMCODESTRING']))
		$_SESSION['FORMCODESTRING'] = str_shuffle("1234567890abcdefghijklmnopqrstuvwxyz597tfyftby4fn4ej9nc3pcb74z4rwz4st4tdarpmbbdqxepyef2pprhxhkpa9juadc5qa3qfhxug8vwukea8hmxv7z8x4sfajybedktrpcrcu589xvmfscfg9pp9uer5");
		return substr($_SESSION['FORMCODESTRING'], rand(0,strlen($_SESSION['FORMCODESTRING'])-(FORMCODE_STRING_LENGTH+1)), FORMCODE_STRING_LENGTH);
	}
	static function checkFormcode($formcode)
	{
		if(isset($_SESSION['FORMCODESTRING']))
			return strpos($_SESSION['FORMCODESTRING'], $formcode) === false ? false : true;
		else
			return false;
	}

	static function delFormcode($formcode)
	{
		if(!isset($_SESSION['FORMCODE']))
		{
			if($position = array_search($formcode, $_SESSION['FORMCODE']) !== false)
			unset($_SESSION['FORMCODE'][$position]);
		}
	}

	function updateLastCronScheduleRun()
	{
		$sql = "UPDATE `aap_application_info` SET `info_app_last_cron_run`= CURRENT_DATE WHERE 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
	}
	function getLastCronScheduleRun()
	{
		$sql = "SELECT `info_app_last_cron_run` FROM `aap_application_info` WHERE 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = $dbc->db_fetch_assoc();
		return $data['info_app_last_cron_run'];
	}


	public static function readTemplate($Templatename, $dataArray)
	{
		global $app;
		extract($dataArray);
		$template_path = BP."template/".$Templatename.".txt";
		if(file_exists($template_path))
		$template = file_get_contents($template_path);
		$pattern = '#{{(.*?)}}#';
		preg_match_all($pattern,$template,$matches);
		if(count($matches[0]))
		foreach($matches[0] as $variable)
		{
			$template = str_replace($variable, "$".trim(trim(trim($variable,"{"),"}")), $template);
		}
		eval("\$data = \"$template\";");
		return nl2br($data);
	}
	public static function exploreApiCredentilas($credentialsKey = NULL)
	{
		global $app;
		$con = json_decode(json_encode(simplexml_load_string(file_get_contents(BP."api/config.xml"))), true);
		return $credentialsKey == NULL ? $con : (isset($con[$credentialsKey]) ? $con[$credentialsKey] : NULL);
	}



}

class Log {
	static function Text($message=NULL, $format='GENERAL'){
		if(!is_dir(LOG_DIR))
		{
			mkdir(LOG_DIR, 0777, true);
		}
		if($message != NULL){

			if($format == 'GENERAL')
			$filename = LOG_DIR.(date("Y-m-d")).".log";
			else
			$filename = LOG_DIR.$format.(date("Y-m-d")).".log";
			file_put_contents($filename, $message ,FILE_APPEND|LOCK_EX);
		}
	}
}

class Signature extends DB{
	public $id;
	public $key_id	=	"signature_id";
	public $table_name	=	"app_signature_capturer";
	public $status	=	"signature_is_used";

	function __construct($id=0){
		$this->id	=	intval($id);
	}

	function getDetailsSecure($signature_id, $signature_code){
		$ip_address = $_SERVER['REMOTE_ADDR'];
		$sql = "SELECT * FROM `".$this->table_name."` WHERE MD5(MD5(`signature_id`)) = '$signature_id' AND MD5(MD5(`signature_code`)) = '$signature_code'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result) == 1)
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
}
?>