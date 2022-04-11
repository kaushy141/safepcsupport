<?php

	$data_code = $data_id = $data_sku = $data_item_id = $data_product_id = $data_type_code = $data_product_code = $sell_price = $store_reference = $sell_date = $remark = "";
	$data  = sanitizePostData($_POST);
	extract($data);	
	
	$weborder = new WebsiteOrder($data_id);
	if($weborder->isExist()){
		$weborderData = $weborder->getDetails();
		$customer_id = $weborderData['web_order_customer_id'];
		$address_id = $weborderData['web_order_address_id'];
		if($data_type_code == "B"){
			$batchProduct = new BatchProduct($data_product_id);
			$productData = $batchProduct->getDetails();
			if($productData['product_in_stock'] == 1)
			{
				$bpca_product_id = $productData['product_id'];
				$bpca_customer_id = $customer_id;
				$bpca_customer_address_id = $address_id;
				$bpca_sell_price = $sell_price;
				$bpca_sell_date = $sell_date;
				$bpca_is_returned = 0;
				$bpca_store_id = $weborderData['web_order_website_id'];
				$bpca_store_reference = $store_reference;
				$bpca_remark = $remark;
				$bpca_created_by = getLoginId();
				$bpca_status = 1;
				
				$wo_process_code = $productData['product_code'];
				$wo_product_srno = $productData['product_serial_number'];
				
				$BatchProductSaleHistory = new BatchProductSaleHistory(0);
				$bpcaData = compact('bpca_product_id', 'bpca_customer_id', 'bpca_customer_address_id', 'bpca_sell_price', 'bpca_sell_date', 'bpca_is_returned', 'bpca_store_id', 'bpca_store_reference', 'bpca_remark', 'bpca_created_by', 'bpca_status');
				
				$bpca_id     = $BatchProductSaleHistory->insert($bpcaData);
				$batchProduct->update(array('product_in_stock' => 0));
				Activity::add("Saved Batch Product Sales Record|^|$productData[product_code]", "B", $bpca_product_id);
			}
			else{
				echo json_encode(array("300", "warning|Product is out of stock"));
				die;
			}
		}
		elseif($data_type_code == "P"){
			$colProcess = new CollectionProcess($data_product_id);
			$productData = $colProcess->getDetails();
			
			if($productData['wc_process_item_stock'] == 1)
			{
				$wpca_process_code = $productData['product_id'];
				$wpca_customer_id = $customer_id;
				$wpca_customer_address_id = $address_id;
				$wpca_sell_price = $sell_price;
				$wpca_sell_date = $sell_date;
				$wpca_is_returned = 0;
				$wpca_store_id = $weborderData['web_order_website_id'];
				$wpca_store_reference = $store_reference;
				$wpca_remark = $remark;
				$wpca_created_by = getLoginId();
				$wpca_status = 1;
				
				$wo_process_code = $productData['wc_process_asset_code'];
				$wo_product_srno = $productData['wc_process_item_sr_no'];
				
				$ProcessProductSaleHistory = new ProcessProductSaleHistory(0);
				$bpcaData = compact('wpca_process_code', 'wpca_customer_id', 'wpca_customer_address_id', 'wpca_sell_price', 'wpca_sell_date', 'wpca_is_returned', 'wpca_store_id', 'wpca_store_reference', 'wpca_remark', 'wpca_created_by', 'wpca_status');
				
				$wpca_id     = $ProcessProductSaleHistory->insert($bpcaData);
				$colProcess->update(array('wc_process_item_stock' => 0));
				Activity::add("Saved Process Product Sales Record|^|$wpca_process_code", "P", $wpca_process_code);
			}
			else{
				echo json_encode(array("300", "warning|Product is out of stock"));
				die;
			}
		}
		$websiteOrderProduct = new WebsiteOrderProduct($data_item_id);
		$websiteOrderProduct->update(array("wo_process_code"=>$wo_process_code, "wo_product_srno"=>$wo_product_srno));
		echo json_encode(array("200", "success|Product assigned and set to out of stock"));
	}

?>