<?php
admin();
Modal::load(array('PaymentReminder'));
$payment_reminder_id = 0;
$action	=	"payment/savepaymentreminder";
$formHeading	=	"Add Payment Reminder";
?>
<?php include("engine/inc/addpaymentreminderform.php"); ?>