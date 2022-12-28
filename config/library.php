<?php
function prd($data)
{
	echo "<pre>";
	if(!empty($data)){
		if(is_array($data) || is_object($data))
			print_r($data);
		else
			echo $data;
	}
	else
		echo "Variable ".getVar($data)." is Empty";
	echo "</pre>";
}
function getBaseAmount($amount, $currency, $baseCurrency = 'GBP'){
	$currencyExchange = array(
		'GBP' => 1,
		'USD' => 1.2638,
		'EUR' => 1.1532,
		'AED' => 5.02
	);
	return round($amount * ($currencyExchange[$baseCurrency]/$currencyExchange[$currency]), 2);
	//return round($amount * ($currencyExchange[$currency] / $currencyExchange[$baseCurrency]), 2);
}

function getCurrencySymbol($currency = 'GBP'){
	$currency = $currency == '' ? 'GBP' : $currency;
	$symbolArray = array(
		'GBP' => '£',
		'USD' => '$',
		'EUR' => '€',
		'AED' => 'د.إ',
		'INR' => '₹',
		'PKR' => '₨',
	);
	return isset($symbolArray[$currency]) ? $symbolArray[$currency] : '';
}

function checkData($str)
{
	if(!is_array($str))
	return trim($str);
}

function getLoginId()
{
	return isset($_SESSION['app_log_type']) ? ($_SESSION['app_log_type'] == "E" ? $_SESSION['user_id']:$_SESSION['customer_id']) : false;	
}

function admin()
{
	if(!isAdmin())
	{
		die("You Don't have Permission to Access this page.");
	}
}
function customer()
{
	if(!isCustomer())
	{
		die("You Don't have Permission to Access this page.");
	}
}
function getConditionFormatString($field, $operator, $value){
	if(in_array($value, array("null", "NULL")))
		return " `$field` $operator ".sanitizePostData($value)." ";
	else
		return " `$field` $operator '".sanitizePostData($value)."' ";
}

	
function getApiResult($url){
	return parseXmlToArray(file_get_contents($url));
}
function parseXmlToArray($xmlData){
	$new = simplexml_load_string($xmlData); 
	$con = json_encode($new);  
	return json_decode($con, true); 
}
function getData($string, $default=NULL)
{
	if(!is_array($string) && !empty($string))
		return $string;
	elseif(is_array($string))
		return $string;
	else
		return $default;
}
function orderDelayLevel($order_date, $class=''){
	$maxDayRang = 20;
	$delayDayName= '';
	$delayDay  = round(max(0,(strtotime(date('Y-m-d')) - strtotime(date('Y-m-d', strtotime($order_date))))) / (24*60*60));
	if(date('Y-m-d', strtotime($order_date)) == date('Y-m-d'))
	$delayDayName = 'Today';
	elseif(date('Y-m-d', strtotime($order_date)) == date('Y-m-d', strtotime('-1 day')))
	$delayDayName = 'Yesterday';
	$colorRange = array('FF8000', 'FF9999', 'ff8080', 'FF6666', 'ff4d4d', 'FF3333', 'ff1a1a', 'FF0000', 'e60000', 'CC0000', 'b30000', '990000');
	$colorCode = $colorRange[max(0,((min($maxDayRang, $delayDay)/$maxDayRang)*count($colorRange))-1)];
	return "<span title=\"Delay $delayDay day(s)\" class=\"badge $class\" style=\"background-color:#$colorCode;\">".($delayDayName != '' ? $delayDayName : (sprintf('%02d', $delayDay))." day")." </span>";
}
function setData($field, $default =''){
	if(isset($field))
		return is_array($field) ? array_filter($field) : trim($field);
	else
		return $default;
		
}
function getUserType($reverse=true)
{
	if(isset($_SESSION['app_log_type']))
	{
		if($reverse)
			return $_SESSION['app_log_type'];
		else
			return $_SESSION['app_log_type']=="E"?"C":"E";
	}
	else
		return false;
}

function getObjectPronoun($pronoun){
	$pronounObjectArray = array(
		'he'=>'his',
		'she'=>'her',
		'they'=>'their'
	);
	return isset($pronounObjectArray[$pronoun]) ? $pronounObjectArray[$pronoun] : 'his';
}

function isAdminAccess(){
	return isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'];
}

function isAdmin()
{
	if(isset($_SESSION['app_log_type']) && $_SESSION['app_log_type'] == "E")
		return $_SESSION['user_id'];
	else
		return false;
}

function isAdminRole(){
	return ((isAdmin() && $_SESSION['user_type_id'] == ADMIN_ROLE ) || isAdminAccess()) ? true : false;
}

/*function permission($permission=NULL)
{
	if($permission != NULL)
	{
		$permission_user = explode("|", $permission);
		if(count($permission) > 0 )
		{
			if(in_array('CUSTOMER', $permission_user))
			{
				if(isCustomer())
				{
					
				}
			}
			return true;
		}
		else
			return false;
	}
	else
		return false;
}*/

function getPdfPassword($passwword=null){
	if($passwword != null){
		return strtoupper(trim( preg_replace('/\s+/', '', $passwword)));
	}
	else
		return '';
}

function isCustomer()
{
	if(isset($_SESSION['app_log_type']) && $_SESSION['app_log_type'] != "E")
		return $_SESSION['customer_id'];
	else
		return false;
}

