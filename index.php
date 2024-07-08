<?php include("setup.php"); ?>
<<<<<<< HEAD
<?php 
if(isset($_SESSION['app_log_type']))
{
	include($_SESSION['app_log_type'] == "E"?"config/session.php":"config/customer-session.php");
}
else
{
=======
<?php
if(isset($_SESSION['app_log_type']))
{ //echo "Found";
	include($_SESSION['app_log_type'] == "E"?"config/session.php":"config/customer-session.php");
}
else
{ //echo "Not found";
>>>>>>> 77a717f (Version 2)
	header("location:".$app->basePath('main.php?return='.$_SERVER['REQUEST_URI']));
	exit();
}
?>
<?php
$request_path			=	$_SERVER['REQUEST_URI'];
$loadedFunction = "";
$parameter0 = isset($_REQUEST['parameter0'])?trim($_REQUEST['parameter0']):"";
$parameter1 = isset($_REQUEST['parameter1'])?trim($_REQUEST['parameter1']):"";
$parameter2 = isset($_REQUEST['parameter2'])?trim($_REQUEST['parameter2']):"";
$parameter3 = isset($_REQUEST['parameter3'])?trim($_REQUEST['parameter3']):"";
if($parameter0!='')
{	
	if(file_exists("engine/".$parameter0.".php"))
	{
    	$loadedFunction = "loadPage('".trim($request_path,"/")."');";		
	}
	else
		$loadedFunction = "loadPage('404');";
}
else
	$loadedFunction = "loadPage('dashboard');";


/*$request_path			=	$_SERVER['REQUEST_URI'];
$request_path_array 	=	explode("/",trim($request_path,"/"));
$loadedFunction = "";

if(isset($request_path_array[0]) && $request_path_array[0]!='')
{
	if(isset($request_path_array[1]))
	$parameter1=$request_path_array[1];
	
	if(isset($request_path_array[2]))
	$parameter2=$request_path_array[2];
	
	if(isset($request_path_array[3]))
	$parameter3=$request_path_array[3];
	
	if(file_exists("engine/".$request_path_array[0].".php"))
	{
    	$loadedFunction = "loadPage('".trim($request_path,"/")."');";		
	}
	else
		$loadedFunction = "loadPage('404');";
}
else
	$loadedFunction = "loadPage('dashboard');";*/
?>
<!DOCTYPE html>
<html lang="en">
<?php include('inc/header.php'); ?>
<?php include('inc/modal.php'); ?>
<?php include('inc/aside.php'); ?>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<?php include($_SESSION['app_log_type'] == "E"?"inc/header-top.php":"inc/header-top-customer.php"); ?>
    <div class="app-body">
    	<?php include($_SESSION['app_log_type'] == "E"?"inc/sidebar.php":"inc/sidebar-customer.php"); ?>
        <?php include('inc/main.php'); ?>
    </div>
<?php include('inc/footer.php'); ?>
</body></html>
<?php //echo $loadedFunction;die;?>