<?php include("setup.php"); ?>
<?php 
if(isset($_SESSION['app_log_type']))
{
	include($_SESSION['app_log_type'] == "E"?"config/session.php":"config/customer-session.php");
}
?>
<?php
function sendGCM($fcm_reg_id, $userId, $name, $image, $type, $message, $complaint_id, $complaint_log_id, $complaint_format) 
{
    $fields = array (            			
			'registration_ids' 	 => $fcm_reg_id,
            'data'				 => array
										(
											'msg_format' 	=> 'module',
											'logger_id' 	=> $userId,
											'logger_name' 	=> $name,
											'logger_image' 	=> $image,
											'logger_type' 	=> $type,
											'complaint_log_text' 	=> htmlspecialchars($message),
											'complaint_id'	=>$complaint_id,
											'complaint_log_id'=>$complaint_log_id,
											'complaint_format'=>$complaint_format,
											'log_time'  	=> dateView(date('Y-m-d H:i:s'),'NOW'),
											'title'			=> "Message From $name",
											'sound'			=> 1,
											'largeIcon'		=> "$image",
											'smallIcon'		=> "$image"
										)
    );
	return send($fields);
}

function sendChat($token, $payload){
	$fields = array (            			
			'registration_ids' 	 => $token,
            'data'				 => $payload
    );
	return send($fields);
}