function whoIs()
{
	return (isset($_SESSION['app_log_type']) && $_SESSION['app_log_type'] == "E")? "ADMIN":"CUSTOMER";
}

function applyAnchor($value, $protocols = array('http', 'mail')){
	$attr = '';
                
        $links = array();
        
        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);
        
        // Extract text links for each protocol
        foreach ((array)$protocols as $protocol) {
            switch ($protocol) {
                case 'http':
                case 'https':   $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a target=\"_blank\" $attr href=\"$protocol://$link\">$link</a>") . '>'; }, $value); break;
                case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a target=\"_blank\" $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
                case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a target=\"_blank\" $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\">{$match[0]}</a>") . '>'; }, $value); break;
                default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a target=\"_blank\" $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
            }
        }
        
        // Insert all link
        return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
}

function statusView($id, $status)
{
	$html="<div>
	<label class=\"switch switch-text switch-pill switch-success\">
		<input onchange=\"statusAction(this);\" value=\"$id\" name=\"radio_check_btn_$id\" id=\"radio_check_btn_$id\" class=\"switch-input\" ".(($status==1)?"checked":"")." type=\"checkbox\">
		<span class=\"switch-label\" data-on=\"On\" data-off=\"Off\"></span>
		<span class=\"switch-handle\"></span>
	</label>
	</div>";
	return $html;
}

function actionView($optionArray){
	global $app;
	$html = "<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-lg m-t-2\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">";
  if(count($optionArray))
  {
	  foreach($optionArray as $option)
	  {
  		$html .= "<a class=\"dropdown-item $option[class]\" href=\"$option[url]\"><i class=\"fa $option[icon] fa-lg m-t-2\"></i> $option[name]</a>";
	  }
  }
  
  $html .= "</div></div>";
  return $html;
}

function strip_slashes($var) {
       if(is_array($var)){
		  foreach($var as $key=>$value)
		  $var[$key] = strip_slashes($value);
		  return $var;
	   }
	   else
	   return stripslashes($var);
}

function getUnSubscribeUrl($email){
	global $app;
	$time = time();
	return $app->siteUrl("unsubscribe.php?email=$email&time=$time&hash=".md5(trim(strtolower($email).$time)));
}

function getProgressColor($progress = 0){
	return $progress>80 ? "#229954"  : ($progress>60 ? "#f1c40f"  : ($progress>40 ? "#f1c40f"  : "#e74c3c"));
}

function getColor($position=-1)
{
	$backgroundColorData = array('#FF6384', '#36A2EB', '#FFCE56', '#E91E63', '#F44336', '#9C27B0', '#00BCD4', '#CDDC39', '#009688', '#673AB7', '#4CAF50', '#3F51B5', '#FFEB3B', '#FF9800', '#FF5722', '#795548', '#9E9E9E', '#607D8B');	
	
	if($position == -1)
	$position = rand(0, count($backgroundColorData)-1);
	
	if($position < count($backgroundColorData))
		return $backgroundColorData[$position];
	else
		return $backgroundColorData[$position%count($backgroundColorData)];
}

function html_balance($html, $remove_empty=true) {
        if (!extension_loaded('dom'))
            return $html;

        if (!trim($html))
            return $html;

        $doc = new DomDocument();
        $xhtml = '<?xml encoding="utf-8"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>'
            // Wrap the content in a <div> because libxml would use a <p>
            . "<div>$html</div>";
        $doc->encoding = 'utf-8';
        $doc->preserveWhitespace = false;
        $doc->recover = true;
        if (false === @$doc->loadHTML($xhtml))
            return $html;

        if ($remove_empty) {
            // Remove empty nodes
            $xpath = new DOMXPath($doc);
            static $eE = array('area'=>1, 'br'=>1, 'col'=>1, 'embed'=>1,
                    'iframe' => 1, 'hr'=>1, 'img'=>1, 'input'=>1,
                    'isindex'=>1, 'param'=>1);
            do {
                $done = true;
                $nodes = $xpath->query('//*[not(text()) and not(node())]');
                foreach ($nodes as $n) {
                    if (isset($eE[$n->nodeName]))
                        continue;
                    $n->parentNode->removeChild($n);
                    $done = false;
                }
            } while (!$done);
        }

        static $phpversion;
        if (!isset($phpversion))
            $phpversion = phpversion();

        $body = $doc->getElementsByTagName('body');
        if (!$body->length)
            return $html;

        if ($phpversion > '5.3.6') {
            $html = $doc->saveHTML($doc->getElementsByTagName('body')->item(0)->firstChild);
        }
        else {
            $html = $doc->saveHTML();
            $html = preg_replace('`^<!DOCTYPE.+?>|<\?xml .+?>|</?html>|</?body>|</?head>|<meta .+?/?>`', '', $html); # <?php
        }
        return preg_replace('`^<div>|</div>$`', '', trim($html));
    }

function secureUrl($url)
{
	return str_replace( 'http://', 'https://', $url );
}
function unSecureUrl($url)
{
	return str_replace( 'https://', 'http://', $url );
}

function gePassword($length = 8)
{
	$characterString = "AaBbCcDdEeFfGgHhJjKkLMmNnPpQqRrSsTtUuVvWwXxYyZz";
	$specialCharacterString = "!_-";
	$numberString = rand(0,99999999);
	return substr(str_shuffle($characterString.$specialCharacterString.$numberString.$numberString),0,$length);
}

