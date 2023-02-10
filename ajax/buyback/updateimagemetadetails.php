<?php
Modal::load(array('BuybackOrder'));
	$image_id = 0;
	$image_title = "";
	$data  = sanitizePostData($_POST);
	extract($data);
	if($image_id){
		$bopm = new BuybackOrderProductMedia($image_id);
		$bopm->update(array("bpoi_title"=>$image_title));
		echo json_encode(array("200",  "success|Buyback product image details updated"));
	}
	else{
		echo json_encode(array("300",  "warning|Image not found"));
	}