<?php include("phpmailer/PHPMailerAutoload.php"); ?>
<?php include("phpexcel/PHPExcel.php"); ?>
<?php include("classes/Doc.class.php"); ?>
<?php include("classes/Emoji.class.php"); ?>
<?php include("classes/Sms.class.php"); ?>
<?php include("classes/Basic.class.php"); ?>
<?php include("classes/DB.class.php"); ?>
<?php include("classes/App.class.php"); ?>
<?php include("classes/Entities.class.php"); ?>
<?php include("classes/Values.class.php"); ?>
<?php include("classes/StoreLocation.class.php"); ?>
<?php include("classes/Employee.class.php"); ?>
<?php include("classes/Complaint.class.php"); ?>
<?php include("classes/HardwareType.class.php"); ?>
<?php include("classes/CustomerType.class.php"); ?>
<?php include("classes/HardwareProblem.class.php"); ?>
<?php include("classes/Customer.class.php"); ?>
<?php include("classes/CustomerAddress.class.php"); ?>
<?php include("classes/ComplaintStatus.class.php"); ?>
<?php include("classes/ComplaintProblemRecord.class.php"); ?>
<?php include("classes/UserType.class.php"); ?>
<?php include("classes/ComplaintLog.class.php"); ?>
<?php include("classes/ComplaintLogViewer.class.php"); ?>
<?php include("classes/Report.class.php"); ?>
<?php include("classes/ComplaintTax.class.php"); ?>
<?php include("classes/AppInfo.class.php"); ?>
<?php include("classes/FcmManager.class.php"); ?>
<?php include("classes/OtpManager.class.php"); ?>
<?php include("classes/WcItemType.class.php"); ?>
<?php include("classes/WcItem.class.php"); ?>
<?php include("classes/WcStatus.class.php"); ?>
<?php include("classes/Collection.class.php"); ?>
<?php include("classes/WcrItem.class.php"); ?>
<?php include("classes/Email.class.php"); ?>
<?php include("classes/Carrier.class.php"); ?>
<?php include("classes/CarrierVehicle.class.php"); ?>
<?php include("classes/WcRecordMedia.class.php"); ?>
<?php include("classes/EbaySaleData.class.php"); ?>
<?php include("classes/EbayCsv.class.php"); ?>
<?php include("classes/EventList.class.php"); ?>
<?php include("classes/Activity.class.php"); ?>
<?php include("classes/DeviceManager.class.php"); ?>
<?php include("classes/OrderNotification.class.php"); ?>
<?php include("classes/Feedback.class.php"); ?>
<?php include("classes/Store.class.php"); ?>
<?php include("classes/CrmEmailTemplate.class.php"); ?>
<?php include("classes/CustomerGroup.class.php"); ?>
<?php include("classes/CrmTask.class.php"); ?>
<?php include("classes/Schedule.class.php"); ?>
<?php include("classes/Product.class.php"); ?>
<?php include("classes/SalesInvoice.class.php"); ?>
<?php include("classes/Navbar.class.php"); ?>
<?php include("classes/Unsubscribe.class.php"); ?>
<?php include("classes/Pallet.class.php"); ?>
<?php include("classes/CollectionProcess.class.php"); ?>
<?php include("classes/Contract.class.php"); ?>
<?php include("classes/SalaryRegister.class.php"); ?>
<?php include("classes/Message.class.php"); ?>
<?php include("classes/PhysicalAsset.class.php"); ?>
<?php include("classes/Licences.class.php"); ?>
<?php include("classes/ContractInsurance.class.php"); ?>
<?php include("classes/Policies.class.php"); ?>
<?php include("classes/SoftwareAssets.class.php"); ?>
<?php include("classes/ManualTrainingDoc.class.php"); ?>
<?php include("classes/Utilities.class.php"); ?>
<?php include("classes/Barcode.class.php"); ?>
<?php include("classes/DestructionMethod.class.php"); ?>
<?php include("classes/Rfc.class.php"); ?>
<?php include("classes/ChecklistReport.class.php"); ?>
<?php include("classes/Isms.class.php"); ?>
<?php include("classes/Company.class.php"); ?>
<?php include("classes/CompanyInvoice.class.php"); ?>
<?php include("classes/Supplier.class.php"); ?>
<?php include("classes/PalletItems.class.php"); ?>
<?php include("classes/Location.class.php"); ?>
<?php include("classes/WebsiteOrder.class.php"); ?>
<?php include("classes/Credentials.class.php"); ?>
<?php include("classes/Templates.class.php"); ?>
<?php include("classes/BatchProduct.class.php"); ?>
<?php include("classes/Attribute.class.php"); ?>
<?php include("classes/CollectionProcessItem.class.php"); ?>
<?php include("classes/BatchProductSaleHistory.class.php"); ?>
<?php include("classes/ProcessProductSaleHistory.class.php"); ?>
<?php include("classes/Country.class.php"); ?>
<?php include("classes/UserTag.class.php"); ?>
<?php include("classes/JivoChat.class.php"); ?>
<?php
<<<<<<< HEAD
class Modal
{

	static function load($modal_name = null)
	{
		if ($modal_name != null) {
			if (is_array($modal_name)) {
				foreach ($modal_name as $modal) {
					Modal::load($modal);
				}
			} else {
				$modal_file_name = str_replace('.class', '', str_replace('.php', '', trim($modal_name))) . '.class.php';
				if (!file_exists('classes/' . $modal_file_name)) {
					include("classes/" . $modal_file_name);
				} else {
=======
class Modal{
	
	static function load($modal_name=null)
	{		
		if($modal_name != null){
			if(is_array($modal_name)){
				foreach($modal_name as $modal){
					Modal::load($modal);
				}
			}
			else
			{			
				$modal_file_name = str_replace('.class', '', str_replace('.php', '', trim($modal_name))).'.class.php';					
				if(!file_exists('classes/'.$modal_file_name)){
					include("classes/".$modal_file_name);
				}
				else{
>>>>>>> 77a717f (Version 2)
					die("Requested modal '$modal_name' could not loaded");
				}
			}
		}
	}
	static function loadMailer()
	{
		include("phpmailer/PHPMailerAutoload.php");
	}
	static function loadPhpExcel()
	{
		include("phpexcel/PHPExcel.php");
	}
	static function loadScript($script)
	{
<<<<<<< HEAD
		include(BP . rtrim(trim($script), '.php') . '.php');
	}
}

class MediaSection
{
=======
		include(BP.rtrim(trim($script), '.php').'.php');
	}
}

class MediaSection{
>>>>>>> 77a717f (Version 2)
	public static $collectionMedia = "Collection Media";
	public static $repairMedia = "Repair Media";
	public static $InvoiceMedia = "Invoice Media";
	public static $SalesProductMedia = "Sales Product Media";
	public static $batchProductMedia = "Batch Product Media";
	public static $collectionProductMedia = "Collection Product Media";
	public static $webOrderProductMedia = "Web Order Product Media";
	public static $webOrderLabelMedia = "Web Order Label Media";
	public static $repairLabelInvoiceMedia = "Repair Label Invoice Media";
	public static $salesLabelInvoiceMedia = "Sales Label Invoice Media";
	public static $webOrderCommissionFile = "Web Order Commission Media";
	public static $buybackProductMedia = "Buyback Order Media";
}
?>