function image($path, $size=80, $circle=false)
{
	return "<img alt=\"image\" src=\"$path\" style=\"".(($circle?"border-radius: 50%;":""))."\" width=\"".$size."px\" />";
}

function evalString($inputString, $varibaleName, $varibaleValue, $encloseTag=NULL)
{
	$parseString = $encloseTag==NULL?str_replace("{{{$varibaleName}}}", "$varibaleValue", $inputString):str_replace("{{{$varibaleName}}}", "<$encloseTag>$varibaleValue</$encloseTag>", $inputString);
	eval("\$outputString = \"$parseString\";");
	return $outputString;
}

function sanitizePostData($array)
{
	global $conn;
	$data	=	array();
	if(is_array($array) && !empty($array))
	{
		foreach($array as $key=>$val)
		{
			if(is_array($val))
				$data[$key]=sanitizePostData($val);
			else
				$data[$key]	=trim(mysqli_real_escape_string($conn,$val));
		}
		return $data;
		
	}
	elseif(is_array($array))
		return NULL;
	else
		return mysqli_real_escape_string($conn, $array);
}

function status($status)
{
	return $status?"Activated":"Deactivated";
}

function icon($label, $text="", $color="")
{
	return $label?"<i style=\"color:#4DBD74;\" title=\"Verified\" class=\"fa fa-check-circle fa-lg m-t-2\"></i> <span>$text</span>":"<i style=\"color:#F86C6B;\" title=\"Unverified\" class=\"fa fa-ban fa-lg m-t-2\"></i> <span>$text</span>";
}

function viewText($text)
{
	return htmlspecialchars($text);
}

function limitText($string, $length=0)
{
	$string = trim($string);
	return (strlen($string) > $length && $length!=0) ? (trim(substr($string, 0, $length))."…") : $string;	
}

function dateView($date, $format = "FULL")
{
	if($format == "FULL")
		return date("D d-M Y h:i A", strtotime($date));
	if($format == "SMALL")
		return date("d-M D h:i A", strtotime($date));
	if($format == "MONTH")
		return date("d-M", strtotime($date));
	if($format == "NOW")
		return time_elapsed_string($date);
	if($format == "NOWDATE")
		return time_elapsed_string($date)." ".date("D, d-M h:i A", strtotime($date));
	if($format == "DATE")
		return date("d-M Y", strtotime($date));
	if($format == "TIME")
		return date("h:i A", strtotime($date));
	if($format == "UNIX")
		return date("D M d Y H:i:s TO", strtotime($date)); 
		//Tue Aug 29 2017 17:28:57 GMT+0530
		//Fri Sep 08 2017 15:35:25 BST+0100
}

function getDuration($init)
{
	$hour = floor($init / 3600);
	$minute = floor(($init / 60) % 60);
	$second = $init % 60;
	return ($hour ? ($hour."h ") : "") . ($minute ? ($minute . "m ") : "") .($minute==0 && $second ? ($second."s"):"");
}


function TZDate($date, $TimeZone)
{
	return date("D M d Y H:i:s", strtotime($date)); 
}

function is_email($email_id)
{
	return filter_var(strtolower($email_id), FILTER_VALIDATE_EMAIL);
}

		  
function beutifyMobileNumber($number, $code=44){
  $number = trim($number, "+");
  $number = ltrim($number, "44");
  $number = ltrim($number, "0");
  $number = ltrim($number, "44");
  $number = "+" .$code . " " .preg_replace("/\s+/","",$number);
  
  return $number;
}

function getDateTimeDiff($fromDate, $toDate){
	return getTimeformatFromSeconds(abs(strtotime($fromDate) - strtotime($toDate)));
}

