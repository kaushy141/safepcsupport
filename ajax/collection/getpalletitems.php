<?php
	$pallet_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	if($pallet_id){
		$pallet = new Pallet($pallet_id);
		$palletItems = new PalletItems($pallet_id);
		$palletData = $pallet->getDetails();
		echo json_encode(array("200", "success|Pallet Items Loaded", $palletItems->getItemsRecords(), $palletData['pallet_capacity']));
	}
	else{
		echo json_encode(array("300",  "danger|Pallet not found."));
	}

?>