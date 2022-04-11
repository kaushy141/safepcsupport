<?php
	function sendGCM($message, $id) {


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            			
			'registration_ids' 	 => array 
										(
                    						$id
            							),
            'data'				 => array
										(
											'message' 	=> $message,
											'title'		=> $message,
											'subtitle'	=> 'This is a subtitle. subtitle',
											'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
											'sound'		=> 1,
											'largeIcon'	=> 'large_icon',
											'smallIcon'	=> 'small_icon'
										)
    	);
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AAAAKkjN9fw:APA91bH3KX38tNeAL2Oe86cL_fJ1sRzxFkcdjOBhdq-_xe2flGZhJvjfkfAcVwpdmGu6JW_MnOt0jq5ytyFI0RzJt6780_eJbZwRCdKOxzOgsovOePWj09l77zVOd2_SeV5Di1-ssKYA",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    var_dump($result);
    curl_close ( $ch );
}
sendGCM($_REQUEST['msg'],"eqy0xMdaEXw:APA91bEXoRDn8jhDQz7RTpjTTPKjbA5sd8vNF4VuzsFD-gQ4Ffewtxv-IJCs6OO8NuWzKQ8NaalCtNtRwJVObEtkYZm-STX44kXYOdsaoK-xTZFs6vgfTZDUHKdO1x3VYq4m5X7lRSAp");
?>