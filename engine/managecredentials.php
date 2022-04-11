<?php 
$credentials = new Credentials(0);
$data['credentialslist'] = $credentials->getMyCredentialsList();
extract($data);
$action	=	"system/managecredentials";
$formHeading	=	"Manage Credentials";
$btnText	=	"Save Credentials";
include("engine/inc/managecredentialsform.php");

?>