<?php include("setup.php"); 
$app = new App();
if(!isset($_SESSION['APP_ACCESS']))
{
	//die("Error... !!! Permission denied.");
}
set_time_limit(0);
error_reporting(0);
$incRequest = 'default';
if(isset($_GET['format']) && !is_array($_GET['format']) && trim($_GET['format']) != ""){
	if(file_exists('report/'.trim($_GET['format']).'.php')){
		$incRequest = trim($_GET['format']);
	}
}
$incFile = 'report/'.$incRequest.'.php';
//echo $incFile;
if(!(file_exists($incFile) && is_readable($incFile) && include_once($incFile))) {
    echo "File not found";die;
}
?>