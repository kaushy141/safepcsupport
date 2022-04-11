<?php
error_reporting(1);
ini_set('display_errors', 1);
	$event_id = 0;	
	$data  = sanitizePostData($_POST);
	extract($data);
	if(isAdmin()){
		$jivoChat = new JivoChat();
		$value_key_id = 'jivo_chat_last_event_ssen_'.getLoginId();
		$event_id = Values::getKeyValues($event_id) ?: $event_id;
		$eventData = $jivoChat->getLatestEvents(intval($event_id), 3);	
		Values::saveKeyValues($value_key_id, $jivoChat->getLastId());
		echo json_encode(array("200",  "success|JivoChat event fetched", $eventData));		
	}
	else	
		echo json_encode(array("300",  "danger|Permission deniend"));

?>