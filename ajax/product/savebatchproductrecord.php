<?php


    $product_id  = 0;
    $data           = sanitizePostData($_POST);
	$product_status = $product_in_stock = $product_is_on_way = 0;
	$product_verified = 'NULL';
	$create_product_copy = $product_under_technician = $product_under_technician_id = 0;
	$no_of_copy = 0;
	$product_age_date = '';
	$product_price = 0;
    extract($data);
	$product_verified = $product_verified ? 'NOW()' : 'NULL';
	$prodValues = compact('product_order_number', 'product_reg_id', 'product_type', 'product_name', 'product_serial_number', 'product_sku', 'product_model', 'product_condition', 'product_processor', 'product_processor_speed', 'product_screen_size', 'product_ram', 'product_ssd', 'product_hdd', 'product_fusion_drive', 'product_release', 'product_reason', 'product_battery_cycle', 'product_operating_system', 'product_grade', 'product_batch_type', 'product_batch_code', 'product_in_stock', 'product_under_technician', 'product_under_technician_id', 'product_age_date', 'product_price', 'product_status', 'product_verified', 'product_part_number', 'product_store_location', 'product_is_on_way');
	$batchProduct = new BatchProduct($product_id);
	if(($product_serial_number != "" && $product_serial_number != "N/A" ) && $batchProduct->isSerialNumberExist($product_serial_number)){
		echo json_encode(array("300", "warning|Serial number \"$product_serial_number\" already exist."));	
		die;
	}
	if($product_id == 0){
		$prodValues['product_code'] = $batchProduct->getBatchProductCode();
		$prodValues['product_created_date'] = date('Y-m-d H:i:s');
		$prodValues['product_created_by'] = getLoginId();
		
		$product_id = $batchProduct->insert($prodValues);
		$detailData = $batchProduct->getDetails();
		Activity::add("Added Batch Product|^|{$prodValues['product_code']}", "B", $product_id);
		//Activity::add("Added  Batch product <b>$detailData[product_code]</b> successfully");
	}
	else{
		$detailData = $batchProduct->getDetails();
		$batchProduct->update($prodValues);
		Activity::add("Updated Batch Product|^|{$detailData['product_code']}", "B", $product_id);
		//Activity::add("Updated  Batch product <b>$detailData[product_code]</b> successfully");
	}
	$copy_message = "";
	$copy_product_code = array();
	if($create_product_copy == 1 && $no_of_copy > 0){
		
		for($i=0; $i < $no_of_copy ; $i++){
			$newBp = new BatchProduct();
			$prodValues['product_code'] = $newBp->getBatchProductCode();
			$copy_product_code[] = $prodValues['product_code'];
			$prodValues['product_created_date'] = date('Y-m-d H:i:s');
			$newBp->insert($prodValues);
			$newBp->update(array('product_serial_number'=>''));
		}
		$copy_message = "<br/>$no_of_copy Copy of this Product Generated <br/>".implode(", ", $copy_product_code);
	}
	
	echo json_encode(array("200", "success|Product details saved successfully $copy_message", $product_id, $copy_message==""?0:1));	

?>