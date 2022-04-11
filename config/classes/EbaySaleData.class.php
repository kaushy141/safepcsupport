<?php
class EbaySaleData extends DB{
	public $id;
	public $key_id	=	"ebay_id";
	public $table_name	=	"app_ebay_sale_data";
	public $status	=	"ebayStatus";
		
	function __construct($id=0){		
		$this->id	=	$id;
	}
	
						
	function getDetails()
	{
		$sql="SELECT `ebay_id`, `SalesRecordNumber`, `UserId`, `BuyerFullname`, `BuyerPhoneNumber`, `BuyerEmail`, `BuyerAddress1`, `BuyerAddress2`, `BuyerTownCity`, `BuyerCounty`, `BuyerPostcode`, `BuyerCountry`, `ItemNumber`, `ItemTitle`, `CustomLabel`, `Quantity`, `IncludedVATRate`, `Insurance`, `Cashondeliveryfee`, `PaymentMethod`, `SaleDate`, `CheckoutDate`, `PaidonDate`, `DispatchDate`, `Invoicedate`, `Invoicenumber`, `Feedbackleft`, `Feedbackreceived`, `Notestoyourself`, `PayPalTransactionID`, `PostageService`, `Cashondeliveryoption`, `TransactionID`, `OrderID`, `VariationDetails`, `ebayStatus` FROM `".$this->table_name."` WHERE  `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
	
	function isExists($SalesRecordNumber, $BuyerFullname)
	{
		$sql="SELECT `ebay_id` FROM `".$this->table_name."` WHERE `SalesRecordNumber` = '$SalesRecordNumber' AND `BuyerFullname` = '$BuyerFullname'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return true;
		else
			return false;
	}
	
		
	function add($records)
	{
		$sql="INSERT INTO `app_ebay_sale_data`(`SalesRecordNumber`, `UserId`, `BuyerFullname`, `BuyerPhoneNumber`, `BuyerEmail`, `BuyerAddress1`, `BuyerAddress2`, `BuyerTownCity`, `BuyerCounty`, `BuyerPostcode`, `BuyerCountry`, `ItemNumber`, `ItemTitle`, `CustomLabel`, `Quantity`, `IncludedVATRate`, `Insurance`, `Cashondeliveryfee`, `PaymentMethod`, `SaleDate`, `CheckoutDate`, `PaidonDate`, `DispatchDate`, `Invoicedate`, `Invoicenumber`, `Feedbackleft`, `Feedbackreceived`, `Notestoyourself`, `PayPalTransactionID`, `PostageService`, `Cashondeliveryoption`, `TransactionID`, `OrderID`, `VariationDetails`, `ebayStatus`) VALUES ('".implode("','", $records)."')";		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_ebay_sale_data`" => array(
															"column"=> array("`SalesRecordNumber`", "`BuyerFullname`", "`BuyerPhoneNumber`", "`BuyerEmail`", "CONCAT(a.`BuyerAddress1`, ' ', a.`BuyerAddress2`, ' ', a.`BuyerTownCity`, ' ', a.`BuyerCounty`, ' ', a.`BuyerPostcode`, ' ', a.`BuyerCountry`) as buyerFullAddress", "`ItemTitle`", "`PaymentMethod`", "`PostageService`", "`TransactionID`"),
															"reference" => "a",
															"join" => NULL
															),								
								),
					"ORDER"	=>array("a.`SalesRecordNumber`", "a.`BuyerFullname`", "a.`BuyerPhoneNumber`", "a.`BuyerEmail`", "CONCAT(a.`BuyerAddress1`, ' ', a.`BuyerAddress2`, ' ', a.`BuyerTownCity`, ' ', a.`BuyerCounty`, ' ', a.`BuyerPostcode`, ' ', a.`BuyerCountry`)", "a.`ItemTitle`", "a.`PaymentMethod`", "a.`PostageService`", "a.`TransactionID`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->condition = array(
																		
								);		
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		$num_rows_total= $this->Count();	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(								
								viewText($row["SalesRecordNumber"]),
								viewText($row["BuyerFullname"]),
								viewText($row["BuyerEmail"].$row["BuyerPhoneNumber"]),
								viewText($row["buyerFullAddress"]),
								viewText($row["ItemTitle"]),
								viewText($row["PaymentMethod"]),
								viewText($row["PostageService"]),
								number_format(doubleval($row["TransactionID"]),0,'','')								
			);
		}
		return json_encode($output);		
	}
	
	public function getDropdownList($fieldName, $keyword, $extra, $condition)
	{
		if($condition != "")
		$condition = "AND $condition";
		$sql="SELECT `ebay_id`, `SalesRecordNumber` as data_id, `SalesRecordNumber` as value, CONCAT(`SalesRecordNumber`, ' - ', `BuyerFullname`, ' - ', `BuyerTownCity`, `BuyerCounty`,' - ', `BuyerPostcode`,' - ', `BuyerCountry`) as itemlabel FROM `".$this->table_name."` WHERE (LOWER(`SalesRecordNumber`) LIKE '$keyword%' OR LOWER(`BuyerFullname`) LIKE '$keyword%') $condition ORDER BY LOWER(`SalesRecordNumber`) LIKE '$keyword', LOWER(`SalesRecordNumber`) LIKE '$keyword%', LOWER(`SalesRecordNumber`) LIKE '%$keyword%', itemlabel LIMIT 5";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($result)
		if($dbc->db_num_rows()>0)
		{	
			while($row = $dbc->db_fetch_assoc())	
				$data[]	= array("label"=>$row['itemlabel'], "data-id"=>$row['data_id'], "value"=>$row['value'], "extra"=>$row);
		}
		return $data;
	}
	
	
	function getReportSql($format = NULL)
	{
		global $app;
		$image_path = $app->sitePath();
		$barcode_file = $app->basePath("qrcode.php?data=");
		if($format == NULL)
		return "SELECT `ebay_id`, `SalesRecordNumber`, `UserId`, `BuyerFullname`, `BuyerPhoneNumber`, `BuyerEmail`, `BuyerAddress1`, `BuyerAddress2`, `BuyerTownCity`, `BuyerCounty`, `BuyerPostcode`, `BuyerCountry`, `ItemNumber`, `ItemTitle`, `CustomLabel`, `Quantity`, `IncludedVATRate`, `Insurance`, `Cashondeliveryfee`, `PaymentMethod`, `SaleDate`, `CheckoutDate`, `PaidonDate`, `DispatchDate`, `Invoicedate`, `Invoicenumber`, `Feedbackleft`, `Feedbackreceived`, `Notestoyourself`, `PayPalTransactionID`, `PostageService`, `Cashondeliveryoption`, `TransactionID`, `OrderID`, `VariationDetails`, `ebayStatus`, CONCAT(`BuyerAddress1`, ' ', `BuyerAddress2`, ' ', `BuyerTownCity`, ' ', `BuyerCounty`, ' ', `BuyerPostcode`, ' ', `BuyerCountry`) as buyerFullAddress FROM `".$this->table_name."` WHERE 1";
	}

}
?>