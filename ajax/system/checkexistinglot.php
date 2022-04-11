<?php
	if(isset($_SESSION['LOT'])){		
		echo json_encode(array("200", "success|Existing lot found", isset($_SESSION['LOT']['PRODUCT']) ? count($_SESSION['LOT']['PRODUCT']) : 0));			
	}
	else
		echo json_encode(array("300",  "danger|No lot available.", ''));

?>