<?php
	$code = "";
	$pallet_id = 0;
	$data = sanitizePostData($_POST);
	extract($data);
	$pallet = new Pallet($pallet_id);
	if($pallet->isExist())
	{
		$PalletItems = new PalletItems();
		$PalletItems->removePalletItemByCode($pallet_id, $code);
		echo json_encode(array("200", "success|Pallet Items Removed"));
	}
	else{
		echo json_encode(array("300",  "danger|Collection Pallet not found."));
	}

?>