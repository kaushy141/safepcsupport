<?php
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASSWORD","");
define("DB_NAME","safepcsupport");

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if(!$conn)
{
	die("Oooops... Database Connection Error. Please Check Application Database Configuration... ");		
}		
mysqli_set_charset($conn, 'utf8mb4');
?>