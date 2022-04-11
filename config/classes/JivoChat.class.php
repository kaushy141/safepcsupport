<?php
class JivoChat extends DB{
	public $id;
	public $key_id	=	"event_id";
	public $table_name	=	"app_jivochat_events";
	public $status	=	"event_status";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function getLatestEvents($event_id = 0, $limit = 10)
	{
		global $app;
		$condition = $event_id != 0 ? " AND `event_id` > '$event_id'" : "";
		$sql="SELECT * FROM `".$this->table_name."` WHERE `event_status` = '1' AND `event_created_date` >= (NOW() - INTERVAL 3 MINUTE) $condition ORDER BY `event_id` LIMIT $limit";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		
		$actionArray = array(
			'' => 'New Action',
			'chat_accepted' => 'Chat accepted',
			'chat_assigned' => 'Chat assigned',
			'chat_finished' => 'Chat finished',
			'offline_message' => 'Offline message'
		);
	
		$resultArray = array();			
		while($row = $dbc->db_fetch_assoc(true)){
			$event = array();
			$event['event_id'] = "{$row['event_id']}";
			$sourceUrl = parse_url($row['event_page_url']);
			$sourceDomain = $sourceUrl['host'];
			if($row['event_name'] == 'chat_accepted'){
				$title = "{$row['event_visitor_agent_name']} accepted chat on {$sourceDomain}. Visitor {$row['event_visitor_name']} from {$row['event_geoip_region']}, {$row['event_geoip_country']}";
				
				$event['speak'] = "{$row['event_visitor_agent_name']} accepted chat on {$sourceDomain}";
			}
			elseif($row['event_name'] == 'chat_finished'){
				$title = "{$row['event_visitor_agent_name']} Finished chat on {$sourceDomain}. Visitor {$row['event_visitor_name']} from {$row['event_geoip_region']}, {$row['event_geoip_country']}";
					
				$event['speak'] = "{$row['event_visitor_agent_name']} Finished chat on {$sourceDomain}";
			}
			elseif($row['event_name'] == 'offline_message'){
				$title = "Offline message on {$sourceDomain}. By new visitor {$row['event_visitor_name']} from {$row['event_geoip_region']}, {$row['event_geoip_country']}";
				
				$event['speak'] = "A Offline message received on {$sourceDomain}";
			}
			$event['image'] = 'https://live.safepcsupport.co.uk/var/media/50x50/img/system/default_user_img.jpg';
			$event['title'] = $title;
			$event['type'] = 'JIVO-CHAT';
			$event['link'] = "{$row['event_page_url']}";
			$event['time'] = dateView($row['event_created_date'], 'NOW');
			$event['user'] = "{$row['event_visitor_name']} - {$row['event_visitor_phone']}";
			$resultArray[] = $event;
		}
		return $resultArray;
	}	
}
?>