function getTimeformatFromSeconds($init){
	$day = floor($init / (24*3600));
	$hour = floor(($init % (24*3600)) / 3600);
	$minute = floor(($init / 60) % 60);
	$second = $init % 60;
	return ($day ? "$day Day " : "") . ($hour ? "$hour Hour " : "") . ($minute ? "$minute Minute " : "") .(($minute==0 && $second) ? "$second Seconds" : "");
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function loadCSS()
{
	global $app, $autoloadCSS;
	$cssArray = $autoloadCSS;
	if(ENABLE_JS_CSS_CACHE)
	{
		$css="";
		if(!empty($cssArray))
		{
			$cssCacheFilename=md5(implode("-",$cssArray)).".css";
			if(!isCached($cssCacheFilename))
			{
				foreach($cssArray as $cssFile)
				{
					$css.= file_get_contents($app->basePath("css/$cssFile.css"));
				}
				writeCache($cssCacheFilename, minify_css($css));
			}
			return "<link type=\"text/css\" href=\"".$app->cachePath($cssCacheFilename)."\" rel=\"stylesheet\" />";
		}
	}
	else
	{
		foreach($autoloadCSS as $cssFile)
		echo "<link type=\"text/css\" href=\"".$app->cssPath($cssFile)."\" rel=\"stylesheet\"> ";
	}
}

function loadJS($jsArray)
{
	global $app;
	$js="";
	if(!empty($jsArray))
	{
		$jsCacheFilename=md5(implode("-",$jsArray)).".js";
		if(!isCached($jsCacheFilename))
		{
			foreach($jsArray as $jsFile)
			{
				$js.= file_get_contents($app->basePath("js/$jsFile.js"));
			}
			writeCache($jsCacheFilename, minify_js($js));
		}
		return "<script src=\"".$app->cachePath($jsCacheFilename)."\"></script>";
	}
}

function loadSystemEntityConstants(){
	global $app;
	$entityFile = Entities::getEntitiesConstantsFilename();
	if(!isCached($entityFile))
	{
		$entityList = Entities::getAllowedConstantsEntity();
		$entityData = null;
		if($entityList){
			foreach($entityList as $_entity){
				$entityData .= "defined('$_entity[entities_key]') OR define('$_entity[entities_key]', ".(is_numeric($_entity['entities_values']) ? $_entity['entities_values'] : "'".addslashes($_entity['entities_values'])."'").");\n";
			}
		}
		if($entityData != null)
		writeCache($entityFile, "<?php \n".$entityData."?>");
	}
	include_once(CACHE_ROOT_DIR.$entityFile);
}

function isCached($file)
{
	if(file_exists(CACHE_ROOT_DIR."/".$file))
		return true;
	else
		return false;
}

function unlinkCacheFile($file){
	if(file_exists(CACHE_ROOT_DIR."/".$file))
		unlink(CACHE_ROOT_DIR."/".$file);
}

function writeCache($file, $data=NULL)
{
	global $deafultHtaccessContent;
	if(!is_dir(CACHE_ROOT_DIR))
	{
		mkdir(CACHE_ROOT_DIR, 0777, true);
	}
	
	if(file_exists(CACHE_ROOT_DIR."/".$file))
		unlink(CACHE_ROOT_DIR."/".$file);
	elseif($data!=NULL)
		file_put_contents(CACHE_ROOT_DIR."/".$file,$data);
}

function saveTempFile($file, $data=NULL)
{
	global $deafultHtaccessContent;
	if(!is_dir(CACHE_ROOT_DIR."/temp"))
	{
		mkdir(CACHE_ROOT_DIR."/temp", 0777, true);
	}
	
	if(file_exists(CACHE_ROOT_DIR."/temp/".$file))
		unlink(CACHE_ROOT_DIR."/temp/".$file);
	elseif($data!=NULL)
		file_put_contents(CACHE_ROOT_DIR."/temp/".$file,$data);
}

function getTempFilePath($filename)
{
	return CACHE_ROOT_DIR."/temp/".$filename;
}

function getFcmMessageIcon($aouth)
{
	global $app;
	if($aouth == md5("www.consolekiller.co.uk"))
		return $app->basePath("img/fcmicon/consolekiller.png");
	elseif($aouth == md5("www.mac4sale.co.uk"))
		return $app->basePath("img/fcmicon/mac4sale.png");
	elseif($aouth == md5("www.technocart.co.uk"))
		return $app->basePath("img/fcmicon/technocart.png");
	elseif($aouth == md5("www.refurbmac.co.uk"))
		return $app->basePath("img/fcmicon/refurbmac.png");
	elseif($aouth == md5("www.safepcdirect.co.uk"))
		return $app->basePath("img/fcmicon/safepcdirect.png");
	elseif($aouth == md5("www.consolekillerpc.co.uk"))
		return $app->basePath("img/fcmicon/consolekillerpc.png");
	elseif($aouth == md5("www.amazon.com"))
		return $app->basePath("img/fcmicon/amazonsale.png");
	else
		return $app->basePath("img/fcmicon/logo.png");
}
function getDomain($url){
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)){
        return $regs['domain'];
    }
    return FALSE;
}

function getWebsiteIdByAouth($aouth){
	$store = new Store();
	$storeList = $store->getAll();
	$store_id = 0;
	foreach($storeList as $_store)
	{
		if(md5("www.".strtolower(trim(getDomain($_store['store_link'])))) == $aouth)
		{
			$store_id = $_store['store_id'];
			break;
		}
	}
	return $store_id;
}

function drawCollectionProcedure($id){
	global $app, $parameter0;
	$action = $parameter0;
return "<nav class=\"tabbable\"><div class=\"nav nav-tabs\"  role=\"tablist\">
  <li role=\"presentation nav-item nav-link\" class=\"$action text-center ".(($action=="updatecollection"?"active":""))."\"><a class=\"redirect\" href=\"".$app->basePath("updatecollection/$id")."\"><i class=\"fa fa-info-circle\"></i> <span class=\"hidden-xs\">Information</span></a></li>
  <li role=\"presentation nav-item nav-link\" class=\"text-center ".(($action=="collectionroute"?"active":""))."\"><a class=\"redirect\" href=\"".$app->basePath("collectionroute/$id")."\"><i class=\"fa fa-map\"></i> <span class=\"hidden-xs\">Route</span></a></li>
  <li role=\"presentation nav-item nav-link\" class=\"text-center ".(($action=="viewcollectionmedia"?"active":""))."\"><a class=\"redirect\" href=\"".$app->basePath("viewcollectionmedia/$id")."\"><i class=\"fa fa-image\"></i> <span class=\"hidden-xs\">Media</span></a></li>
  <li role=\"presentation nav-item nav-link\" class=\"text-center ".(($action=="managecollection"?"active":""))."\"><a class=\"redirect\" href=\"".$app->basePath("managecollection/$id")."\"><i class=\"fa fa-check-circle\"></i> <span class=\"hidden-xs\">Validation</span></a></li>
  <li role=\"presentation nav-item nav-link\" class=\"text-center ".(($action=="processcollection"?"active":""))."\"><a class=\"redirect\" href=\"".$app->basePath("processcollection/$id")."\"><i class=\"fa fa-tasks\"></i> <span class=\"hidden-xs\">Process</span></a></li>
  <li role=\"presentation nav-item nav-link\" class=\"text-center\"><a class=\"\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$id|W', 'Log Report')\"><i class=\"fa fa-comments\"></i> <span class=\"hidden-xs\">Log</span></a></li>
  <li role=\"presentation nav-item nav-link\" class=\"text-center ".(($action=="collectionreports"?"active":""))."\"><a class=\"redirect\" href=\"".$app->basePath("collectionreports/$id")."\"><i class=\"fa fa-file-pdf-o\"></i> <span class=\"hidden-xs\">Reports</span></a></li>
</div></nav >";
}

