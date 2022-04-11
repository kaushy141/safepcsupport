<?php

	$web_order_id = 0;
	$order_product_images = array();
	$data  = sanitizePostData($_POST);
	extract($data);
	$websiteOrder = new WebsiteOrder($web_order_id);
	if(count($order_product_images) && $websiteOrder->isExist())
	{
		$order = $websiteOrder->load();
		$email = new Email("$order[store_name] Order #$order[web_order_number] Products image");		
        $email->to($order['customer_email'], $order['customer_fname'].' '.$order['customer_lname'], $order['customer_image']);
		//$email->to("kaushyedu@gmail.com", "Kaushal Sachan", $order['customer_image']);
		$tableHtml = '<table widht="100%">';		
		$websiteOrderProduct = new WebsiteOrderProduct();
        $productList = $websiteOrderProduct->getList($web_order_id);
		$productCounter = 1;
		$totalImageCounter = 0;
		foreach($productList as $_product)
		{
			$wopm = new WebsiteOrderProductMedia(0);
			$productImages = $wopm->getImageList($_product['wo_id']);
			if(isset($order_product_images[$_product['wo_id']]) && count($productImages)){
				$tableHtml .= '<tr><td colspan="3">'.($productCounter++).'. '.$_product['wo_product_name'].'</td></tr>';
				$tableHtml .= '<tr>
				<td><a href="'.$_product['wo_product_url'].'"><img height="80px" src="'.$_product['wo_product_image'].'"></a></td>
				<td>SKU<br/><a href="'.$_product['wo_product_url'].'"><strong>'.$_product['wo_product_sku'].'"</strong></a></td>
				<td>SR/No<br/>'.($_product['wo_product_srno'] ? $_product['wo_product_srno'] : "N/A").'</td>
				</tr>';
				$tableHtml .= '<tr><td colspan="3">Media images</td></tr>';
				$tableHtml .= '<tr><td colspan="3">';
				$tableHtml .= '<table widht="100%">';
				$imageCounter = 1;
				$selectedImages = $order_product_images[$_product['wo_id']];
				foreach($productImages as $_image){
					if(in_array($_image['wpoi_id'], $selectedImages))
					{
						$wopi = new WebsiteOrderProductMedia($_image['wpoi_id']);
						$wopi->update(array('wpoi_sent_to_customer' => 1));
						$tableHtml .= '<tr>
						<td width="180px"><img width="180px" src="'.$app->siteUrl($_image['wpoi_image_path']).'"></td>
						<td align = "right">'.($_image['wpoi_title'] ? $_image['wpoi_title'] : "Other").'</td>
						</tr>';
						$email->addFile($_image['wpoi_image_path'], "$_product[wo_product_sku]_$productCounter"."_".$imageCounter."_".basename($_image['wpoi_image_path']));
						$totalImageCounter++;
					}
				}
				$tableHtml .= '</table>';
				$tableHtml .= '</td></tr>';
			}
		}
		$tableHtml .= '</table>';
		$templateData = array(
			"store_name" => $order['store_name'],
			"order_number" => $order['web_order_number'],
			"customer_name" => $order['customer_fname'].' '.$order['customer_lname'],
			"table_html" => $tableHtml
		);
		$email->template('weborder_images_sent', $templateData)->send();
		echo json_encode(array("200",  "success|$totalImageCounter Weborder product image sent to customer"));
	}
	else{
		echo json_encode(array("300",  "warning|Image not found"));
	}

?>