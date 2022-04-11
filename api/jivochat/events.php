<?php
include("../../setup.php");
$json = file_get_contents('php://input');
if(!empty($json)){
	$appData = json_decode($json, true);
	$appData = sanitizePostData($appData);
	if(isset($appData['event_name'])){
		$allowedEvenst = array('chat_accepted', 'chat_assigned', 'chat_finished', 'offline_message');
		if(in_array($appData['event_name'], $allowedEvenst)){
			$event_name = $appData['event_name']; 
			$event_chat_id = $appData['chat_id']; 
			$event_widget_id = $appData['widget_id']; 
			$event_visitor_name = $appData['visitor']['name']; 
			$event_visitor_email = $appData['visitor']['email'];
			$event_visitor_phone = $appData['visitor']['phone'];
			$event_visitor_description = $appData['visitor']['description'];
			$event_visitor_agent_name = isset($appData['agent']['name']) ? $appData['agent']['name'] : 'NULL';
			$event_visitor_agent_email =  isset($appData['agent']['email']) ? $appData['agent']['email'] : 'NULL';
			$event_geoip_organization = $appData['session']['geoip']['organization']; 
			$event_geoip_country = $appData['session']['geoip']['country']; 
			$event_geoip_region = $appData['session']['geoip']['region']; 
			$event_geoip_location = $appData['session']['geoip']['latitude'].','.$appData['session']['geoip']['longitude']; 
			$event_page_url = $appData['page']['url']; 
			$event_page_title = $appData['page']['title']; 
			$event_user_agent = $appData['session']['user_agent']; 
			$event_created_date = 'NOW()'; 
			$event_status = 1; 
			$eventArray = compact('event_name', 'event_chat_id', 'event_widget_id', 'event_visitor_name', 'event_visitor_email', 'event_visitor_phone', 'event_visitor_description', 'event_visitor_agent_name', 'event_visitor_agent_email', 'event_geoip_organization', 'event_geoip_country', 'event_geoip_region', 'event_geoip_location', 'event_page_url', 'event_page_title', 'event_user_agent', 'event_created_date', 'event_status');
			$jivoChat = new JivoChat();
			$event_id = $jivoChat->insert($eventArray);
		}
	}
}

?>