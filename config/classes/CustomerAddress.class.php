<?php
class CustomerAddress extends DB{
	public $id;
	public $key_id	=	"customer_address_id";
	public $table_name	=	"app_customer_address";
	public $status	=	"customer_address_status";	
	function __construct($customer_address_id=0){
				
		$this->id	=	$customer_address_id;
	}
		
	function getDetails()
	{
		$sql="SELECT `customer_address_id`, `customer_id`, `customer_address_street_number`, `customer_address_route`, `customer_address_locality`, `customer_address_administrative_area`, `customer_address_country`, `customer_address_postcode`, `customer_address_geo_location`, `customer_address_created_date`, `customer_address_status`, CONCAT(`customer_address_street_number`, ', ', `customer_address_route`, ', ', `customer_address_locality`, ', ', `customer_address_administrative_area`, ', ', `customer_address_country`, ' - ', `customer_address_postcode`) as full_address FROM `app_customer_address` WHERE `customer_address_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	static function getCustomerAddress($customer_id)
	{
		$sql="SELECT * FROM `app_customer_address` WHERE `customer_id`= '$customer_id' ORDER BY `customer_address_created_date`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$recordsArray = array();	
		while($row = $dbc->db_fetch_assoc(true)){					
			$recordsArray[] = $row;					
		}
		return $recordsArray;	
	}	
	
	function getOptions($customer_address_id=NULL)
	{
		$sql="SELECT `customer_address_id`, CONCAT(`customer_address_street_number`, ' ', `customer_address_route` , ' ', `customer_address_locality`, ' ', `customer_address_administrative_area`, ' ', `customer_address_country`, ' ', `customer_address_postcode`) as customer_address_name FROM `app_customer_address` WHERE `customer_address_status`=1 ORDER BY `customer_fname`, `customer_lname`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Customer - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[customer_address_id]\" ".(($customer_address_id==$row['customer_address_id'])?"selected":"")." >".$row['customer_address_name']."</option>";					
		}
		return $html;	
	}
	
	function add($customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status)
	{
		$sql="INSERT INTO `app_customer_address`(`customer_id`, `customer_address_street_number`, `customer_address_route`, `customer_address_locality`, `customer_address_administrative_area`, `customer_address_country`, `customer_address_postcode`, `customer_address_geo_location`, `customer_address_created_date`, `customer_address_status`) VALUES ('$customer_id', '$customer_address_street_number', '$customer_address_route', '$customer_address_locality', '$customer_address_administrative_area', '$customer_address_country', '$customer_address_postcode', '$customer_address_geo_location', NOW(), '$customer_address_status')";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function isCustomerAddressExists($customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country)
	{
		$sql="SELECT `customer_address_id` FROM `app_customer_address` WHERE `customer_id` = '$customer_id' AND LOWER(`customer_address_street_number`)=LOWER('$customer_address_street_number')  AND LOWER(`customer_address_route`)=LOWER('$customer_address_route')  AND LOWER(`customer_address_locality`)=LOWER('$customer_address_locality')  AND LOWER(`customer_address_administrative_area`)=LOWER('$customer_address_administrative_area')  AND LOWER(`customer_address_country`)=LOWER('$customer_address_country')";	
		//echo $sql;die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$data = $dbc->db_fetch_assoc();
			return $data['customer_address_id'];
		}
		else
			return 0;
	}
	
	static function getAddressGeoTracking(){
		$sql="SELECT `customer_address_geo_location` FROM `app_customer_address` WHERE `customer_address_geo_location` != ''";	
		$dbc 	= 	new DB();
		$locations = "";
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			while($row = $dbc->db_fetch_assoc(true)){
				$locArr = explode(",", $row['customer_address_geo_location']);
				$locations.= "{lat:".ltrim($locArr[0],"(").", lng:".rtrim($locArr[1], ")")."},";				
			}
		}
		return $locations;
	}
	
}
?>