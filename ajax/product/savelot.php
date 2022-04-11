<?php
	$lot_id = 0;
	$products = array();
	$data  = sanitizePostData($_POST);
	extract($data);	
	if(!count($products))
	{
		echo json_encode(array("300",  "warning|No product found."));
		die;
	}
	Modal::load(array('Lot'));
	$lot = new Lot($lot_id);
	$lotdata['lot_name'] = $lot_name;
	$lotdata['lot_update_date'] = 'NOW()';
	if($lot_id == 0){
		$lotdata['lot_code'] = $lot->getLotCode();
		$lotdata['lot_created_date'] = 'NOW()';
		$lotdata['lot_created_by'] = getLoginId();
		$lotdata['lot_status'] = 1;
		$lot_id = $lot->insert($lotdata);
	}
	else
		$lot->update($lotdata);
	$allExistingItes = $lot->getExistingItemsIds();
	$existingProducts = array();
	foreach($products as $_product){
		$productData = explode("|", $_product);
		$isExist = $lot->isProductInLot($productData[0],$productData[1], $lot_id);
		if(!$isExist){
			$lotProduct = new LotProduct();
			$existingProducts[] = $lotProduct->insert(array(
				"lot_item_lot_id" 		=> $lot_id, 
				"lot_item_code" 		=> $productData[0], 
				"lot_item_product_id" 	=> $productData[1]
			));
		}
		else
			$existingProducts[] = $isExist;
	}
	$delItems = array_diff($allExistingItes, $existingProducts);
	if(count($delItems))
	$lot->unlinkItems($delItems);
	if(isset($_SESSION['LOT']))
	unset($_SESSION['LOT']);
	echo json_encode(array("200",  "success|Lot Saved", $lot_id));

?>