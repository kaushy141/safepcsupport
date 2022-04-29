<?php
define('VERSION', '4.3.5');
define('MAX_SESSION_TIMEOUT', 60*60*24);
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('CDS','/');
define('BP', str_replace("\\",CDS,__DIR__).CDS);
date_default_timezone_set('Europe/London');
define('CACHE_ROOT_DIR',BP."var/");
define('SESSION_SAVE_PATH', CACHE_ROOT_DIR."session/");
define('CACHE_DIR_NAME', str_replace(BP,'',CACHE_ROOT_DIR));
$deafultHtaccessContent = "Options -Indexes";
if(!is_dir(SESSION_SAVE_PATH))
{
	mkdir(SESSION_SAVE_PATH, 0777, true);
	file_put_contents(SESSION_SAVE_PATH.".htaccess", $deafultHtaccessContent ,FILE_APPEND|LOCK_EX);
	file_put_contents(CACHE_ROOT_DIR.".htaccess",$deafultHtaccessContent,FILE_APPEND|LOCK_EX);
}
session_save_path(SESSION_SAVE_PATH);
ini_set('session.gc_probability', 1);
ini_set("session.gc_maxlifetime", MAX_SESSION_TIMEOUT);
ini_set("session.cookie_lifetime", MAX_SESSION_TIMEOUT);

define('IP_ADDRESS', isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1'); 

//error_reporting($_SERVER['HTTP_HOST']=='localhost:8081'?E_ALL:E_ALL);
//ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED); 
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

if(isset($_GET['android_app_logged_in_session_id']))
	session_id($_GET['android_app_logged_in_session_id']);
session_start();

if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
	$_POST		=	strip_slashes($_POST);
	$_GET		=	strip_slashes($_GET);
	$_REQUEST	=	strip_slashes($_REQUEST);
}

include(BP."config.php");
include(BP."config/phpqrcode.php");
include(BP."ireport/phpjasperxml.inc.php");
include(BP."ireport/tcpdf/tcpdf.php");
include("config/classes.php");
include("config/minify.php");
include("config/library.php");
include("config/barcode/index.php");

loadSystemEntityConstants();

defined('DEFAULT_IMAGE_PATH')  OR  define('DEFAULT_IMAGE_PATH', 'img/system/default_user_img.jpg');
defined('DEFAULT_USER_IMAGE')  OR  define('DEFAULT_USER_IMAGE', 'img/system/default_user_img.jpg');
defined('PREMIUM_ICON')  OR  define('PREMIUM_ICON', 'img/system/icon_premium.png');
defined('DEFAULT_SIGNATURE_IMAGE')  OR  define('DEFAULT_SIGNATURE_IMAGE', 'img/default_signature.png');
defined('AMAZON_PRODUCT_IMAGE')  OR  define('AMAZON_PRODUCT_IMAGE', 'img/amazon_product_icon.png');
defined('BACKMARKET_PRODUCT_IMAGE')  OR  define('BACKMARKET_PRODUCT_IMAGE', 'img/backmarket_product_icon.png');

defined('DEFAULT_TRANSFEROR_SIGNATURE_IMAGE')  OR  define('DEFAULT_TRANSFEROR_SIGNATURE_IMAGE', 'img/default_tranferor_signature.png');
#defined('APP_PASSWORD_MIN_LENGTH')  OR  define('APP_PASSWORD_MIN_LENGTH', 5);
#defined('SYSTEM_EMAIL_SENDER')  OR  define('SYSTEM_EMAIL_SENDER','support@safepcsupport.co.uk');
#defined('SYSTEM_EMAIL_SENDER_CC')  OR  define('SYSTEM_EMAIL_SENDER_CC','info@mac4sale.co.uk');
#defined('ENABLE_JS_CSS_CACHE')  OR  define('ENABLE_JS_CSS_CACHE',0);
#defined('URL_RETURN_ALLOWED')  OR  define('URL_RETURN_ALLOWED', 1);
#defined('ALLOW_ANDROID_USER_RE_SESSION')  OR  define('ALLOW_ANDROID_USER_RE_SESSION', 1);
#defined('ENABLE_LOGIN_CAPTCHA')  OR  define('ENABLE_LOGIN_CAPTCHA', 1);

