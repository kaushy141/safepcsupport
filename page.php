<?php 
ob_start();
include("setup.php");
$app = new App();
$file="engine/404.php";
if(getLoginId() && isset($_POST['path']) && !empty($_POST['path']))
{
	$navbar = new Navbar();	
	$path_array = explode("/",$_POST['path']);
	if(isset($path_array[0]))
	$parameter0=$path_array[0];
	
	if(isset($path_array[1]))
	$parameter1=$path_array[1];
	
	if(isset($path_array[2]))
	$parameter2=$path_array[2];
	
	if(isset($path_array[3]))
	$parameter3=$path_array[3];
	
	if($navbar->isUserCanAccess($path_array[0]))
	{	
		$TABEL_ID = 'datatable_'.md5(trim(strtolower($path_array[0])));
		$path	=	 "engine/".strtolower(trim(preg_replace("/[^a-zA-Z0-9]/", "", $path_array[0]))).".php";
		if(file_exists($path))
		$file=$path;	
	}
	else
	{
		$file="engine/403.php";
	}
}
elseif(!getLoginId())
{
	$file="engine/101.php";
}
$context = file_get_contents($file); 
eval("?>$context");
$output = ob_get_contents();
ob_get_clean();
echo json_encode(array("context"=>$output, "title"=>isset($formHeading)?$formHeading:"", "version"=> VERSION));
?>