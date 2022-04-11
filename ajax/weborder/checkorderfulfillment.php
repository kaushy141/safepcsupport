<?php

	$web_order_id = 0;
	$data  = sanitizePostData($_POST);
	extract($data);
	if($web_order_id){
		
		$websiteOrder = new WebsiteOrder($web_order_id);
		if($websiteOrder->isExist()){
			$details = $websiteOrder->getDetails();
			$weborderProducts = new WebsiteOrderProduct();
			$productList = $weborderProducts->getList($details['web_order_id']);
			$fulfillmentList = array();
			if($productList)
			{
				foreach($productList as $_product){
					$fulfillmentList[] = array(
						'product' => $_product,
						'fulfill'=> WebsiteOrderProduct::checkProductAvailability($_product['wo_product_sku'])
					);
				}
			}
			echo json_encode(array("200", "success|Order FulFillment checked", $fulfillmentList));
		}
		else{
			echo json_encode(array("300",  "warning|Weborder not exist"));
		}
	}
	else{
		echo json_encode(array("300",  "warning|Weborder not found"));
	}

?>