#defined('ENABLE_LOGIN_EMAIL_VERIFICATION_CHECK')  OR  define('ENABLE_LOGIN_EMAIL_VERIFICATION_CHECK', 0);
#defined('ENABLE_LOGIN_MOBILE_VERIFICATION_CHECK')  OR  define('ENABLE_LOGIN_MOBILE_VERIFICATION_CHECK', 0);
#defined('LIVE_CHAT_ENABLE_STATUS')  OR  define('LIVE_CHAT_ENABLE_STATUS', 0);

#defined('SUCCESSFULL')  OR  define('SUCCESSFULL', '<span class="text-success"><i class="fa fa-check-circle fa-lg"></i> Successfull</span>');

defined('DOC_UPLOAD_DIR')  OR  define('DOC_UPLOAD_DIR', "upload/doc/");
defined('DOC_TEMP_UPLOAD_DIR')  OR  define('DOC_TEMP_UPLOAD_DIR', "upload/temp/");

defined('INVOICE_BAR_CODE_IMAGE_PATH')  OR  define('INVOICE_BAR_CODE_IMAGE_PATH',"upload/temp/barcode/invoice-barcode-image.png");
defined('INVOICE_QR_CODE_IMAGE_PATH')  OR  define('INVOICE_QR_CODE_IMAGE_PATH',"upload/temp/qrcode/invoice-qrcode-image.png");
defined('INVOICE_PAID_IMAGE_PATH')  OR  define('INVOICE_PAID_IMAGE_PATH', 'img/paid_invoice.png');
defined('INVOICE_UNPAID_IMAGE_PATH')  OR  define('INVOICE_UNPAID_IMAGE_PATH', 'img/unpaid_invoice.png');
defined('INVOICE_CANCEL_IMAGE_PATH')  OR  define('INVOICE_CANCEL_IMAGE_PATH', 'img/cancel_invoice.png');

spl_autoload_register(function(){ global $conn; if($conn!=NULL)mysqli_close($conn);});
$autoloadCSS	= array("bootstrap-datepicker.min","bootstrap-datetimepicker.min","simple-line-icons","font-awesome.min","style","datatables.min","bootstrap.min","bootstrap","bootstrap-ms-extension","fullcalendar.min","bootstrap-clockpicker.min", "intro.min", "intro-themes/introjs-modern","custom", "font-awesome-animation.min","jquery.mentiony", "toastr.min", "select2.min");
$autoloadJS		= array("jquery.min","jquery-ui1.8.min","tether.min","bootstrap.min","pace.min","chart.min","app","bootstrap-datepicker.min","bootstrap-datetimepicker.min","main","datatables.bootstrap.min","jquery.datatables.min", "md5", "signature_pad", "webcam","moment.min","fullcalendar.min","bootstrap-clockpicker.min","drag-arrange.min", "pdf","pdf.worker","jquery.ez-plus", "paste", "popper.min", "jquery.mentiony","toastr.min", "intro.min","custom", "websocket", "select2.min","chase.customer");

#-------------------------------Fixed Constants
define('STATUS_PENDING', 2);

define('PAY_FORMAT_HOURLY','Hourly');
define('PAY_FORMAT_WEEK', 'Weekly');
define('PAY_FORMAT_15DAY','15 Days');
define('PAY_FORMAT_MONTH','Monthly');
define('PAY_FORMAT_QUATR','Quaterly');
define('PAY_FORMAT_HALFY','Half Yearly');
define('PAY_FORMAT_YEAR','Yearly');

define('CURRENCY_GBP','GBP');
define('CURRENCY_INR','INR');
define('CURRENCY_PKR','PKR');
define('CURRENCY_USD','USD');
define('CURRENCY_AED','AED');

