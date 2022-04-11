<?php
admin();
$refund_md5_id = $parameter1;
Modal::load(array('Section', 'Refund', 'RefundProduct'));
$refund = new Refund();
$refund_id = 0;
$method = 'FINALISE';
if($refund->loadByMd5($refund_md5_id))
{
	$refundData = $refund->getDetails();
	extract($refundData);
		
	if($refund_type_code && $refund_type_id)
	{
		$RefundWarning = $refund_completed_by ? "This Refund <strong>#$refund_code</strong> already ".($refund_status == 1 ? "Completed" : "Cancelled")." by <strong>$processor_name</strong>" : "";	
			
		$refundProducts = RefundProduct::getRefundProducts($refund_id);
		$refundingProducts = array();
		if($refundProducts){
			foreach($refundProducts as $_product){
				$refundingProducts[] = array(
					"refund_pro_id" => $_product['refund_pro_id'],
					"refund_pro_name" => $_product['refund_pro_name'],
					"refund_pro_sku" => $_product['refund_pro_sku'],
					"refund_pro_srno" => $_product['refund_pro_sr_number'],
					"refund_pro_price" => $_product['refund_pro_sell_price'],
					"refund_pro_refund" => $_product['refund_pro_refund_price']
				);
			}
		}
		if($refund_type_code == 'O')
		{
			$weborder = new WebsiteOrder($refund_type_id);
			$data = $weborder->load();
			$custumer = new Customer($data['web_order_customer_id']);
			$customerData = $custumer->getDetails();
							
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
		}
		elseif($refund_type_code == 'S')
		{
			$salesinvoice = new SalesInvoice($refund_type_id);
			$data = $salesinvoice->load();
			$custumer = new Customer($data['sales_invoice_customer_id']);
			$customerData = $custumer->getDetails();
							
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
		}	
		$formData['refund_products'] = $refundingProducts;
		extract($formData);
	}
	$btnText = "Finish Refund";
	$action	=	"refund/finalisefund";
	$formHeading	=	"$refund_code Finalise Refund";
	include("engine/inc/addrefundform.php"); 
}
else
{
	include("engine/403.php"); 
}
?>