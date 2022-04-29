<?php include("setup.php"); ?>
<?php 
if(isset($_SESSION['app_log_type']))
{
	include($_SESSION['app_log_type'] == "E"?"config/session.php":"config/customer-session.php");
}
?>
<?php 
$allow_action_array = array(
	"E"=>array("viewemployeelist","viewcustomerlist","viewcomplaintlist",),
	"C"=>array("viewcomplaintlist")
);
?>
<?php $app = new App();?>
<?php 
if(isset($_POST['action']))
{
	$action = "";
	$data = array();
	$searchKeyword = $orderPosition = NULL;
	$orderDirection = "ASC"; $draw = 1; $start = 0; $length = 10;
	if(isset($_POST['draw']) && !is_array($_POST['draw']) && $_POST['draw']!="")
	$draw = intval(sanitizePostData($_POST['draw']));
	
	if(isset($_POST['start']) && !is_array($_POST['start']) && $_POST['start']!="")
	$start = intval(sanitizePostData($_POST['start']));
	
	if(isset($_POST['length']) && !is_array($_POST['length']) && $_POST['length']!="")
	$length = intval(sanitizePostData($_POST['length']));
	
	if(isset($_POST['search']['value']) && !is_array($_POST['search']['value']) && $_POST['search']['value']!="")
	$searchKeyword = sanitizePostData($_POST['search']['value']);
	
	if(isset($_POST['order'][0]['column']) && !is_array($_POST['order'][0]['column']) && $_POST['order'][0]['column']!="")
	$orderPosition = sanitizePostData($_POST['order'][0]['column']);
	
	if(isset($_POST['order'][0]['dir']) && !is_array($_POST['order'][0]['dir']) && $_POST['order'][0]['dir']!="" && in_array(strtoupper($_POST['order'][0]['dir']), array("ASC", "DESC")))
	$orderDirection = sanitizePostData($_POST['order'][0]['dir']);
	
	$length = min($length, 100);
	
	if(isset($_POST['action']) && !is_array($_POST['action']))
	$action=$_POST['action']; 
	
	if(isset($_POST['filter']))
	$filter=$_POST['filter']; 
	
	if($action !="" && function_exists($action))
		call_user_func($action);
	else
		echo json_encode(array(404,"Invalid Action found. Please Refresh and try again."));
}
else
	echo json_encode(array(404,"No Action found. Please Refresh and try again."));
?>
<?php
function viewemployeelist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$emp = new Employee(0);
	echo $emp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewsupplieruserlist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$emp = new Employee(0);
	echo $emp->getJsonRecordsSupplier($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}

function viewcustomerlist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$Customer = new Customer(0);
	echo $Customer->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}

function viewcustomerlistcheckbox()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Customer = new Customer(0);
	echo $Customer->getJsonRecordscheckbox($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $_POST['group_id']);
}

function viewcomplaintlist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$Complaint = new Complaint(0);
	if($customer_id = isCustomer())
	$Complaint->customer  = $customer_id;
		echo $Complaint->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}

function viewtaxinfolist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$ComplaintTax = new ComplaintTax(0);
		echo $ComplaintTax->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}

function viewcollectionlist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Collection = new Collection(0);
	if($customer_id = isCustomer())
	$Collection->customer  = $customer_id;
	echo $Collection->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, 0);
}

function viewdropofflist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Collection = new Collection(0);
	if($customer_id = isCustomer())
	$Collection->customer  = $customer_id;
	echo $Collection->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, 1);
}
function viewpickuplist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Collection = new Collection(0);
	if($customer_id = isCustomer())
	$Collection->customer  = $customer_id;
	echo $Collection->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, 2);
}

function viewactivitylist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Activity = new Activity(0);	
	echo $Activity->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}

function viewebaysalesrecords()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$EbaySaleData = new EbaySaleData(0);	
	echo $EbaySaleData->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewfeedbacklist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Feedback = new Feedback(0);
	echo $Feedback->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}