define('PAY_STATUS_PAID','Paid');
define('PAY_STATUS_UNPAID','Unpaid');

define('PAY_MODE_CHECK','Check');
define('PAY_MODE_ONLINE','Online');
define('PAY_MODE_CASH','Cash');
define('PAY_MODE_OTHER','Other');


define('USER_GENDER_MALE','Male');
define('USER_GENDER_FEMALE','Female');
define('USER_GENDER_OTHER','Other');

define('WEBSITE_CUSTOMER', 3);
define('COLLECTION_STATUS_CANCEL', 3);

define('COMP_CHK_LIST_INDUCTION','Induction Checklist');
define('COMP_CHK_LIST_TERMINATION','Termination Checklist');
define('COMP_CHK_LIST_TELEWORKING','Teleworking risk assesment Checklist');
define('COMP_CHK_LIST_NON_CONFORMANCE','Non Conformance Report');

define('SERIALIZED', 'Serialized');
define('UNSERIALIZED', 'Unserialized');

define('SHIPMENT_STATUS_SHIPPED', 'Shipped');
define('SHIPMENT_STATUS_SHIPPED_PARTIALY', 'Partialy Shipped');

define('REFUND_STATUS_FULL', 'Full Refund');
define('REFUND_STATUS_PARTIAL', 'Partialy Refund');

define('DESRUCTION_METHOD_SECURE_ERASING', 'Secure Erasing');
define('LOG_DIR', CACHE_ROOT_DIR."log/");
#----------------------------------------------

#defined('APP_EMPLOYER_NAME')  OR  define('APP_EMPLOYER_NAME','eWorld U.K. LTD');

#defined('AJAX_REQUEST_MAX_TIME')  OR  define('AJAX_REQUEST_MAX_TIME',20000);
#defined('FORMCODE_STRING_LENGTH')  OR  define('FORMCODE_STRING_LENGTH',16);
#defined('DEFAULT_FEEDBACK_STORE')  OR  define('DEFAULT_FEEDBACK_STORE', 'safepcdisposal');
#defined('SALES_VAT_PERCENTAGE')  OR  define('SALES_VAT_PERCENTAGE', 20);
#defined('MAX_IDLE_SESSION_TIMEOUT')  OR  define('MAX_IDLE_SESSION_TIMEOUT', 20*60); // Seconds

#defined('ADMIN_USER')  OR  define('ADMIN_USER', 1);
#defined('ADMIN_ROLE')  OR  define('ADMIN_ROLE', 1);
#defined('ACCOUNT_MANAGER')  OR  define('ACCOUNT_MANAGER', 18);
#defined('HR_MANAGER')  OR  define('HR_MANAGER', 29);
#defined('APP_EMPLOYER_DOMAIN')  OR  define('APP_EMPLOYER_DOMAIN', 5);

#defined('COMPANY_NAME')  OR  define('COMPANY_NAME', 'eWorld UK LTD');
#defined('CURRENCY')  OR  define('CURRENCY', '£');
#defined('SIGNATURE_LINK_VALIDITY_TIME')  OR  define('SIGNATURE_LINK_VALIDITY_TIME', 1*24*60*60);
#defined('PER_DAY_REFUND_LIMIT')  OR  define('PER_DAY_REFUND_LIMIT', 3000);

#defined('MAX_SALARY_REGISTER_VIEW')  OR  define('MAX_SALARY_REGISTER_VIEW', 8);


