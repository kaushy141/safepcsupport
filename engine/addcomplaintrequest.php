<?php
admin();
$action	=	"repair/addcomplaint";
$formHeading	=	"Add Repair(Request)";
$btnText	=	"ADD";

if(isset($parameter1) && $parameter1 != ''){
	if(isset($parameter2) && $parameter2 != '' && isset($parameter3) && $parameter3 != '')
	if($parameter1 == 'O'){
		$weborder = new WebsiteOrder($parameter2);
		$weborderData = $weborder->getDetails();
		$customer = new Customer($weborderData['web_order_customer_id']);
		$customerData = $customer->getDetails();		
		extract($customerData);
		
		$customerAddress = new CustomerAddress($weborderData['web_order_address_id']);
		$customerAddressData = $customerAddress->getDetails();
		extract($customerAddressData);
		
		$product = new WebsiteOrderProduct($parameter3);
		$productInfo = $product->getDetails();
		
		$productData['complaint_product_sku'] = $productInfo['wo_product_sku'];
		$productData['complaint_product_serial'] = $productInfo['wo_product_srno'];
		$productData['complaint_order_number'] = $weborderData['web_order_number'];
		$productData['complaint_store_id'] = $weborderData['web_order_website_id'];
		$productData['complaint_description'] = "Order #{$weborderData['web_order_number']} Dated - ".date('D, d M Y h:i A', strtotime($weborderData['web_order_created_date']))."\n{$productInfo['wo_product_name']}";
		if($productInfo['wo_product_options'] != null){
			$productOptions = json_decode($productInfo['wo_product_options'], true);
			foreach($productOptions as $_pOption){
				$productData['complaint_description'].= "\n{$_pOption['label']} : {$_pOption['value']}";
			}
		}
		
		extract($productData);
	}
	elseif($parameter1 == 'S'){
		$salesinvoice = new SalesInvoice($parameter2);
		$salesData = $salesinvoice->getDetails();
		$customer = new Customer($salesData['sales_invoice_customer_id']);
		$customerData = $customer->getDetails();		
		extract($customerData);
		
		$customerAddress = new CustomerAddress($salesData['sales_invoice_customer_address_id']);
		$customerAddressData = $customerAddress->getDetails();
		extract($customerAddressData);
		
		$product = new SalesInvoiceProduct($parameter3);
		$productInfo = $product->getDetails();
		
		$productData['complaint_product_sku'] = $productInfo['sipd_product_sku'];
		$productData['complaint_product_serial'] = $productInfo['sipd_product_serial_details'];
		$productData['complaint_order_number'] = $salesData['sales_invoice_number'];
		$productData['complaint_store_id'] = $salesData['sales_invoice_store_id'];
		
		$productData['complaint_description'] = "Sales Invoice #{$salesData['sales_invoice_number']} Dated - ".date('D, d M Y h:i A', strtotime($salesData['sales_invoice_created_date']))."\n{$productInfo['product_name']}";
		
		extract($productData);
	}
	elseif($parameter1 == 'K'){
		Modal::load(array('BuybackOrder'));
		$buybackOrder = new BuybackOrder($parameter2);
		$buybackData = $buybackOrder->load();
		$customer = new Customer($buybackData['customer_id']);
		$customerData = $customer->getDetails();		
		extract($customerData);
						
		$productData['complaint_product_sku'] = $buybackData['product_sku'];
		$productData['complaint_order_number'] = $buybackData['order_number'];
		$productData['complaint_store_id'] = $buybackData['website_id'];
		
		$productData['complaint_description'] = "Buyback Order #{$buybackData['order_number']} Dated - ".date('D, d M Y h:i A', strtotime($buybackData['created_at']))."\n{$buybackData['product_name']}";
		
		extract($productData);
	}
}
?>
<?php include("engine/inc/addcomplaintrequestform.php"); ?>