function send($fields)
{
    $fields = json_encode ( $fields );
    $url = GOOGLE_FIREBASE_API_URL;
    $headers = array (
            'Authorization: key=' .GOOGLE_FIREBASE_API_AUTHERISATION_KEY,
            'Content-Type: application/json');

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
	//Log::Text("FCM Result ".json_encode($result), 'FCM');
    curl_close ( $ch );
	return (isset($result->success) && $result->success) ? (($result == null || $result == "") ? $fields : $result) : $fields;
}


	$chatsection=0;
	$msg_receiver =0;
	$msg_text="";
	$logtext=""; $id = 0;
	$complaint_format = "";
	$customer_id = 0;
	$mentiony = array();
	$replier_parent = 0;
	$notifyall = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($chatsection==0 && $logtext!="" && intval($id)!=0 && $complaint_format!="")
	{
		$logLabel = "Unknown";
		$complaint_id = $id;
		if($complaint_format == "C"){
			$module = new Complaint($complaint_id);
			$customer_id 	=	$module->get("complaint_customer_id");
			$logLabel 		= 	$module->get("complaint_ticket_number");
		}
		if($complaint_format == "W"){
			$module = new Collection($complaint_id);
			$customer_id 	=	$module->get("wc_customer_id");
			$logLabel 		= 	$module->get("wc_code");
		}
		if($complaint_format == "S"){
			$module = new SalesInvoice($complaint_id);
			$customer_id 	=	$module->get("sales_invoice_customer_id");
			$logLabel 		= 	$module->get("sales_invoice_number");
		}
		if($complaint_format == "O"){
			$module = new WebsiteOrder($complaint_id);
			$customer_id 	=	$module->get("web_order_customer_id");
			$logLabel 		= 	$module->get("web_order_number");
		}
		if($complaint_format == "B"){
			$module = new BatchProduct($complaint_id);
			$customer_id 	=	0;
			$logLabel 		= 	$module->get("product_code");
		}
		if($complaint_format == "P"){
			$module = new CollectionProcess($complaint_id);
			$customer_id 	=	3; // Default set for Kaushal
			$logLabel 		= 	$module->get("wc_process_asset_code");
		}
		if($complaint_format == "R"){
			Modal::load(array('Refund'));
			$module = new Refund($complaint_id);
			$customer_id 	=	3; // Default set for Kaushal
			$logLabel 		= 	$module->get("refund_code");
		}
		
		$complaint_log_text		=	$logtext;
		$complaint_log_type		=	"TEXT";
		$complaint_log_status	=	1;
		
		$complaint_user_id 		= USER == "ADMIN" ? getLoginId():0;
		$complaint_customer_id 	= USER == "ADMIN" ? 0 : getLoginId();
		
		$logData = array(
			"complaint_id" => $complaint_id,
			"complaint_format" => $complaint_format,
			"complaint_customer_id" => $complaint_customer_id,
			"complaint_user_id" => $complaint_user_id,
			"complaint_log_text" => $complaint_log_text,
			"complaint_log_type" => $complaint_log_type,
			"complaint_log_time" => 'NOW()',
			"complaint_log_status" => $complaint_log_status,
			"is_readed" => 0,
			"complaint_log_privacy" => 0,
			"complaint_log_reply_parent"	=> $replier_parent,
			"complaint_notified_all" => $notifyall ? 1 : 0
		);
				
		$log = new ComplaintLog();
		$complaint_log_id = $log->insert($logData);
		if($complaint_log_id)
		{
			$attachementData = "";			
			$supportedChatFileExtension = array('png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'rtf', 'xslx', 'csv', 'txt');
			#-----------------------------------Media--------------------------------
			if(isset($_FILES["logmedia"]) && !empty($_FILES["logmedia"])){
				$image_name = pathinfo($_FILES['logmedia']["name"]);
				$extension = strtolower($image_name["extension"]);
				if(in_array($extension, $supportedChatFileExtension))
				{
					$filename=time()."_".rand(100000,999999).".".$extension;
					$complaint_log_media_path = "upload/comments/$complaint_format/".getDirectorySeparatorPath()."media_".$complaint_log_id."_".time().".$extension";
					if (move_file($_FILES["logmedia"]["tmp_name"], $app->sitePath($complaint_log_media_path))){
						$log->update(array('complaint_log_media' => $complaint_log_media_path));
						$attachementData = " with attachment";	
					}
				}
			}		
			#------------------------------------------------------------------------
		
		
			$taggingData = "";
			$usedTagUser = array();
			if(!empty($mentiony)){
				$taggingData .= " and tagged to ";
				foreach($mentiony as $_user){
					//var_dump($_user);
					$_user = is_array($_user) ?  $_user  :  json_decode(stripslashes($_user), true);
					//var_dump($_user);
					if(!in_array($_user['id'], $usedTagUser))
					{
						$userTag = new UserTag();
						$userTag->insert(array(
								"tag_mentioner_id" 	=> getLoginId(), 
								"tag_user_id" 		=> $_user['id'], 
								"tag_module_code"	=> $complaint_format, 
								"tag_module_id"		=> $complaint_id, 
								"tag_time"			=> 'NOW()', 
								"tag_is_readed"		=> 0, 
								"tag_type"			=> 'tagged', 
								"tag_text"			=> $logLabel,
								"tag_log_id"		=> $complaint_log_id,
								"tag_is_tagged"		=> 1
							)
						);
						$taggingData .= "<b class=\"badge badge-info\">{$_user['name']}</b> ";
						array_push($usedTagUser, $_user['id']);
					}
				}
			}
			
			if($notifyall == 1)
			{
				$userFromTag = UserTag::getModuleParticipatedUser($complaint_format, $complaint_id);
				$userFromLog = ComplaintLog::getModuleParticipatedUser($complaint_format, $complaint_id);
				$paarticipatedUserArray = array_unique(array_merge($userFromTag, $userFromLog));
				
				if(count($paarticipatedUserArray)){
					foreach($paarticipatedUserArray as $tag_user_id){
						if(!in_array($tag_user_id, $usedTagUser) && $tag_user_id != getLoginId()){
						$userTag = new UserTag();
							$userTag->insert(array(
									"tag_mentioner_id" 	=> getLoginId(), 
									"tag_user_id" 		=> $tag_user_id, 
									"tag_module_code"	=> $complaint_format, 
									"tag_module_id"		=> $complaint_id, 
									"tag_time"			=> 'NOW()', 
									"tag_is_readed"		=> 0, 
									"tag_type"			=> 'commented, where you were previously tagged. and notified', 
									"tag_text"			=> $logLabel,
									"tag_log_id"		=> $complaint_log_id,
									"tag_is_tagged"		=> 0
								)
							);
						}
					}
				}
			}
			
		
			$ComplaintLogViewer = new ComplaintLogViewer();
			$ComplaintLogViewer->add($complaint_id, $complaint_format);
			
			/*if(isset($_SESSION['currentToken']) && $_SESSION['currentToken']!="")
			{
			}
			else
				echo json_encode(array("500","warning|Message Could Not send. Google FCM Tocken Not Found <br/><center><a class='btn btn-danger' onclick='notifyMe()'>Request Permission</a> &nbsp; then &nbsp; <a class='btn btn-danger' href=\"".$app->basePath()."\">Refresh</a></center>"));*/
			
			if(isset($_SESSION['currentToken']) && $_SESSION['currentToken']!="")
			{
				if(getUserType()=="E")
				{
					$customer  = new Customer($customer_id);
					$fcmID =  $customer->getFCMToken();
					$fcmToken = $fcmID ? array($fcmID,$_SESSION['currentToken']) : array($_SESSION['currentToken']);
					$fcmMessage = sendGCM($fcmToken, $_SESSION['user_id'], $_SESSION['user_name'], $app->imagePath($_SESSION['user_image']), getUserType(), $complaint_log_text, $complaint_id, $complaint_log_id, $complaint_format);									
				}
				else
				{
					$fcmger  = new FcmManager();
					$fcmger->customer_id=$customer_id;
					$fcmID =  $fcmger->getFCMToken();
					$fcmToken = $fcmID ? array($fcmID,$_SESSION['currentToken']) : array($_SESSION['currentToken']);
					$fcmMessage = sendGCM($fcmToken, $_SESSION['customer_id'], $_SESSION['customer_name'], $app->imagePath($_SESSION['customer_image']), getUserType(), $complaint_log_text, $complaint_id, $complaint_log_id, $complaint_format);				
				}
			}
			Activity::add("Replied $taggingData $attachementData on|^|$logLabel",$complaint_format,$complaint_id);
			
			echo json_encode(array("200","success|Log Added successfully", $fcmMessage, $log->getLog($complaint_id, $complaint_format, $complaint_log_id)));
		}
		else
			echo json_encode(array("300","warning|Unable to Insert Log. try again"));
	}
	elseif($chatsection=1 && $msg_receiver !=0 && $msg_text!="")
	{
		$msg_sender = $_SESSION['user_id'];
		$employee = new Employee($msg_receiver);
		if($empData = $employee->getDetails())
		{
			$msg_readed = $empData['user_is_live'] == 1 ? 1 : 0;
			$message = new Message();
			if($msg_id=$message->add($msg_sender, $msg_receiver, $msg_text, $msg_readed))
			{
				if(isset($_SESSION['currentToken']) && $_SESSION['currentToken']!="")
				{
					$fcmToken = array($_SESSION['currentToken'], $empData['user_fcm_token']);				

					$payload = array(
										'msg_format' 	=> 'chat',
										'msg_id' 		=> $msg_id,
										'sender_id' 	=> $_SESSION['user_id'],
										'sender_name' 	=> $_SESSION['user_name'], 
										'sender_image' 	=> $app->imagePath($_SESSION['user_image']), 
										'receiver_id'	=> $empData['user_id'], 
										'receiver_name' => $empData['user_name'], 
										'receiver_image'=> $app->imagePath($empData['user_image']),
										'msg_text' 		=> htmlspecialchars($msg_text),
										'msg_time'  	=> dateView(date('Y-m-d H:i:s'),'SMALL'),
										'title'			=> "Message From $_SESSION[user_name]",
										'sound'			=> 1,
										'largeIcon'		=> $app->imagePath($_SESSION['user_image']),
										'smallIcon'		=> $app->imagePath($_SESSION['user_image'])
									);		

					$fcmMessage = sendChat( $fcmToken, $payload );
				}
				echo json_encode(array("200","success|Message send.", array("data"=>$payload), $log->getLog($complaint_id, $complaint_format, $complaint_log_id)));	
			}
			else
				echo json_encode(array("300","warning|Message Couldn't saved."));
		}
		else
			echo json_encode(array("300","warning|Unknown User Found."));
	}
	else
		echo json_encode(array("300","warning|Please write Log Text"));

?>