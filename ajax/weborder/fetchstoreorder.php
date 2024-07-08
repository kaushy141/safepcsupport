<?php

	$order_id = $store_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	$store = new Store($store_id);
	if($store->isExist()){		
		$storeData = $store->getDetails();
		$ch = curl_init();
<<<<<<< HEAD
		curl_setopt( $ch,CURLOPT_URL, $storeData['store_link'].'orderpush.php?order_id='.$order_id );
=======
		if($storeData['store_order_fetch_endpoint']){
		    curl_setopt( $ch,CURLOPT_URL, $storeData['store_order_fetch_endpoint'].$order_id );
		}else{
		    curl_setopt( $ch,CURLOPT_URL, $storeData['store_link'].'orderpush.php?order_id='.$order_id );
		}
>>>>>>> 77a717f (Version 2)
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		$response = curl_exec($ch );	
		curl_close( $ch );
		if($response == "order pushed"){
			echo json_encode(array("300",  "success|Order imported. Order may take 1 minute to visible"));
		}
		else{
			echo json_encode(array("300",  "warning|Order import failed"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Store not found"));
	}
?>