function getOptionPaid($value="")
{
	return 	"<option value=\"\"> -- Select Paid Status -- </option>".
			"<option value=\"".PAY_STATUS_PAID."\" ".($value==PAY_STATUS_PAID?"selected":"").">".PAY_STATUS_PAID."</option>".
			"<option value=\"".PAY_STATUS_UNPAID."\" ".($value==PAY_STATUS_UNPAID?"selected":"").">".PAY_STATUS_UNPAID."</option>";
}

function getOptionPayMode($value="")
{
	return 	"<option value=\"\"> -- Select Payment Mode -- </option>".
			"<option value=\"".PAY_MODE_CHECK."\" ".($value==PAY_MODE_CHECK?"selected":"").">".PAY_MODE_CHECK."</option>".
			"<option value=\"".PAY_MODE_ONLINE."\" ".($value==PAY_MODE_ONLINE?"selected":"").">".PAY_MODE_ONLINE."</option>".
			"<option value=\"".PAY_MODE_CASH."\" ".($value==PAY_MODE_CASH?"selected":"").">".PAY_MODE_CASH."</option>".
			"<option value=\"".PAY_MODE_OTHER."\" ".($value==PAY_MODE_OTHER?"selected":"").">".PAY_MODE_OTHER."</option>";
}

function getCurrencyType($value="")
{
	return 	"<option value=\"\"> -- Select currency -- </option>".
			"<option value=\"".CURRENCY_GBP."\" ".($value==CURRENCY_GBP?"selected":"").">".CURRENCY_GBP."</option>".
			"<option value=\"".CURRENCY_AED."\" ".($value==CURRENCY_AED?"selected":"").">".CURRENCY_AED."</option>".
			"<option value=\"".CURRENCY_INR."\" ".($value==CURRENCY_INR?"selected":"").">".CURRENCY_INR."</option>".
			"<option value=\"".CURRENCY_PKR."\" ".($value==CURRENCY_PKR?"selected":"").">".CURRENCY_PKR."</option>".
			"<option value=\"".CURRENCY_USD."\" ".($value==CURRENCY_USD?"selected":"").">".CURRENCY_USD."</option>";
}

function getUserGender($value="")
{
	return 	"<option value=\"\"> -- Select gender -- </option>".
			"<option value=\"".USER_GENDER_MALE."\" ".($value==USER_GENDER_MALE?"selected":"").">".USER_GENDER_MALE."</option>".
			"<option value=\"".USER_GENDER_FEMALE."\" ".($value==USER_GENDER_FEMALE?"selected":"").">".USER_GENDER_FEMALE."</option>".
			"<option value=\"".USER_GENDER_OTHER."\" ".($value==USER_GENDER_OTHER?"selected":"").">".USER_GENDER_OTHER."</option>";
}

function getCiaOptions($value = ""){
	$cia_array = array("C","I","A");
	$html_option = "<option value=\"\"> -- Select CIA Code -- </option>";
	foreach($cia_array as $cia_code){
		$html_option .= "<option ".($cia_code == $value ? "selected" : "")." value=\"$cia_code\"> $cia_code </option>";
	}
	return $html_option;
}

function getImpactOptions($value = ""){
	$impact_array = array("1","2","3","4","5");
	$html_option = "<option value=\"\"> -- Select impact -- </option>";
	foreach($impact_array as $impact_array){
		$html_option .= "<option ".($impact_array == $value ? "selected" : "")." value=\"$impact_array\"> $impact_array </option>";
	}
	return $html_option;
}

function getRisk($value = ""){
	$impact_array = array("1","2","3","4","5");
	$html_option = "<option value=\"\"> -- Select Risk -- </option>";
	foreach($impact_array as $impact_array){
		$html_option .= "<option ".($impact_array == $value ? "selected" : "")." value=\"$impact_array\"> $impact_array </option>";
	}
	return $html_option;
}

function getOvarallRisk($value = ""){
	$impact_array = array("1","2","3","4","5");
	$html_option = "<option value=\"\"> -- Select Overall Risk -- </option>";
	foreach($impact_array as $impact_array){
		$html_option .= "<option ".($impact_array == $value ? "selected" : "")." value=\"$impact_array\"> $impact_array </option>";
	}
	return $html_option;
}

function getProbabilityOfRiskOccurance($value = ""){
	$impact_array = array("1","2","3","4","5");
	$html_option = "<option value=\"\"> -- Select Prob. Risk Occurance -- </option>";
	foreach($impact_array as $impact_array){
		$html_option .= "<option ".($impact_array == $value ? "selected" : "")." value=\"$impact_array\"> $impact_array </option>";
	}
	return $html_option;
}

