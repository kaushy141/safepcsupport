<?php
	if(WebsiteOrder::checkFulfillment())
		echo json_encode(array("200", "success|Order FulFillment checked"));
	else
		echo json_encode(array("300",  "warning|Order FulFillment check failed"));

?>