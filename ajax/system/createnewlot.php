<?php
	if(!isset($_SESSION['LOT'])){
		$_SESSION['LOT'] = array();
		$_SESSION['LOT']['PRODUCT'] = array();
		echo json_encode(array("200", "success|Lot is available to add products"));			
	}
	else
		echo json_encode(array("300",  "danger|A lot already on Progress.", count($_SESSION['LOT']['PRODUCT'])));

?>