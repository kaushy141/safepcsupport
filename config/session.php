<?php
if(!isset($_SESSION['user_id'])){
	$_SESSION['msg']	=	"danger|Session Expired !!! Please Login to Continue...";
	header("location:login.php");
	exit();
}
define('USER', $_SESSION['app_log_type'] == "E"? "ADMIN":"CUSTOMER");
?>