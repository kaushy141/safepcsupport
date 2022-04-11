<?php
	Modal::load(array('Po'));
   
	$po_id = $po_store_id = $po_suplier_id = $po_amount = $po_amount_discount = $po_is_approved = $po_is_closed = $po_status = 0;
	$po_code = $po_title = $po_description = $po_order_date = $po_crew = $po_shipping_via = $po_approved_by = '';
	$po_currency = 'GBP';
	$po_item_sr = $po_item = $po_item_description = $po_item_qty = $po_item_rate = $po_item_amount = $po_item_vat = array();
	
    $data           = sanitizePostData($_POST);
	extract($data);
	if(count($po_item_sr) == 0 || count($po_item_sr) != count($po_item_description)|| count($po_item_sr) != count($po_item_qty)|| count($po_item_sr) != count($po_item_rate)|| count($po_item_sr) != count($po_item_amount)){
		echo json_encode(array("200",  "success|All item details required"));
		die;
	}
	$po_items = compact('po_item_sr', 'po_item', 'po_item_description', 'po_item_qty', 'po_item_rate', 'po_item_amount', 'po_item_vat');
    $po = new Po($po_id);
	$po_data_array = array(
		'po_description' 		=> $po_description, 
		'po_store_id' 			=> $po_store_id, 
		'po_suplier_id' 		=> $po_suplier_id, 
		'po_shipping_address' 	=> $po_shipping_address, 
		'po_currency' 			=> $po_currency, 
		'po_amount_discount' 	=> $po_amount_discount,
		'po_order_date' 		=> $po_order_date, 
		'po_crew' 				=> $po_crew,
		'po_approved_by'		=> $po_approved_by,		
		'po_shipping_via' 		=> $po_shipping_via, 
		'po_is_approved'		=> $po_is_approved, 
		'po_is_closed' 			=> $po_is_closed,
		'po_status' 			=> $po_status
	);
	if($po_id == 0){
		$po_code = $po->getPoCode();		
		$po_data_array['po_code'] = $po_code;
		$po_data_array['po_created_by'] = getLoginId();
		$po_data_array['po_created_date'] = 'NOW()'; 
		$po_data_array['po_is_sent_to_supplier'] = '0'; 
		
		$po_id = $po->insert($po_data_array);
		
		$po->managePoItems($po_items);
		Activity::add("Created|^|$po_code", "U", $po_id);
		echo json_encode(array("200",  "success|Purchase order #$po_code created successfully", $po_id));
	}elseif($po->isExist())
	{		
		if(!$po->isApprooved())
		{
			$po->update($po_data_array);
			$po->managePoItems($po_items);
			$po_code = $po->get('po_code');
			Activity::add("Updated|^|$po_code", "U", $po_id);
			echo json_encode(array("200",  "success|Purchase order #$po_code updated successfully", $po_id));
		}
		else
			echo json_encode(array("300",  "warning|Purchase order was approved. You can't update."));		
	}
	else
		echo json_encode(array("300",  "warning|Purchase order not Found"));

?>