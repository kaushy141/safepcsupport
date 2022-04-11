<?php
if(isset($parameter1))
{
	$sales_invoice_id = $parameter1;
	$SalesInvoice = new SalesInvoice($sales_invoice_id);
	$data = $SalesInvoice->load();
	if($data)
	{		
		$currency = $data['sales_invoice_currency'] ? $data['sales_invoice_currency'] : 'GBP';
		$products = $SalesInvoice->getProducts();
		$sumAmount = $SalesInvoice->getInvoiceSum($data['store_vat_percent']);
		$data['product_sum_amount']	= number_format($sumAmount['product_sum_amount'],2);
		$data['product_sum_vat']	= number_format($sumAmount['product_sum_vat'],2);
		$data['product_discount']	= number_format($data['sales_discount_amount'],2);
		$data['product_sum_total']	= number_format(($sumAmount['product_sum_vat'] + $sumAmount['product_sum_amount'] - $data['sales_discount_amount']),2);	
		extract($data);
		$action	=	"sales/updatesalesinvoice";
		$formHeading	=	"Sales Invoice #$sales_invoice_number ";
		$btnText	=	"Save";
		if(isAdmin())
			include("engine/inc/addsalesinvoiceform.php");
		else
			include("engine/inc/viewsalesinvoicecustomer.php");
	}
	else
		include("engine/404.php");
}
else
	include("engine/404.php");
?>