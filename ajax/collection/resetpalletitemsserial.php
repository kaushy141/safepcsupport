<?php
	$pallet_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);	
	$PalletItems = new PalletItems($pallet_id);
	$PalletItems->resetSerial();
	echo json_encode(array("200",  "success|Pallet's Item order reset succesfully", json_encode($PalletItems->getItemsRecords())));

?>