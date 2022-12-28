<?php
class DOC{
	public static function getLink($name)
	{
		global $app;
		return $app->basePath("report/".$name);
	}	
	public static function HWCN($id) //Hazard waste Collection Report
	{
		return self::getLink("collection-hwcn/$id");
	}
	public static function WCNN($id) //Wastage Consignment Report
	{
		return self::getLink("collection-wcnn/$id");
	}
	public static function DOCN($id) //Duty of Care: Waste Transfer
	{
		return self::getLink("collection-docn/$id");
	}
	public static function CERT($id) //Collection Certificate
	{
		return self::getLink("collection-cert/$id");
	}
	public static function CINV($id)
	{
		return self::getLink("complaint-invoice/$id");
	}
	public static function CDTL($id, $multi=NULL)
	{
		if($multi==NULL)
		return self::getLink("complaint-detail/$id");
		else
		return self::getLink("complaint-detail/$id/$multi");
	}
	public static function CPRT($id)
	{
		return self::getLink("complaint-print/$id");
	}
	public static function CPLIST()
	{
		return self::getLink("complaint-list");
	}
	public static function EMPLIST()
	{
		return self::getLink("employee-list");
	}
	public static function CUSTLIST()
	{
		return self::getLink("customer-list");
	}
	public static function CARRIERLIST()
	{
		return self::getLink("carrier-list");
	}
	public static function COLLECTIONLIST()
	{
		return self::getLink("collection-list");
	}
	public static function SALESINV($id)
	{
		return self::getLink("sales-invoice/$id");
	}
	public static function SALESINVCOMMERCIAL($id)
	{
		return self::getLink("sales-invoice-commercial/$id");
	}
	public static function SALESPACKSLIP($id)
	{
		return self::getLink("sales-package-slip/$id");
	}
	public static function SALESINVMEMO($id)
	{
		return self::getLink("sales-invoice-memo/$id");
	}
	public static function SALESINVLIST()
	{
		return self::getLink("sales-invoice-list");
	}
	public static function PROSLIP($id)
	{
		return self::getLink("pro-slip/$id");
	}
	public static function PROSLIPSINGLE($id)
	{
		return self::getLink("pro-slip-single/$id");
	}
	public static function CBAR($id) //Collection Certificate
	{
		return self::getLink("collection-audit-report/$id");
	}
	public static function CBARBASIC($id) //Collection Certificate
	{
		return self::getLink("collection-basic-audit-report/$id");
	}
	public static function CASSETCODE($id, $code=0) //Collection Certificate
	{
		return self::getLink("printassetcode/$id/$code");
	}
	public static function PALLETLIST() //Collection Certificate
	{
		return self::getLink("pallet-list");
	}
	public static function PAYSLIP($id) //Collection Certificate
	{
		return self::getLink("pay-slip/$id");
	}
	public static function APPOINTMNET($id) //Collection Certificate
	{
		return self::getLink("appointment/$id");
	}
	public static function SOFTASSETS($id) //Collection Certificate
	{
		return self::getLink("printsoftassets/$id");
	}
	public static function PHYCASSETS($id) //Collection Certificate
	{
		return self::getLink("printphycassets/$id");
	}
	public static function CONTRACTINSURANCE($id) //Collection Certificate
	{
		return self::getLink("printcontractinsurance/$id");
	}
	public static function UTILITIES($id) //Collection Certificate
	{
		return self::getLink("printutilities/$id");
	}
	public static function EQUIPMENTDISPOSAL($id) //Collection Certificate
	{
		return self::getLink("printequipmentdisposal/$id");
	}
	public static function RFCDOC($id) //Collection Certificate
	{
		return self::getLink("printrfc/$id");
	}
	public static function COMPANY($id) //Collection Certificate
	{
		return self::getLink("printcompany/$id");
	}
	public static function SUPPLIER($id) //Collection Certificate
	{
		return self::getLink("printsupplier/$id");
	}
	public static function SUPPLIERLIST() //Collection Certificate
	{
		return self::getLink("printsupplierlist");
	}
	public static function PALLETITEMLIST($id) //Collection Certificate
	{
		return self::getLink("exportpallet/$id");
	}
	public static function PALLETDETAILSHEET($id) //Collection Certificate
	{
		return self::getLink("exportpalletdetail/$id");
	}
	public static function WEBORDERLIST(){
	return self::getLink("weborderlist");
	}
	
	public static function EMPCONTRACTLIST(){
	return self::getLink("empcontractlist");
	}
	
	public static function EMPCONTRACT($id){
	return self::getLink("empcontract/$id");
	}
	
	
	public static function EMPTRAININGLIST(){
	return self::getLink("emptraininglist");
	}
	
	public static function EMPTRAINING($id){
	return self::getLink("emptraining/$id");
	}
	
	public static function EMPLEAVELIST(){
	return self::getLink("empleavelist");
	}	
	
	public static function EMPINDUCTIONLIST(){
	return self::getLink("emplinductionlist");
	}
	
	public static function EMPINDUCTION($id){
	return self::getLink("empinduction/$id");
	}	
	
	public static function EMPLEAVERLIST(){
	return self::getLink("empleaverlist");
	}
	
	public static function EMPLEAVER($id){
	return self::getLink("empleaver/$id");
	}
	
	public static function BATCHPROCODE($id){
	return self::getLink("batchproductcode/$id");
	}
	
	public static function SHIPMENTLABEL($id){
	return self::getLink("shipmentlabel/$id");
	}
	
	public static function CUSTEXCELLIST(){
	return self::getLink("customerexportexcel");
	}
	
	public static function PALLETCODE($id){
	return self::getLink("palletcodelabel/$id");
	}	
	public static function CREDITNOTE($id){
	return self::getLink("credit-note/$id");
	}
	public static function WEOBORDEREXCEL(){
	return self::getLink("exportordertoexcel");
	}
	public static function LOTEXCEL($id){
	return self::getLink("exportlotoexcel/$id");
	}
	public static function PO($id){
	return self::getLink("purchaseorder/$id");
	}	
	public static function POINVOICE($id){
	return self::getLink("purchaseorderinvoice/$id");
	}
	public static function EMPEXPLETTER($id){
	return self::getLink("empexperienceletter/$id");
	}
	public static function SALESPROFITEXCEL(){
	return self::getLink("salesprofitexcel");
	}
	public static function WOINVOICE($id)
	{
	return self::getLink("weborder-invoice/$id");
	}
	
}

?>