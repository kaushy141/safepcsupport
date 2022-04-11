<?php
if(isset($parameter1))
{
	$complaint_id = $parameter1;
	$Complaint = new Complaint($complaint_id);
	$data = $Complaint->load();
	if($data)
	{
		extract($data);
		$action	=	"repair/updatecomplaint";
		$formHeading	=	"Update Request #$complaint_ticket_number ";
		$btnText	=	"UPDATE";
		if(isAdmin())
			include("engine/inc/addcomplaintrequestform.php");
		else
			include("engine/inc/viewcomplaintrequestcustomer.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>