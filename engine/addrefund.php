<?php
admin();
$refund_type_code = isset($parameter1) ? $parameter1 : 'Z';
$refund_type_id = isset($parameter2) ? $parameter2 : 0;
$refund_id = 0;
$method = 'INIT';

Modal::load(array('Section', 'Refund'));
if($refund_type_code && $refund_type_id){
	if($refund_type_code == 'O')
	{
		$weborder = new WebsiteOrder($refund_type_id);
		$data = $weborder->load();
		$custumer = new Customer($data['web_order_customer_id']);
		$customerData = $custumer->getDetails();
		
		$refund = new Refund();
		$isRefundExist = $refund->isRefundExist($refund_type_code, $refund_type_id);
		
		$RefundWarning = $isRefundExist ? "This Web Order #$data[web_order_number] 's Refund already initiated. Make sure before proceed" : "";
		
		$weborderProducts = new WebsiteOrderProduct();
		$productList = $weborderProducts->getList($data['web_order_id']);
		
			
		$formData = array(
			'refund_to_id' => $customerData['customer_id'],
			'refund_to_name' => $customerData['customer_fname']. ' '.$customerData['customer_lname'],
			'refund_to_company' => $customerData['customer_company'],
			'refund_to_email' => $customerData['customer_email'],
			'refund_to_phone_number' => $customerData['customer_phone'],
			'refund_for_name' => "Web Order",
			'refund_for_reference' => $data['web_order_number'],
			'refund_for_date' => $data['web_order_created_date'],
			'refund_for_status'=>$data['wc_status_name'],
			'refund_for_currency'=>$data['web_order_currency']
			
		);	
		
		if($productList){
			foreach($productList as $_product){
				$formData['refund_products'][] = array(
					"product_id" => $_product['wo_id'],
					"product_name" => $_product['wo_product_name'],
					"product_sku" => $_product['wo_product_sku'],
					"product_image" => $_product['wo_product_image'],
					"product_quantiry" => $_product['wo_product_quantity'],
					"product_srno" => $_product['wo_product_srno'],
					"product_price" => $_product['wo_product_sell_price']
				);
			}
		}		
	}	
	elseif($refund_type_code == 'S')
	{
		$salesinvoice = new SalesInvoice($refund_type_id);
		$data = $salesinvoice->load();
		$custumer = new Customer($data['sales_invoice_customer_id']);
		$customerData = $custumer->getDetails();
		
		$refund = new Refund();
		$isRefundExist = $refund->isRefundExist($refund_type_code, $refund_type_id);
		
		$RefundWarning = $isRefundExist ? "This Sales Invoice #$data[sales_invoice_number] 's Refund already initiated. Make sure before proceed" : "";
		
		$productList = $salesinvoice->getProducts();
					
		$formData = array(
			'refund_to_id' => $customerData['customer_id'],
			'refund_to_name' => $customerData['customer_fname']. ' '.$customerData['customer_lname'],
			'refund_to_company' => $customerData['customer_company'],
			'refund_to_email' => $customerData['customer_email'],
			'refund_to_phone_number' => $customerData['customer_phone'],
			'refund_for_name' => "Sales Invoice",
			'refund_for_reference' => $data['sales_invoice_number'],
			'refund_for_date' => $data['sales_invoice_created_date'],
			'refund_for_status'=>$data['sales_invoice_is_paid'] ? "Paid":"Unpaid",
			'refund_for_currency'=>'GBP'
			
		);	
		
		if($productList){
			foreach($productList as $_product){
				$formData['refund_products'][] = array(
					"product_id" => $_product['sipd_id'],
					"product_name" => $_product['product_name'],
					"product_sku" => $_product['product_srno'],
					"product_image" => '',
					"product_quantiry" => $_product['product_quantity'],
					"product_srno" => $_product['product_srno'],
					"product_price" => $_product['product_price']*$_product['product_quantity']
				);
			}
		}		
	}	
	
	extract($formData);
}
$btnText = "Initiate Refund";
$action	=	"refund/initiaterefund";
$formHeading	=	"Initiate Refund";
?>
<?php include("engine/inc/addrefundform.php"); ?>