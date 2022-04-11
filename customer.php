<?php include("setup.php"); ?>
<?php include("config/customer-session.php");?>
<?php $app = new App();?>
<?php
$request_path			=	$_SERVER['REQUEST_URI'];
$request_path_array 	=	explode("/",trim($request_path,"/"));
$loadedFunction = "";
if(isset($request_path_array[0]) && $request_path_array[0]!='')
{
	if(count($request_path_array)>1)
	{
		header("location:".$app->basePath().$request_path_array[0]);
		exit();
	}
	if(file_exists("engine/".$request_path_array[0].".php"))
	{
    	$loadedFunction = "loadPage('".$request_path_array[0]."');";		
	}
	else
		$loadedFunction = "loadPage('404');";
}
else
	$loadedFunction = "loadPage('dashboard');";
?>
<!DOCTYPE html>
<html lang="en">
<?php include('inc/header.php'); ?>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
<?php include('inc/header-top.php'); ?>
    <div class="app-body">
        <?php include('inc/sidebar.php'); ?>
        <?php include('inc/main.php'); ?>
        <?php include('inc/aside.php'); ?>
    </div>
<?php include('inc/footer.php'); ?>
</body></html>
