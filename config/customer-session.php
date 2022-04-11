<?php
if(!isset($_SESSION['customer_id'])){
	$_SESSION['msg']	=	"danger|Session Expired !!! Please Login to Continue...";
	header("location:customer-login.php");
	exit();
}
define('USER', $_SESSION['app_log_type'] == "E"? "ADMIN":"CUSTOMER");
?>