function getRiskRatingOptions($value = ""){
	$risk_rating_array = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25");
	$html_option = "<option value=\"\"> -- Select risk rating -- </option>";
	foreach($risk_rating_array as $risk_rating){
		$html_option .= "<option ".($risk_rating == $value ? "selected" : "")." value=\"$risk_rating\"> $risk_rating </option>";
	}
	return $html_option;
}

function move_file($source, $detination){
	$destination_info  = pathinfo($detination);
	if(!is_dir($destination_info['dirname']))
	mkdir($destination_info['dirname'], 0777, true);
	
	if(file_exists($source))
	{
		return copy($source, $detination) ? true : false;
	}
	else
		return false;
}

function unlinkFile($path){
	if($path != ""){
		if(file_exists(BP.$path)){
			unlink(BP.$path);
		}
	}
}

function getDestructionMethod($value="")
{
	return 	"<option value=\"\"> -- Select Destruction Method -- </option>".
			"<option value=\"".DESRUCTION_METHOD_SECURE_ERASING."\" ".($value==DESRUCTION_METHOD_SECURE_ERASING?"selected":"").">".DESRUCTION_METHOD_SECURE_ERASING."</option>";
}

function getCompanyCheckListTypes($value="")
{
	return 	"<option value=\"\"> -- Select Checklist Mode -- </option>".
			"<option value=\"".COMP_CHK_LIST_INDUCTION."\" ".($value==COMP_CHK_LIST_INDUCTION?"selected":"").">".COMP_CHK_LIST_INDUCTION."</option>".
			"<option value=\"".COMP_CHK_LIST_TERMINATION."\" ".($value==COMP_CHK_LIST_TERMINATION?"selected":"").">".COMP_CHK_LIST_TERMINATION."</option>".
			"<option value=\"".COMP_CHK_LIST_TELEWORKING."\" ".($value==COMP_CHK_LIST_TELEWORKING?"selected":"").">".COMP_CHK_LIST_TELEWORKING."</option>".
			"<option value=\"".COMP_CHK_LIST_NON_CONFORMANCE."\" ".($value==COMP_CHK_LIST_NON_CONFORMANCE?"selected":"").">".COMP_CHK_LIST_NON_CONFORMANCE."</option>";
}

function getResizeImage($image, $width=0, $height=0)
{
	
	global $app;
	if(trim($image)==NULL || trim($image)=="" || !file_exists(BP.$image))
		$base_image = BP.DEFAULT_IMAGE_PATH;
	else
		$base_image = BP.$image;
	
	list($o_width, $o_height, $o_type, $o_attr) = getimagesize($base_image);
	
	$image_name = pathinfo($image);
	$extension = strtolower(isset($image_name['extension']) ? $image_name['extension'] : 'png');		
	
	if($height == 0)
	{
		$height = $width;
	}
	if($width == 0)
	{
		$width  = $o_width;
		$height = $o_height;
	}
	$folder_name = $width."x".$height;
	
	$new_file_name = strtolower("media/$folder_name/$image");
	$new_file_path = CACHE_ROOT_DIR.$new_file_name;
	//echo $new_file_path;die;
	if(!file_exists($new_file_path))
	{
		$new_dir_path = dirname($new_file_path);
		//echo $new_dir_path;
		if(!file_exists($new_dir_path))
		mkdir($new_dir_path, 0777, true);
		$new_image = imagecreatetruecolor( $width, $height );
		if($extension == "png")
			$current_image = @imagecreatefrompng( $base_image );
		if($extension == "jpg" || $extension == "jpeg")
			$current_image = imagecreatefromjpeg( $base_image );
			
		imagealphablending($new_image, false);
		imagesavealpha($new_image,true);
		$transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
		imagefilledrectangle($new_image, 0, 0, $width, $height, $transparent);		
		@imagecopyresampled( $new_image, $current_image, 0, 0, 0, 0, $width, $height, $o_width, $o_height );
		
		
		if($extension == "png")
		imagepng($new_image, $new_file_path, 9 );
		if($extension == "jpg" || $extension == "jpeg")
		imagejpeg( $new_image, $new_file_path, 90 );
		
	}
	$file_relative_path = CACHE_DIR_NAME.$new_file_name;
	return $app->siteUrl($file_relative_path);
}

function getDirectorySeparatorPath($file_name = NULL)
{
	if($file_name == NULL || strlen($file_name)< 2)
		$file_name = str_shuffle("abcdefghijklmnopqrstuvwxyz1234567890");
	$str = preg_replace('/[^\w]/', '', basename($file_name));
	return substr($str, 0, 1)."/".substr($str, 1, 1)."/";
}

function getTemplateView($template_name, $parameter){
	global $app;
	if(file_exists($app->sitePath($template_name))){
		extract($parameter);
		$template = file_get_contents($app->sitePath($template_name));
		
		$pattern = '#{{(.*?)}}#';
		preg_match_all($pattern,$template,$matches);
		if(count($matches[0]))
		foreach($matches[0] as $variable)
		{
			$template = str_replace($variable, "$".trim(trim(trim($variable,"{"),"}")), $template);
		}		
		eval("\$data = \"$template\";");
		return $data;		
	}
	return NULL;
}

function getDropDownOptionFromArray($array, $value){
	$html = "";
	if(count($array)){
		foreach($array as $key => $val){			
			$html .= "<option ".($val == $value ? "selected" : "")." value=\"".htmlspecialchars($val)."\">".htmlspecialchars($val)."</option>";
		}
	}
	return $html;
}