#Third Party API Credentials Details :-
#--------------------------------------
#Google reCAPTCHA Credentials from fanu001@googlemail.com
#defined('GOOGLE_RECAPTCHA_PUBLIC_KEY')  OR  define('GOOGLE_RECAPTCHA_PUBLIC_KEY', '6Ld42FcUAAAAAFLXVVmptERY11f5rUu6m5ye_XkW');
#defined('GOOGLE_RECAPTCHA_PRIVATE_KEY')  OR  define('GOOGLE_RECAPTCHA_PRIVATE_KEY', '6Ld42FcUAAAAAJGCV_LfRvTQQ4J0k10doicW4S_p');
#Google API Credentials Details
#Google Map
#defined('GOOGLE_MAP_API_KEY')  OR  define('GOOGLE_MAP_API_KEY','AIzaSyAOirXs29PPTeN7jVhvw6Pyw8LBgrfs1ao');
defined('GOOGLE_MAP_API_SCRIPT')  OR  define('GOOGLE_MAP_API_SCRIPT',"<script src=\"https://maps.googleapis.com/maps/api/js?key=".GOOGLE_MAP_API_KEY."&libraries=places&callback=initAutocomplete\" async defer></script>");
#Google FireBase Messaging
#defined('GOOGLE_FIREBASE_API_KEY')  OR  define('GOOGLE_FIREBASE_API_KEY','AIzaSyDw9SuyqepAKgtnFhR2VTCEw4BoYPTgp_o');
#defined('GOOGLE_FIREBASE_API_AUTH_DOMAIN')  OR  define('GOOGLE_FIREBASE_API_AUTH_DOMAIN','safepcdisposal-support.firebaseapp.com');
#defined('GOOGLE_FIREBASE_API_DATABASE_URL')  OR  define('GOOGLE_FIREBASE_API_DATABASE_URL','https://safepcdisposal-support.firebaseio.com');
#defined('GOOGLE_FIREBASE_API_PROJECT_ID')  OR  define('GOOGLE_FIREBASE_API_PROJECT_ID','safepcdisposal-support');
#defined('GOOGLE_FIREBASE_API_STORAGE_BUCKET')  OR  define('GOOGLE_FIREBASE_API_STORAGE_BUCKET','safepcdisposal-support.appspot.com');
#defined('GOOGLE_FIREBASE_API_MESSAGING_SENDER_ID')  OR  define('GOOGLE_FIREBASE_API_MESSAGING_SENDER_ID','776466513137');
#defined('GOOGLE_FIREBASE_API_APP_ID')  OR  define('GOOGLE_FIREBASE_API_APP_ID','1:776466513137:android:16614d09bd645771');
#defined('GOOGLE_FIREBASE_API_MEASUREMENT_ID')  OR  define('GOOGLE_FIREBASE_API_MEASUREMENT_ID','');

#defined('GOOGLE_FIREBASE_API_URL')  OR  define('GOOGLE_FIREBASE_API_URL','https://fcm.googleapis.com/fcm/send');
#defined('GOOGLE_FIREBASE_API_AUTHERISATION_KEY')  OR  define('GOOGLE_FIREBASE_API_AUTHERISATION_KEY','AAAAtMkCvPE:APA91bHzhPuFwCkjqzOGkxVOiYcvdtNobqNRfHpwTDLivA6-YL3PoboW77a97eHGA2PaBKC6l5CJd8n18HIgMvbOR3uswoB5CJVfItAbtGyKTdO3RTmXh5ldPheyI7dtGcfpp0wLvkGf');

#defined('SPD_GOOGLE_REVIEW_LINK')  OR  define('SPD_GOOGLE_REVIEW_LINK','https://search.google.com/local/writereview?placeid=ChIJyZ7iM3u4dkgRlF-0suNchds');

#defined('UPS_DEVL_URL')  OR  define('UPS_DEVL_URL','https://wwwcie.ups.com/ups.app/xml/');
#defined('UPS_PROD_URL')  OR  define('UPS_PROD_URL','https://onlinetools.ups.com/ups.app/xml/');

#defined('DPD_PROD_URL')  OR  define('DPD_PROD_URL','https://api.dpd.co.uk/');
#defined('DPD_DEVL_URL')  OR  define('DPD_DEVL_URL','https://api.dpd.co.uk/');

#defined('FEDEX_PROD_URL')  OR  define('FEDEX_PROD_URL','https://ws.fedex.com:443/web-services');
#defined('FEDEX_DEVL_URL')  OR  define('FEDEX_DEVL_URL','https://wsbeta.fedex.com:443/web-services');

$app = new App();
?>