<?php
	Modal::load(array('Section', 'Refund', 'RefundProduct'));
    
    $refund_item_price =  $refund_id =  $refund_customer_id =  $refund_type_id =  $refund_amount =  0;
	$refund_type_code =  $refund_comments = '';
    $data           = sanitizePostData($_POST);
    $refund        = new Refund($refund_id);
    extract($data);
	$section = new Section($refund_type_code);
	$customer = new Customer($refund_customer_id);
	if($section->isExist() && $customer->isExist()){
		$sectionDetail = $section->getDetails();
		$customerDetail = $customer->getDetails();
		$isFullRefund = true;
		if($sectionDetail['activity_section_code'] === 'O')
		{
			$weborder = new WebsiteOrder($refund_type_id);
			if(!$weborder->isExist()){
				echo json_encode(array("300",  "warning|Requested Order not found"));
				exit();
			}
			$weborderData = $weborder->getDetails();
			$refund_reference = "Web Order ".$weborderData['web_order_number'];
			$refund_amount_currency = $weborderData['web_order_currency'];
			
			$weborderProducts = new WebsiteOrderProduct();
			$productList = $weborderProducts->getList($weborderData['web_order_id']);
			$refundingProducts = array();
			foreach($productList as $_pro)
			{
				if($refund_item_price[$_pro['wo_id']] != $_pro['wo_product_sell_price'])
				{
					$isFullRefund = false;
				}
				$refundingProducts[] = array(
					"refund_pro_ref_id"=>$_pro['wo_id'],
					"refund_pro_name"=>$_pro['wo_product_name'],
					"refund_pro_sku"=>$_pro['wo_product_sku'],
					"refund_pro_sr_number"=>$_pro['wo_product_srno'],
					"refund_pro_sell_price"=>$_pro['wo_product_sell_price'],
					"refund_pro_refund_price"=>$refund_item_price[$_pro['wo_id']]
				);	
			}
		}
		elseif($sectionDetail['activity_section_code'] === 'S')
		{
			$saleinvoice = new SalesInvoice($refund_type_id);
			if(!$saleinvoice->isExist()){
				echo json_encode(array("300",  "warning|Requested Invoice not found"));
				exit();
			}
			$saleinvoiceData = $saleinvoice->getDetails();
			$refund_reference = "Sales ".$saleinvoiceData['sales_invoice_number'];
			$refund_amount_currency = 'GBP';
			
			$productList = $saleinvoice->getProducts();
			$refundingProducts = array();
			foreach($productList as $_pro)
			{
				if($refund_item_price[$_pro['sipd_id']] != ($_pro['product_price']*$_pro['product_quantity']))
				{
					$isFullRefund = false;
				}				
				$refundingProducts[] = array(
					"refund_pro_ref_id"=>$_pro['sipd_id'],
					"refund_pro_name"=>$_pro['product_name'],
					"refund_pro_sku"=>$_pro['product_srno'],
					"refund_pro_sr_number"=>$_pro['product_srno'],
					"refund_pro_sell_price"=>$_pro['product_price']*$_pro['product_quantity'],
					"refund_pro_refund_price"=>$refund_item_price[$_pro['sipd_id']]
				);	
			}
		}
		else
			echo json_encode(array("300",  "warning|Not a valid refund section"));
		
		#==================================================================================	
		$refund_code = $refund->getRefundCode();
		$refundData = array(
			"refund_code" 			=> $refund_code,
			"refund_type_code" 		=> $refund_type_code,
			"refund_type_id" 		=> $refund_type_id,
			"refund_store_id"		=> $refund->getStoreId($refund_type_code, $refund_type_id),
			"refund_reference"		=> $refund_reference,
			"refund_customer_id" 	=> $refund_customer_id,
			"refund_amount"			=> $refund_amount,
			"refund_amount_currency"=> $refund_amount_currency,
			"refund_pattern"		=> REFUND_STATUS_FULL,
			"refund_initiated_by"	=> getLoginId(),
			"refund_initiated_date" => "NOW()", 
			"refund_status" 		=> 2				
			);
		$refund_id = $refund->insert($refundData);
		if($refund_id)
		{
			if(trim($refund_comments) != "" && $refund->isExist())
			{
				$log = new ComplaintLog();
				$log->add($refund_id, 'R', $refund_comments, 'TEXT', 1, 0);
			}
			foreach($refundingProducts as $product)
			{
				$refundProduct = new RefundProduct();
				$refundProduct->insert(array_merge($product, array("refund_pro_refund_id"=>$refund_id)));										
			}
			Activity::add("Initiated Refund for $refund_reference|^|$refund_code", "R", $refund_id);
			
			if(!$isFullRefund)
			$refund->update(array("refund_pattern"=>REFUND_STATUS_PARTIAL));
			echo json_encode(array("200",  "success|Refund Request Initiated", 'refundprocess/'.md5($refund_id)));
		}
		else
			echo json_encode(array("300",  "warning|Refund Request not initialized"));		
	}
	else
		echo json_encode(array("300",  "warning|Customer or Section not Found"));

?>