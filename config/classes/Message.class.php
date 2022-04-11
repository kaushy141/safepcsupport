<?php
class Message extends DB{
	public $id;
	public $key_id	=	"msg_id";
	public $table_name	=	"app_users_messages";
	public $status	=	"msg_readed";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
	function add($msg_sender, $msg_receiver, $msg_text, $msg_readed){
		$sql= "INSERT INTO `app_users_messages`(`msg_sender`, `msg_receiver`, `msg_text`, `msg_time`, `msg_readed`) VALUES ('$msg_sender', '$msg_receiver', '$msg_text', NOW(), '$msg_readed')";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();
	}
	
	function markMessageReaded(){
		$sql= "UPDATE `app_users_messages` SET `msg_readed` = '1' WHERE `msg_receiver` = '$_SESSION[user_id]'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
	}
	
	function getUsersMessage($user_id, $offsetId=0){
		global $app;
		$my_id = $_SESSION['user_id'];
		$sql = "SELECT 'chat' as msg_format, a.`msg_id`, a.`msg_sender` as sender_id, a.`msg_receiver` as receiver_id, a.`msg_text`, a.`msg_time`, a.`msg_readed` , s.`user_image` as sender_image, CONCAT(s.`user_fname`, ' ', s.`user_lname`) as sender_name, r.`user_image` as receiver_image, CONCAT(r.`user_fname`, ' ', r.`user_lname`) as receiver_name
		FROM `app_users_messages` as a 
		INNER JOIN `app_system_user` as s ON s.`user_id` = a.`msg_sender`
		INNER JOIN `app_system_user` as r ON r.`user_id` = a.`msg_receiver`
		WHERE (a.`msg_sender` = '$my_id' AND a.`msg_receiver` = '$user_id') OR (a.`msg_sender` = '$user_id' AND a.`msg_receiver` = '$my_id') ORDER BY a.`msg_time` DESC LIMIT 10 OFFSET $offsetId";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){								
				$row['sender_image'] = $app->imagePath($row['sender_image']);
				$row['receiver_image'] = $app->imagePath($row['receiver_image']);
				$row['msg_time'] = dateView($row['msg_time'],'SMALL');
				$row['msg_text'] = htmlentities($row['msg_text']);
				$record_array[] = $row;
		}
		return $record_array;	
	}
	
}
?>