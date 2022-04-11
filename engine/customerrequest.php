<?php
$action	=	"repair/addcomplaintcustomer";
$formHeading	=	"Add Request";
$btnText	=	"ADD";
$customer  = new Customer($_SESSION['customer_id']);
$customerdata = $customer->getDetails();
?>
<?php include("engine/inc/customerrequestform.php"); ?>