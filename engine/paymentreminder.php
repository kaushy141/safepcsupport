<?php
if(isset($parameter1))
{
	Modal::load(array('PaymentReminder'));
	$po_id = $parameter1;
	$pr = new PaymentReminder($po_id);
	$data = $pr->getDetails();
	if($data)
	{		
		extract($data);
		$action	=	"payment/savepaymentreminder";
		$formHeading	=	"Payment Reminder #$payment_reminder_code";
		include("engine/inc/addpaymentreminderform.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>