function viewsalesinvoicelist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$SalesInvoice = new SalesInvoice(0);
	if($customer_id = isCustomer())
	$SalesInvoice->customer  = $customer_id;
	echo $SalesInvoice->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}

function viewpendingsalesinvoicelist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$SalesInvoice = new SalesInvoice(0);
	if($customer_id = isCustomer())
	$SalesInvoice->customer  = $customer_id;
	echo $SalesInvoice->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter, true);
}

function viewsalesproductlist()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Product = new Product(0);
	echo $Product->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}

function viewphysicalasset()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$PhysicalAsset = new PhysicalAsset(0);
	echo $PhysicalAsset->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}

function viewcontractinsurance(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$ContractInsurance = new ContractInsurance(0);
	echo $ContractInsurance->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}

function viewsoftwareasset()
{
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$SoftwareAssets = new SoftwareAssets(0);
	echo $SoftwareAssets->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}

function viewutility(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Utilities = new Utilities(0);
	echo $Utilities->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewdisposaldestructionlog(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$DestructionMethod = new DestructionMethod(0);
	echo $DestructionMethod->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewrfcrecord(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Rfc = new Rfc(0);
	echo $Rfc->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewchecklistreport(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$chklistreport = new ChecklistReport(0);
	echo $chklistreport->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewcompnayrecord(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$Company = new Company(0);
	echo $Company->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewsupplierrecord(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$Supplier = new Supplier(0);
	echo $Supplier->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewwebsiteorderlist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$weborder = new WebsiteOrder(0);
	echo $weborder->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewwebsitependingorderlist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$weborder = new WebsiteOrder(0);
	echo $weborder->getJsonPendingRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewwebsiteundercustomerreviewlist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$weborder = new WebsiteOrder(0);
	echo $weborder->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter, true);
}

function viewrefundlist(){
	Modal::load(array('Refund'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$refund = new Refund(0);
	echo $refund->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}

function viewempcontractlist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$emp = new ContractEmployee(0);
	echo $emp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}

function viewemptraininglist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$emp = new Training(0);
	echo $emp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewempleavelist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$emp = new EmployeeLeave(0);
	echo $emp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewempInductionlist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$emp = new EmployeeInduction(0);
	echo $emp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewempLeaverlist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$emp = new EmployeeLeaver(0);
	echo $emp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewempnoticelist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$emp = new EmployeeNotice(0);
	echo $emp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewbatchproductlist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	//var_dump($filter);die;
	$bp = new BatchProduct(0);
	echo $bp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewcollectionitemlist(){
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$bp = new CollectionProcess(0);
	echo $bp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewshipmentlist(){
	Modal::load(array('Shipment'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$shp = new Shipment(0);
	echo $shp->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewwallettransationlist(){
	Modal::load(array('Recharge'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$recharge = new Recharge(0);
	echo $recharge->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewsalescommissionlist()
{
	Modal::load(array('SalesCommission'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$salesCommission = new SalesCommission(0);
	echo $salesCommission->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewpurchaseorderlist()
{
	Modal::load(array('Po'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$po = new Po(0);
	echo $po->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewpurchaseorderinvoicelist()
{
	Modal::load(array('PoInvoice'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$poInvoice = new PoInvoice(0);
	echo $poInvoice->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewpaymentreminderlist(){
	Modal::load(array('PaymentReminder'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length, $filter;
	$paymentReminder = new PaymentReminder(0);
	echo $paymentReminder->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter);
}
function viewcreditnotelist(){
	Modal::load(array('CreditNote'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$modal = new CreditNote(0);
	echo $modal->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function viewlotlist(){
	Modal::load(array('Lot'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$modal = new Lot(0);
	echo $modal->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
function tagnotificationlist(){
	//Modal::load(array('Lot'));
	global $searchKeyword, $orderPosition, $orderDirection, $draw, $start, $length;
	$modal = new UserTag(0);
	echo $modal->getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length);
}
?>