function getBooleanRadioOption($field){
	$html = "";
	$array = array("Yes", "No");
	$i=0;
	foreach($array as $val){			
		$html .= "<div class=\"col-xs-6\">
				<label for=\"radio_$field[attribute_id]_".$i++."\" class=\"switch switch-icon switch-pill switch-".($val == 'Yes' ? "success" : "danger")."\">
				<input class=\"switch-input radio_$field[attribute_id]\" ".($field['values_data'] == $val ? "checked" : "")." id=\"radio_$field[attribute_id]_".$i++."\" value=\"".htmlspecialchars($val)."\" name=\"attribute[$field[attribute_id]]\" type=\"radio\">
				<span class=\"switch-label\" data-on=\"✓\" data-off=\"✕\"></span> <span class=\"switch-handle\"></span> </label> ".htmlspecialchars($val)."</div>";
	}
	
	return $html;
}

function getShipmentLabelBlock($_slabel, $options = array()){
	global $app;
$confirmMessage = $_slabel['label_downloads'] ? "<i class=\'fa fa-fwarning text-warning\'></i> Warning: This Label is downloaded <b>{$_slabel['label_downloads']}</b> times.<br/>" : "";
	return "<div class=\"shipmentlabel_".md5($_slabel['label_id'])." col-xs-12 col-sm-6 col-md-4\">
				<div class=\"card\">
					<div class=\"card-body p-0 d-flex align-items-center justify-content-between p-1\">
						<div class=\"pl-0 justify-content-start\"><img title=\"Uploaded by {$_slabel['user_fname']} {$_slabel['user_lname']}\" class=\"img-circle\" alt=\"{$_slabel['user_fname']}\" src=\"".getResizeImage($_slabel["user_image"],50)."\" height=\"32px\"> {$_slabel['user_fname']}</div>
						<div class=\"px-0 justify-content-center\">
							<div class=\"text-value-sm\"><a download=\"\" href=\"{$app->basePath($_slabel['label_path'])}\" class=\"btn btn-success\"><i class=\"fa fa-fw fa-download\" aria-hidden=\"true\"></i> Label</a></div>
						</div>
					</div>
					<div class=\"d-flex align-items-center justify-content-between px-1 pb-1\">
						<div class=\"pl-0 justify-content-start\">
							<img alt=\"{$_slabel['shipment_type_name']}\" style=\"height:40px\" src=\"{$app->basePath($_slabel['shipment_type_image'])}\"/> &nbsp; <span class=\"badge badge-".($_slabel['label_type'] == 'Return' ? "info":"success")."\">{$_slabel['label_type']} Label</span>
						</div>
						<div class=\"px-0 justify-content-end text-right\">
							<strong class=\"text-info\">{$_slabel['label_shipment_code']}</strong>
							".((isset($_slabel['label_details']) && !empty($_slabel['label_details'])) ? "<p class=\"mb-0 text-label-detail\"> {$_slabel['label_details']}</p>" : "")."
						</div>
					</div>
					<div class=\"d-flex align-items-center justify-content-between px-1 pb-1 \">
						<div class=\"pl-0 justify-content-start\"> <a onclick=\"confirmMessage.Set('{$confirmMessage}Are you sure to cancel this label... ?', '".$options['action']."', '".md5($_slabel['label_id'])."');\" class=\"btnlabelcancel btn btn-outline-danger btn-sm\">Cancel</a> </div>
						<div class=\"px-0 justify-content-center\"><i class=\"fa fa-fw fa-clock-o\" aria-hidden=\"true\"></i> ".dateView($_slabel['label_uploaded_date'], "FULL")."</div>
					</div>
				</div>
			 </div>";
}

function getAttributeFieldForm($field, $divClassName="col-xs-12 col-sm-6 col-md-6 col-lg-4"){
	$html = '<div class="'.$divClassName.'">
              <div class="form-group">
                <label for="attribute_'.$field['attribute_id'].'">'.$field['attribute_name'].'<sup></sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chktext" type="checkbox" name="chkattribute[]" value="'.$field['attribute_id'].'" />
				</span>
                <input class="form-control" onkeyup="getDropdown(this, \'CollectionProcessItem<=>'.$field['attribute_id'].'\',true)" id="attribute_'.$field['attribute_id'].'" name="attribute['.$field['attribute_id'].']" maxlength="50" placeholder="Enter '.$field['attribute_name'].'" type="text" value="'.(isset($field['values_data']) ? $field['values_data'] : "").'">
				 
			  </div>
			  </div>
            </div>';
	if($field['attribute_element_type'] == 'bdropdown'){
	$options = WcItem::getAttributesOptions($field['attribute_id']);
	$html = '<div class="'.$divClassName.'">
              <div class="form-group">
                <label for="attribute_'.$field['attribute_id'].'">'.$field['attribute_name'].'<sup></sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chkdropdown" type="checkbox" name="chkattribute[]" value="'.$field['attribute_id'].'" />
				</span>
                <select id="attribute_'.$field['attribute_id'].'" name="attribute['.$field['attribute_id'].']" class="form-control" size="1">
				<option value=""> - Select '.$field['attribute_name'].' - </option>
				'.getDropDownOptionFromArray($options, $field['values_data']).'</select>				 
			  </div>
			  </div>
            </div>';
	}
	elseif($field['attribute_element_type'] == 'cboolean'){
		$html = '<div class="'.$divClassName.'">
	<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chkboolean" type="checkbox" name="chkattribute[]" value="'.$field['attribute_id'].'" />
				</span>
            <label class="mt-half mb-0" for="attribute_'.$field['attribute_id'].'"><i class="fa fa-stop-circle-o fa-lg m-t-2"></i> &nbsp;'.$field['attribute_name'].'</label>
            <label class="switch switch-icon switch-pill switch-success pull-right">
              <input class="switch-input" id="attribute_'.$field['attribute_id'].'" '.($field['values_data'] == 'Yes' ? "checked" : "").' value="Yes"  name="attribute['.$field['attribute_id'].']" type="checkbox">
              <span class="switch-label" data-on="Yes" data-off="No"></span> <span class="switch-handle"></span> </label>
          </div></div>
		  </div>';
	}
	/*
	elseif($field['attribute_element_type'] == 'cboolean'){
		$html = '<div class="'.$divClassName.'">
              <div class="form-group">
                <label>'.$field['attribute_name'].'<sup></sup></label>
				<div class="input-group">
				<span class="input-group-addon">
					<input class="chkattribute chkboolean" type="checkbox" name="chkattribute[]" value="'.$field['attribute_id'].'" />
				</span>'.getBooleanRadioOption($field).'
                
				 
			  </div>
			  </div>
            </div>';
	}
	*/
	return $html;
}

