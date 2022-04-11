<?php
	
	Modal::load(array('PaymentReminder'));
   
    $payment_reminder_id = $payment_reminder_related_store = $payment_reminder_amount = $payment_reminder_creator_user = $payment_reminder_status = $payment_reminder_type = $payment_reminder_cycle_clock = 0;
	$payment_reminder_code = $payment_reminder_title = $payment_reminder_recipients = $payment_reminder_currency = '';
	$payment_reminder_tag_user = array();
	
    $data = sanitizePostData($_POST);
	extract($data);
	
	if(count($payment_reminder_tag_user))
	$payment_reminder_tag_user = array_column($payment_reminder_tag_user, 'id');
    $paymentReminder  = new PaymentReminder($payment_reminder_id);
    
	$payment_reminder_due_date = date("Y-m-d H:i:s", strtotime($payment_reminder_due_date));
	
	$inputData = array(
		"payment_reminder_title" 		=> $payment_reminder_title,
		"payment_reminder_type"			=> $payment_reminder_type,
		"payment_reminder_recipients" 	=> $payment_reminder_recipients,
		"payment_reminder_related_store"=> $payment_reminder_related_store,
		"payment_reminder_amount"		=> $payment_reminder_amount,
		"payment_reminder_currency" 	=> $payment_reminder_currency,
		"payment_reminder_due_date"		=> $payment_reminder_due_date,
		"payment_reminder_trig_time"	=> PaymentReminder::getNextReminderDate($payment_reminder_due_date),
		"payment_reminder_cycle_clock"	=> $payment_reminder_cycle_clock,
		"payment_reminder_cycle_next_clock" => PaymentReminder::getNextReminderIntervalClock($payment_reminder_due_date, $payment_reminder_cycle_clock),
		"payment_reminder_updated_date" => "NOW()", 
		"payment_reminder_status" 		=> $payment_reminder_status				
	);
	//echo "<pre>";
	//print_r($inputData);die;
	
	if($payment_reminder_id == 0) // && $paymentReminder->isExist()
	{  		
		$payment_reminder_code = PaymentReminder::getPaymentReminderCode();
		$inputData['payment_reminder_code'] = $payment_reminder_code;
		$inputData['payment_reminder_creator_user'] = getLoginId();
		$inputData['payment_reminder_created_date'] = "NOW()";
		$inputData['payment_reminder_code'] = $payment_reminder_code;
		$payment_reminder_id = $paymentReminder->insert($inputData);
		if($payment_reminder_id)
		{
			UserTag::saveModuleTag('Y', $payment_reminder_id, $payment_reminder_tag_user, 0, $payment_reminder_code);
			//Activity::add("added|^|$payment_reminder_code", "Y", $payment_reminder_id);
			echo json_encode(array("200",  "success|Payment reminder addedd", 'paymentreminder/'.md5($payment_reminder_id)));
		}
		else
			echo json_encode(array("300",  "warning|Payment reminder could't addedd. Try again"));		
	}
	elseif($paymentReminder->isExist())
	{
		$recordData = $paymentReminder->getDetails();
		$paymentReminder->update($inputData);
		UserTag::saveModuleTag('Y', $payment_reminder_id, $payment_reminder_tag_user, 0, $recordData['payment_reminder_code']);
		//Activity::add("updated|^|$payment_reminder_code", "Y", $payment_reminder_id);
		echo json_encode(array("200",  "success|updated", 'paymentreminder/'.md5($payment_reminder_id), $payment_reminder_tag_user));
	}
	else
	{
		echo json_encode(array("300",  "warning|No Payment reminder found"));	
	}
	

?>