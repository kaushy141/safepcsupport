<?php

	$status = 0;
	$item_id = "";
	Modal::load(array('Po'));
    
    $data           = sanitizePostData($_POST);
	extract($data);
	$item_id_array = explode("|", $item_id);
	if(count($item_id_array) == 3){
		$poi_id			=	$item_id_array[0];
		$poi_po_id		=	$item_id_array[1];
		$poi_item_id	=	$item_id_array[2];
		$poi_is_closed	=	$status;
		PoItems::updateCloseStatus($poi_id, $poi_po_id, $poi_item_id, $poi_is_closed);
		echo json_encode(array("200",  "success|Item is ".($poi_is_closed ? "closed":"open")." now"));
	}

?>