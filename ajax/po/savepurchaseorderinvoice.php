<?php

	Modal::load(array('Po', 'PoInvoice'));
    
	
	$pob_invoice_date = $pob_due_date = $pob_terms = $pob_mailing_address = "";
	$po_id = $pob_amount_discount = $add_items_in_collection_stock = 0;
	$po_item_sr = $pobi_poi = $pobi_item = $pobi_item_description = $pobi_item_quantity = $pobi_item_rate = $pobi_item_vat = $pobi_item_amount = $pobi_item_make = $pobi_item_model = array();

    $data           = sanitizePostData($_POST);
	extract($data);
	if(count($po_item_sr) == 0 || count($po_item_sr) != count($pobi_item)|| count($po_item_sr) != count($pobi_item_description)|| count($po_item_sr) != count($pobi_item_quantity)|| count($po_item_sr) != count($pobi_item_rate)|| count($po_item_sr) != count($pobi_poi)|| count($po_item_sr) != count($pobi_item_amount)){
		echo json_encode(array("200",  "success|All item details required"));
		die;
	}
    $po = new Po($po_id);
	if($po->isExist())
	{
		$poData = $po->getDetails();
		if($poData['po_is_closed']){
			echo json_encode(array("300",  "warning|Purchase order {$poData['po_code']} was closed. can't generate Invoice on this Purchase order"));
			die;
		}
		$pobItems = new PobItems(0);
		if($fulledItems = $po->hasClosedOrFullItems($pobi_poi)){
			echo json_encode(array("300",  "warning|$fulledItems Selected items from purchase order {$poData['po_code']} was closed or Completed. Remove these items and try again."));
			die;
		}
		
		$pob = new PoInvoice();
		$pob_code = $pob->getPobCode();		
		$pob_data_array = array(
			'pob_code' 				=> $pob_code, 
			'pob_mailing_address' 	=> $pob_mailing_address, 
			'pob_description' 		=> $pob_description, 
			'pob_po_id' 			=> $poData['po_id'], 
			'pob_suplier_id' 		=> $poData['po_suplier_id'], 
			'pob_invoice_date' 		=> $pob_invoice_date, 
			'pob_due_date' 			=> $pob_due_date,
			'pob_amount_discount' 	=> $pob_amount_discount, 
			'pob_currency' 			=> $poData['po_currency'],
			'pob_terms' 			=> $pob_terms, 
			'pob_created_date'		=> 'NOW()', 
			'pob_created_by' 		=> getLoginId(),
			'pob_status' 			=> '1'
		);
		$pob_id = $pob->insert($pob_data_array);
			
		foreach($po_item_sr as $item => $srno)
		{			
			$pobi_pob_id 		= $pob_id;
			$pobi_po_id 		= $poData['po_id'];
			$pobi_poi_id 		= $pobi_poi[$item];
			$pobi_item_id 		= $pobi_item[$item];
			$pobi_description 	= $pobi_item_description[$item];
			$pobi_make			= $pobi_item_make[$item];
			$pobi_model			= $pobi_item_model[$item];
			$pobi_quantity		= $pobi_item_quantity[$item];
			$pobi_rate			= $pobi_item_rate[$item];
			$pobi_vat			= $pobi_item_vat[$item];
			$pobi_vat_amount	= round($pobi_quantity * (($pobi_rate * $pobi_vat)/100), 2);
			$pobi_amount 		= round($pobi_quantity * $pobi_rate, 2);
			
			$pobiData = compact('pobi_pob_id', 'pobi_po_id', 'pobi_poi_id', 'pobi_item_id', 'pobi_make', 'pobi_model', 'pobi_description', 'pobi_rate', 'pobi_vat', 'pobi_quantity', 'pobi_vat_amount', 'pobi_amount');	
			$pobItems = new PobItems(0);			
			if(!PoItems::isClosed($pobi_po_id, $pobi_poi_id)){
				$pobItems->insert($pobiData);
				PoItems::updateReceivedItems($pobi_po_id, $pobi_poi_id, $pobi_quantity, $pobi_amount);
			}
		}
		$pob->setPobAmount();
		
		$wc_created_by = $customer_created_by = $_SESSION['user_id'];
		if($add_items_in_collection_stock == 1)
		{
			$Customer        = new Customer(0);
			if(!$wc_customer_id  = $Customer->isEmailExists($poData['supplier_email'])){
				$customer_image  = $poData['supplier_image'] ?: DEFAULT_USER_IMAGE;
				$customer_password           = gePassword();
				
				$customer_code   = $Customer->getNewCode($poData['supplier_email'], $poData['supplier_name']);
				$wc_customer_id  = $Customer->add($customer_code, $poData['supplier_name'], '', $poData['supplier_email'], $poData['supplier_contact'], $poData['supplier_company_name'], 8, $customer_image, 1, $customer_password, $customer_created_by, 1, 1);
			}
			$customerAddress = CustomerAddress::getCustomerAddress($wc_customer_id);
			if(count($customerAddress) > 0){
				$wc_customer_address_id = $customerAddress[0]['customer_address_id'];
			}
			else
			{
				$CustomerAddress        = new CustomerAddress(0);
				if (!$wc_customer_address_id = $CustomerAddress->isCustomerAddressExists($wc_customer_id, '-', '-', '-', '-', '-'))
					$wc_customer_address_id = $CustomerAddress->add($wc_customer_id, '-', '-', '-', '-', '-', '', '', 1);
			}
			
			$collection    = new Collection();
			$wc_code       = $collection->getWcCode('');
			$wc_ip_address = IP_ADDRESS;
			$wc_status     = 1;
			
			//$wc_manager_id = 1;
			//$wc_driver_id = 60; // Costel Codrianu
			//$wc_carrier_id = 1; // Safe Pc Disposal
			//$wc_vehicle_id = 1; // 
			$wc_status_id = 1;
			$wc_due_date = $pob_due_date;
			//$wc_loading_time = '00:00';
			$wc_help_member = $poData['po_crew'];
			$wc_is_local_authority = 1;
			$wc_mail_to_customer = 0;
			$wc_mail_to_collector = 0;
			$wc_collection_report = 0;
			$wc_transfer_note = 0;
			$wc_consignment_note = '';
			//$wc_consignment_note_code = '';
			//$wc_on_behalf_of_user = '';
			$wc_is_drop_off = 0;
			//$wc_drop_off_driver = '';
			//$wc_drop_off_vehicle = '';
			
			$wc_id         = $collection->add($wc_code, $wc_customer_id, $wc_customer_address_id, $wc_manager_id, $wc_driver_id, $wc_carrier_id, $wc_vehicle_id, $wc_status_id, $wc_due_date, $wc_loading_time, $wc_help_member,  $wc_is_local_authority, $wc_mail_to_customer, $wc_mail_to_collector, $wc_collection_report, $wc_transfer_note, $wc_consignment_note, $wc_consignment_note_code, $wc_on_behalf_of_user, $wc_created_by, $wc_ip_address, $wc_status, $wc_is_drop_off, $wc_drop_off_driver, $wc_drop_off_vehicle);
			
			if (!empty($po_item_sr)) {
				$WcrItem = new WcrItem();
				foreach($po_item_sr as $item => $srno){
					$WcrItem->add($wc_id, $pobi_item[$item], $pobi_item_quantity[$item], 0, 1, 0, 0, 'CHARGE', $pobi_item_description[$item]);
					if(WcItem::getItemSerializeType($pobi_item[$item]) == SERIALIZED)
					{
						for($j=0; $j < $pobi_item_quantity[$item]; $j++){
							$wc_process_asset_code = CollectionProcess::getProcessCode($wc_id, $wc_code);
							CollectionProcess::addProcess($wc_process_asset_code, $wc_id, $pobi_item[$item], $pobi_item_make[$item], $pobi_item_model[$item], $pobi_item_description[$item], '', 0, 1, 0, 1);
						}
					}
					else
					{
						$wc_process_asset_code = CollectionProcess::getProcessCode($wc_id, $wc_code);
						CollectionProcess::addProcess($wc_process_asset_code, $wc_id, $pobi_item[$item], $pobi_item_make[$item], $pobi_item_model[$item], $pobi_item_description[$item], '', 0, $pobi_item_quantity[$item], 0, 0);
					}
					
				}
			}
		}
		
		Activity::add("Created|^|$pob_code", "D", $pob_id);
		echo json_encode(array("200",  "success|Purchase order invoice #$pob_code created successfully", $pob_id));
	}
	else
		echo json_encode(array("300",  "warning|Purchase order not Found"));

?>