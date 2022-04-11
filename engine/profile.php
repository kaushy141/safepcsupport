<?php
if(isAdmin())
{
	$Employee = new Employee($_SESSION['user_id']);
	$data = $Employee->getDetails();
	if($data)
	$formHeading	=	"Profile #$data[user_fname] $data[user_lname]";
}
else
{
	$customer = new Customer($_SESSION['customer_id']);
	$data = $customer->getDetails();
	if($data)
	$formHeading	=	"Profile #$data[customer_fname] $data[customer_lname]";
}
	if($data)
	{
		extract($data);
		$btnText= "Update";
	}
?>
<?php 
		if(isAdmin())
			include("engine/inc/updateprofileform.php");
		else
			include("engine/inc/updateprofileformcustomer.php");
?>