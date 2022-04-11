<?php include("setup.php"); ?>
<?php 
$app = new App();
$app->clearSession();
if(!isset($_SESSION))
session_start();
$_SESSION['APP_ACCESS'] = true;
$lastcronrun = $app->getLastCronScheduleRun();
if(strtotime(date('Y-m-d')) - strtotime($lastcronrun) > (24*60*60))
{	
	$salesInvoice = new SalesInvoice();
	$cycledSalesInvoice = $salesInvoice->getCycledOriginalSalesInvoice();
	if($cycledSalesInvoice != NULL)
	{
		$today = strtotime(date('Y-m-d'));
		foreach($cycledSalesInvoice as $invoice)
		{
			$inv = new SalesInvoice($invoice['sales_invoice_id']);						
			$sales_invoice_id = $inv->autoGenerateCycleInvoice();			
			$inv->updateNextInvoiceDate();
			
			$newinv = new SalesInvoice($sales_invoice_id);
			$invoiceData = $newinv->getDetails();
			new SMS($invoiceData['customer_phone'], "Your Sales Invoice generated successfully. Your Sales Invoice number is #$invoiceData[sales_invoice_number]. Your registered email id is $invoiceData[customer_email] .");
			$dataArray = array(
				"customer_name" => $invoiceData['customer_name'],
				"sales_invoice_number" => $invoiceData['sales_invoice_number'],
				"login_page" => $app->basePath("customer-login.php"),
				"invoice_link" => $app->basePath("salesinvoice/$invoiceData[sales_invoice_id]")
			);
			$email     = new Email("New Sales Invoice #$invoiceData[sales_invoice_number] Generated");
			$email->to($invoiceData['customer_email'], $invoiceData['customer_name']);
			if($send_invoice_to_customer)
			$email->addFile(DOC::SALESINV($sales_invoice_id), $app->siteName . " Sales - $invoiceData[sales_invoice_number].pdf");
			$email->template('newsalesinvoice', $dataArray)->send();
			sleep(1);
		}
	}
	
	WebsiteOrder::checkFulfillment();
	Activity::archiveActivity();
	Activity::archiveLoginLog();
	Activity::updateUserDaysWorkingHours(date('Y-m-d',strtotime("-1 days")));
	$app->updateLastCronScheduleRun();	
	
	Modal::load(array('PaymentReminder'));
	PaymentReminder::createCycleClockReminder();
}
?>