 <?php
 include("phpmailer/PHPMailerAutoload.php");
 include("phpexcel/PHPExcel.php");
 include("classes/Doc.class.php");
 include("classes/Emoji.class.php");
 include("classes/Sms.class.php");
 include("classes/Basic.class.php");
 include("classes/DB.class.php");
 include("classes/App.class.php");
 include("classes/Entities.class.php");
 include("classes/Values.class.php");
 include("classes/StoreLocation.class.php");
 include("classes/Employee.class.php");
 include("classes/Complaint.class.php");
 include("classes/HardwareType.class.php");
 include("classes/CustomerType.class.php");
 include("classes/HardwareProblem.class.php");
 include("classes/Customer.class.php");
 include("classes/CustomerAddress.class.php");
 include("classes/ComplaintStatus.class.php");
 include("classes/ComplaintProblemRecord.class.php");
 include("classes/UserType.class.php");
 include("classes/ComplaintLog.class.php");
 include("classes/ComplaintLogViewer.class.php");
 include("classes/Report.class.php");
 include("classes/ComplaintTax.class.php");
 include("classes/AppInfo.class.php");
 include("classes/FcmManager.class.php");
 include("classes/OtpManager.class.php");
 include("classes/WcItemType.class.php");
 include("classes/WcItem.class.php");
 include("classes/WcStatus.class.php");
 include("classes/Collection.class.php");
 include("classes/WcrItem.class.php");
 include("classes/Email.class.php");
 include("classes/Carrier.class.php");
 include("classes/CarrierVehicle.class.php");
 include("classes/WcRecordMedia.class.php");
 include("classes/EbaySaleData.class.php");
 include("classes/EbayCsv.class.php");
 include("classes/EventList.class.php");
 include("classes/Activity.class.php");
 include("classes/DeviceManager.class.php");
 include("classes/OrderNotification.class.php");
 include("classes/Feedback.class.php");
 include("classes/Store.class.php");
 include("classes/CrmEmailTemplate.class.php");
 include("classes/CustomerGroup.class.php");
 include("classes/CrmTask.class.php");
 include("classes/Schedule.class.php");
 include("classes/Product.class.php");
 include("classes/SalesInvoice.class.php");
 include("classes/Navbar.class.php");
 include("classes/Unsubscribe.class.php");
 include("classes/Pallet.class.php");
 include("classes/CollectionProcess.class.php");
 include("classes/Contract.class.php");
 include("classes/SalaryRegister.class.php");
 include("classes/Message.class.php");
 include("classes/PhysicalAsset.class.php");
 include("classes/Licences.class.php");
 include("classes/ContractInsurance.class.php");
 include("classes/Policies.class.php");
 include("classes/SoftwareAssets.class.php");
 include("classes/ManualTrainingDoc.class.php");
 include("classes/Utilities.class.php");
 include("classes/Barcode.class.php");
 include("classes/DestructionMethod.class.php");
 include("classes/Rfc.class.php");
 include("classes/ChecklistReport.class.php");
 include("classes/Isms.class.php");
 include("classes/Company.class.php");
 include("classes/CompanyInvoice.class.php");
 include("classes/Supplier.class.php");
 include("classes/PalletItems.class.php");
 include("classes/Location.class.php");
 include("classes/WebsiteOrder.class.php");
 include("classes/Credentials.class.php");
 include("classes/Templates.class.php");
 include("classes/BatchProduct.class.php");
 include("classes/Attribute.class.php");
 include("classes/CollectionProcessItem.class.php");
 include("classes/BatchProductSaleHistory.class.php");
 include("classes/ProcessProductSaleHistory.class.php");
 include("classes/Country.class.php");
 include("classes/UserTag.class.php");
 include("classes/JivoChat.class.php");
 include("classes/EmailLog.class.php");

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
		include(BP . rtrim(trim($script), '.php') . '.php');
	}
}

class MediaSection
{
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