<?php require_once 'setup.php'; ?>
<?php	
	$firebase = new Firebase();
	$push = new Push();

	// optional payload
	$payload = array();
	$payload['team'] = 'India';
	$payload['score'] = '5.6';

	// notification title
	$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
	 
	// notification message
	$message = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';
	 
	// push type - single user / topic
	$push_type = isset($_REQUEST['push_type']) ? $_REQUEST['push_type'] : '';
	 
	// whether to include to image or not
	$include_image = isset($_REQUEST['include_image']) ? TRUE : FALSE;


	$push->setTitle($title);
	$push->setMessage($message);
	if ($include_image) {
		$push->setImage('https://api.androidhive.info/images/minion.jpg');
	} else {
		$push->setImage('');
	}
	$push->setIsBackground(FALSE);
	$push->setPayload($payload);


	$json = '';
	$response = '';

	if ($push_type == 'topic') {
		$json = $push->getPush();
		$response = $firebase->sendToTopic('global', $json);
	} else if ($push_type == 'individual') {
		$json = $push->getPush();
		$regId = isset($_REQUEST['regId']) ? $_REQUEST['regId'] : '';
		$response = $firebase->send($regId, $json);
	}
?>