function formatDate($date){
	if($date != '' && strlen($date) == 8)
	return substr($date, 0, 4).'-'.substr($date, 4, 2).'-'.substr($date, 6, 2);
}

function getRealPOST() {
    $pairs = explode("&", file_get_contents("php://input"));
    $vars = array();
    foreach ($pairs as $pair) {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        $value = urldecode($nv[1]);
        $vars[$name] = $value;
    }
    return $vars;
}

function normalizeString($string){
	return preg_replace("/[^A-Za-z0-9_-]/", '', $string);
}	

function getOs($user_agent) { 

    $os_platform    =   "Unknown OS";

    $os_array       =   array(
							'/windows nt 10/i'		=>	'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile',
							'/okhttp/i'		=>	'Android'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }
    return $os_platform;
}

function getBrowser($user_agent) {

    $browser        =   "Unknown Browser";

    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Mobile',
							'/okhttp/i'=>	'Support App'
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }

    }
    return $browser;
}

function getEntitiyForm($entities_key, $type = null, $entities_title = 'values here', $boxClass="col-sm-12"){
	if($type == null || $type == 'textarea'){
		return "<div class=\"{$boxClass} entity_form_box\">
			<div class=\"form-group\">
				<textarea data-key=\"{$entities_key}\" name=\"{$entities_key}\" rows=\"3\" class=\"form field_entities_field form-control\" placeholder=\"Enter {$entities_title}\">".Entities::getKeyValues($entities_key)."</textarea>
			</div>
			<div class=\"entiry_contoll_box\">
				<label><input class=\"entities_php_allowed\" type=\"checkbox\" value=\"1\" ".(Entities::isPhpAllowed($entities_key) ? "checked" : "")."> Allow constants</label>
			</div>
			<div>
				<a class=\"formfield_entities_button btn btn-info text-white\">Update</a>
			</div>
			<hr/>
		</div>";
	}
	elseif($type == 'input'){
		return "<div class=\"{$boxClass} entity_form_box\">
					<div class=\"row\">
						<div class=\"col-xs-8 col-sm-8 col-md-9 col-lg-10\">
							<div class=\"form-group\">	
								<label>{$entities_title}</label>
								<input data-key=\"{$entities_key}\" name=\"{$entities_key}\" class=\"field_entities_field  form-control\" placeholder=\"Enter {$entities_title}\" value=\"".htmlentities(Entities::getKeyValues($entities_key))."\">
							</div>
							<div class=\"entiry_contoll_box\">
							<label><input class=\"entities_php_allowed\" type=\"checkbox\" value=\"1\" ".(Entities::isPhpAllowed($entities_key) ? "checked" : "")."> Allow constants</label>
									
							</div>
						</div>
						<div class=\"col-xs-4 col-sm-4 col-md-3 col-lg-2 text-right\"><label>&nbsp;</label><br/>
							<a class=\"formfield_entities_button btn btn-info text-white\">Update</a>
						</div>
					</div>
					<hr/>
				</div>";
	}
	elseif($type == 'checkbox'){
		return "<div class=\"{$boxClass} entity_form_box\">					
					<div class=\"form-group\">
						<label><i class=\"fa fa-circle fa-fw\" aria-hidden=\"true\"></i> &nbsp;{$entities_title}</label>
						<label class=\"switch switch-icon switch-pill switch-success pull-right\">
						<input class=\"switch-input field_entities_field field_entities_button\" data-key=\"{$entities_key}\" name=\"{$entities_key}\" value=\"1\"  type=\"checkbox\" ".(Entities::getKeyValues($entities_key) ? "checked" : "").">
							<span class=\"switch-label\" data-on=\"Yes\" data-off=\"No\"></span> 
							<span class=\"switch-handle\"></span> 
						</label>
					</div>
					<div class=\"entiry_contoll_box\">
						<label><input class=\"entities_php_allowed\" type=\"checkbox\" value=\"1\" ".(Entities::isPhpAllowed($entities_key) ? "checked" : "")."> Allow constants</label>
					</div>
					<hr/>
			  </div>";
	}
	else
		return "<div class=\"{$boxClass}\">Type id not available for {$entities_key}</div>";
}
?>