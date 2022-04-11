<?php
Modal::load(array('ProductAvailbility'));
	$key_id	= "";
	$pro_avail_supplier_id  = "";
	$pro_avail_checked_time = "";
	$pro_avail_stock_status = "";
	
	$data           = sanitizePostData($_POST);
    extract($data);
	$keyIdArray = explode("|", $key_id);
	$pro_avail_section_code = $keyIdArray[0];
	$pro_avail_order_id = isset($keyIdArray[1]) ? $keyIdArray[1] : 0;
	$pro_avail_product_id = isset($keyIdArray[2]) ? $keyIdArray[2] : 0;
	$productAvailbility = new ProductAvailbility($customer_id);
	if($pro_avail_section_code != "" && $pro_avail_order_id != 0 && $pro_avail_product_id != 0){
		$insertData = array();
		
		if($pro_avail_section_code == 'O'){
			$websiteOrder = new WebsiteOrder($pro_avail_order_id);
			$record = $websiteOrder->getDetails();
			$activity_code = $record['web_order_number'];
		}elseif($pro_avail_section_code == 'S'){
			$salesInvoice = new SalesInvoice($pro_avail_order_id);
			$record = $salesInvoice->getDetails();
			$activity_code = $record['sales_invoice_number'];
		}
				
		$insertData['pro_avail_section_code'] = $pro_avail_section_code;
		$insertData['pro_avail_order_id'] = $pro_avail_order_id;
		$insertData['pro_avail_product_id'] = $pro_avail_product_id;
		$insertData['pro_avail_supplier_id'] = $pro_avail_supplier_id;
		$insertData['pro_avail_remark'] = $pro_avail_remark;
		$insertData['pro_avail_checked_user_id'] = getLoginId();
		$insertData['pro_avail_checked_time'] = $pro_avail_checked_time;
		$insertData['pro_avail_created_date'] = 'NOW()';
		$insertData['pro_avail_stock_status'] = $pro_avail_stock_status;
		$insertData['pro_avail_status'] = 1;
		
		$productAvailbility->insert($insertData);
		
		Activity::add("added Product availability check|^|{$activity_code}", $pro_avail_section_code, $pro_avail_order_id);
		echo json_encode(array("200",  "success|Activity checked status saved"));
	}
	else{
		echo json_encode(array("300",  "success|Required data not found"));
	}

?>