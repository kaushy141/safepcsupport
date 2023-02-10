<?php
class Collection extends DB{
	public $id;
	public $key_id	=	"wc_id";
	public $table_name	=	"app_wc_record";
	public $status	=	"wc_status";
	public $customer		=	0;
		
	function __Construct($wc_id=0){				
		$this->id	=	$wc_id;
	}
	
	
	function getDetails()
	{
		$sql="SELECT * FROM `app_wc_record` WHERE `wc_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function load()
	{
		$sql="SELECT a.*, b.`customer_id`, b.`customer_fname`, b.`customer_lname`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_email`, b.`customer_company`, b.`customer_image`, b.`customer_phone`, b.`customer_type_id`, c.`customer_address_id`, c.`customer_address_street_number`, c.`customer_address_route`, c.`customer_address_locality`, c.`customer_address_administrative_area`, c.`customer_address_country`, c.`customer_address_postcode`, c.`customer_address_geo_location`, CONCAT(c.`customer_address_street_number`,  ', ', c.`customer_address_route`, ', ', c.`customer_address_locality`, ', ', c.`customer_address_administrative_area`, ', ', c.`customer_address_country`, ' ', c.`customer_address_postcode`) as customer_full_address, f.`customer_type_name`, g.`carrier_name`, g.`carrier_address`, g.`carrier_email`, g.`carrier_logo_image`, CONCAT(h.`user_fname`, ' ', h.`user_lname`) as collection_manager, h.`user_fname` as manager_fname, h.user_image as collection_manager_image, h.user_email as collection_manager_email, CONCAT(j.`user_fname`, ' ', j.`user_lname`) as driver_name, j.`user_fname` as driver_fname, j.user_image as driver_image, j.user_email as driver_email, i.`vehicle_registration_number`
		FROM `app_wc_record` as a 
		LEFT JOIN `app_customer` as b ON  a.`wc_customer_id`= b.`customer_id`
		LEFT JOIN `app_customer_address` as c ON a.`wc_customer_address_id`= c.`customer_address_id`
		LEFT JOIN `app_wc_record_items` as d ON a.`wc_id` = d.`wc_id`
		LEFT JOIN `app_customer_type` as f ON b.`customer_type_id`= f.`customer_type_id`
		LEFT JOIN `app_wc_carrier` as g ON a.`wc_carrier_id`= g.`carrier_id` 
		LEFT JOIN `app_system_user` as h ON a.`wc_manager_id`= h.`user_id`
		LEFT JOIN `app_system_user` as j ON a.`wc_driver_id`= j.`user_id`
		LEFT JOIN `app_wc_carrier_vehicle` as i ON a.`wc_vehicle_id`= i.`vehicle_id`
		WHERE a.`wc_id` = '".intval($this->id)."'  GROUP BY a.`wc_id` ORDER BY d.`wcr_id`";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()==1)
		{
			$row = $dbc->db_fetch_assoc(true);
			$wcrItem = new WcrItem(0);
			$row['collection_item_list_array'] = $wcrItem->getAllItemDetailsArray($this->id);
			return $row;
		}
		else
			return false;
	}	
	
	function add($wc_code, $wc_customer_id, $wc_customer_address_id, $wc_manager_id, $wc_driver_id, $wc_carrier_id, $wc_vehicle_id, $wc_status_id, $wc_due_date, $wc_loading_time, $wc_help_member, $wc_is_local_authority, $wc_mail_to_customer, $wc_mail_to_collector, $wc_collection_report, $wc_transfer_note, $wc_consignment_note, $wc_consignment_note_code, $wc_on_behalf_of_user,  $wc_created_by, $wc_ip_address, $wc_status, $wc_is_drop_off, $wc_drop_off_driver, $wc_drop_off_vehicle){
	
		$sql= "INSERT INTO `app_wc_record`(`wc_code`, `wc_customer_id`, `wc_customer_address_id`, `wc_manager_id`, `wc_driver_id`, `wc_carrier_id`, `wc_vehicle_id`, `wc_status_id`, `wc_due_date`, `wc_loading_time`, `wc_help_member`, `wc_is_local_authority`, `wc_mail_to_customer`, `wc_mail_to_collector`, `wc_collection_report`, `wc_transfer_note`, `wc_consignment_note`, `wc_consignment_note_code`, `wc_on_behalf_of_user`, `wc_created_date`, `wc_created_by`, `wc_ip_address`, `wc_status`, `wc_is_drop_off`, `wc_drop_off_driver`, `wc_drop_off_vehicle`) VALUES ('$wc_code', '$wc_customer_id', '$wc_customer_address_id', '$wc_manager_id', '$wc_driver_id', '$wc_carrier_id', '$wc_vehicle_id', '$wc_status_id', '$wc_due_date', '$wc_loading_time', '$wc_help_member', '$wc_is_local_authority', '$wc_mail_to_customer', '$wc_mail_to_collector', '$wc_collection_report', '$wc_transfer_note', '$wc_consignment_note', '$wc_consignment_note_code', '$wc_on_behalf_of_user', NOW(), '$wc_created_by', '$wc_ip_address', '$wc_status', '$wc_is_drop_off', '$wc_drop_off_driver', '$wc_drop_off_vehicle')";	
		//echo $sql;die;	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();
		return $this->id ;
	}
	
	function getWcCode($date = NULL)
	{
		if($date == NULL || $date == ""){
			$time = time();
			$sql = "SELECT `wc_code`, CURRENT_DATE as currentdate FROM `app_wc_record` WHERE YEAR(`wc_created_date`) = YEAR(NOW()) AND MONTH(`wc_created_date`) = MONTH(NOW()) ORDER BY `wc_id` DESC LIMIT 1 ";
		}
		else{
			$time = strtotime($date);
			$sql = "SELECT `wc_code`, '$date' as currentdate FROM `app_wc_record` WHERE YEAR(`wc_created_date`) = YEAR('$date') AND MONTH(`wc_created_date`) = MONTH('$date') ORDER BY `wc_id` DESC LIMIT 1 ";
		}
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows())
		$data = $dbc->db_fetch_assoc();
		else
		{
			$data['wc_code'] = date("ym", $time )."W"."000";
			$data['currentdate'] = date("Y-m-d", $time);					
		}
		$numcount = intval(substr($data['wc_code'],5,3))+1;
		return strtoupper(date("ym",strtotime($data['currentdate']))."W".sprintf("%03d",$numcount));
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $wc_is_drop_off){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_wc_record`" => array(
															"column"=> array("`wc_id`", "`wc_code`", "`wc_due_date`", "`wc_created_date`","`wc_is_certificate_sended`", "wc_completion_date", "wc_status"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_customer`" => array(
															"column"=> array("`customer_fname`", "`customer_lname`", "CONCAT(`customer_fname`, ' ', `customer_lname`) as customer_name", "`customer_id`", "`customer_email`", "`customer_phone`", "`customer_is_mobile_verified`", "`customer_is_email_verified`", "`customer_company`"),
															"reference" => "b",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_wc_record`", "on"=>array("`customer_id`" => "`wc_customer_id`"))
															),
								"`app_customer_address`" => array(
															"column"=> array("`customer_address_id`", "`customer_address_street_number`", "`customer_address_route`", "`customer_address_locality`", "`customer_address_administrative_area`", "`customer_address_country`", "`customer_address_postcode`", "`customer_address_geo_location`"),
															"reference" => "c",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_wc_record`", "on"=>array("`customer_address_id`" => "`wc_customer_address_id`"))
															),
								"`app_wc_record_items`" => array(
															"column"=> array("COUNT(d.`wcr_id`) as total_collection_items_type", "SUM(d.`wcr_item_qty`) as total_collection_items"),
															"reference" => "d",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_wc_record`", "on"=>array("`wc_id`" => "`wc_id`"))
															),
								"`app_system_user`" => array(
															"column"=> array("CONCAT(e.`user_fname`, ' ', e.`user_lname`) as collection_manager"),
															"reference" => "e",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_wc_record`", "on"=>array("`user_id`" => "`wc_manager_id`"))
															),
								"`app_wc_status`" => array(
															"column"=> array("`wc_status_name`", "`wc_status_color_code`"),
															"reference" => "f",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_wc_record`", "on"=>array("`wc_status_id`" => "`wc_status_id`"))
															),
								"`app_wc_carrier`" => array(
															"column"=> array("`carrier_name`", "`carrier_logo_image`"),
															"reference" => "g",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_wc_record`", "on"=>array("`carrier_id`" => "`wc_carrier_id`"))
															),
								),
					"ORDER"	=>array("wc_due_date","`wc_code`", "CONCAT(`customer_fname`,  `customer_fname`)", "CONCAT(`customer_address_street_number`", "`customer_address_route`", "`customer_address_locality`", "`customer_address_administrative_area`)", "`carrier_name`", "CONCAT(e.`user_fname`, ' ', e.`user_lname`)", "`wc_status_name`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		
		if($this->customer!=0){
			$conditionArray[] = array(
				array("b.`customer_id`","=",$this->customer),
				array("a.`wc_is_drop_off`","=",$wc_is_drop_off)									
			);;
		}
		else{
			$conditionArray[] = array(
				array("a.`wc_is_drop_off`","=",$wc_is_drop_off)									
			);
		}			
		if(isCustomer()){
			$conditionArray[] = array("`wc_customer_id`", "=", getLoginId());	
		}
		$this->condition = $conditionArray;
								
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`wc_id`");
			
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		//$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		//$num_rows_total= $dbcTotal->db_num_rows($resultTotal);
		$num_rows_total = $this->getTotalCount();
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(								
								dateView($row["wc_due_date"],"MONTH"),
								$row["wc_code"]."<br/><span class=\"badge badge-info\" data-toggle=\"popover-ajax\" data-popover-action=\"collection\" data-popover-id=\"{$row['wc_id']}\">{$row['total_collection_items']} in {$row['total_collection_items_type']} type</span>",
								viewText($row["customer_name"]),
								$row["customer_address_street_number"]. " " .$row["customer_address_route"]. " " .$row["customer_address_locality"]. " " .$row["customer_address_administrative_area"]. " " .$row["customer_address_country"]. " - " .$row["customer_address_postcode"],
								$row["customer_company"],
								$row["collection_manager"],
								$row["wc_status_name"],
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[wc_id]|W', '$row[wc_code] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>".(isAdmin()?
	
    ("<a class=\"dropdown-item redirect\" href=\"".$app->siteUrl("updatecollection/".$row['wc_id'])."\"><i class=\"fa fa-edit fa-fw\"></i> Update</a>	
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::HWCN($row['wc_id'])."');\"><i class=\"fa fa-bookmark-o fa-fw\"></i> Hazard waste Collection</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::WCNN($row['wc_id'])."')\"><i class=\"fa fa-print fa-fw\"></i> Waste Consignment</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::DOCN($row['wc_id'])."')\"><i class=\"fa fa-file-o fa-fw\"></i> Duty of Care</a>	
    <a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::CERT($row['wc_id'])."')\"><i class=\"fa fa-certificate fa-fw\"></i> Certificate ".($row['wc_is_certificate_sended']==1?" &nbsp; &nbsp; <i class=\"fa fa-check-square-o fa-fw text-success\"></i>(Send)":"")."</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::CBAR($row['wc_id'])."')\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Advance Audit Report</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::CBARBASIC($row['wc_id'])."')\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Basic Audit Report</a>
	<a class=\"dropdown-item\" href=\"javascript:requestFeedback('".$row['wc_id']."', 'W', '".$row['customer_id']."')\"><i class=\"fa fa-comments-o fa-fw\"></i> Request Feedback</a>"):($row['wc_status'] == 1 && strtotime($row['wc_completion_date']) < strtotime(" -3 day") ? 
	("<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::HWCN($row['wc_id'])."');\"><i class=\"fa fa-bookmark-o fa-fw\"></i> Hazard waste Collection</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::WCNN($row['wc_id'])."')\"><i class=\"fa fa-print fa-fw\"></i> Waste Consignment</a>
	<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::DOCN($row['wc_id'])."')\"><i class=\"fa fa-file-o fa-fw\"></i> Duty of Care</a>	
    <a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::CERT($row['wc_id'])."')\"><i class=\"fa fa-certificate fa-fw\"></i> Certificate </a>") 
	:""))." </div></div>",
								$_SESSION['app_theme'] != 'dark' ? $row['wc_status_color_code'] : null,
								"updatecollection/".$row['wc_id']

			);
		}
		/*<a class=\"dropdown-item redirect\" href=\"".$app->siteUrl("managecollection/".$row['wc_id'])."\"><i class=\"fa fa-bookmark-o fa-fw\"></i> Validate Collection</a>
	<a class=\"dropdown-item redirect\" href=\"".$app->siteUrl("viewcollectionmedia/".$row['wc_id'])."\"><i class=\"fa fa-bookmark-o fa-fw\"></i> Veiw Media files</a>
	<a class=\"dropdown-item redirect\" href=\"".$app->siteUrl("collectionroute/".$row['wc_id'])."\"><i class=\"fa fa-map-marker fa-fw\"></i> Veiw Route</a>*/
		return json_encode($output);		
	}
	
	function getListView($offset=0, $searchkeyword = "")
	{
		global $app;
		$condition = "";
		if($searchkeyword!="")
		{
			$searchkeyword = trim(strtolower($searchkeyword));
			$condition = " AND (LOWER(a.`wc_code`) LIKE '%$searchkeyword%' OR a.`wc_created_date` LIKE '%$searchkeyword%' OR a.`wc_due_date` LIKE '%$searchkeyword%' OR LOWER(b.`customer_fname`) LIKE '%$searchkeyword%' OR LOWER(b.`customer_lname`) LIKE '%$searchkeyword%'  OR LOWER(h.`user_fname`) LIKE '%$searchkeyword%' OR LOWER(h.`user_lname`) LIKE '%$searchkeyword%' OR LOWER(c.`wc_status_name`) LIKE '%$searchkeyword%' OR LOWER(CONCAT(b.`customer_fname`, ' ', b.`customer_lname`)) LIKE '%$searchkeyword%' OR LOWER(CONCAT(h.`user_fname`, ' ', h.`user_lname`)) LIKE '%$searchkeyword%') ";
		}
		
		$sql="SELECT a.`wc_id`, a.`wc_code`, a.`wc_created_date`, a.`wc_due_date`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_image`, c.wc_status_name, CONCAT(h.`user_fname`, ' ', h.`user_lname`) as collection_manager
		FROM `app_wc_record` as a 
		LEFT JOIN `app_customer` as b ON  a.`wc_customer_id`= b.`customer_id`		
		LEFT JOIN `app_wc_status` as c ON a.`wc_status_id`= c.`wc_status_id`
		LEFT JOIN `app_system_user` as h ON a.`wc_manager_id`= h.`user_id`
		WHERE 1 $condition GROUP BY a.`wc_id` ORDER BY a.`wc_id` DESC Limit 10 OFFSET $offset";
				
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$collection_item_list_array = array();
			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['customer_image_url']	=	getResizeImage($row['customer_image'], 50);
				$row['wc_created_date']		=	dateView($row['wc_created_date']);
				$row['wc_due_date']		=	"Due Date : " . dateView($row['wc_due_date'], "DATE");
				$row['collection_manager']		=	"Collector : " . $row['collection_manager'];
				$collection_item_list_array[] = $row; 
			}
			return $collection_item_list_array;
		}
		else
			return false;
	}
	
	function getDetailsByCode($wc_code)
	{
		global $app;
		$sql="SELECT a.`wc_id`, a.`wc_code`, a.`wc_customer_id`, a.`wc_customer_address_id`, a.`wc_manager_id`, a.`wc_driver_id` , a.`wc_carrier_id`, a.`wc_vehicle_id`, a.`wc_status_id`, a.`wc_due_date`, a.`wc_loading_time`, a.`wc_help_member`, a.`wc_is_local_authority`, a.`wc_mail_to_customer`, a.`wc_mail_to_collector`, a.`wc_collection_report`, a.`wc_transfer_note`, a.`wc_consignment_note`, a.`wc_consignment_note_code`, a.`wc_on_behalf_of_user`, a.`wc_completion_date`, a.`wc_arrival_time`, a.`wc_departure_time`, a.`wc_member_of_staff_name`, a.`wc_authority_member_of_staff`, a.`wc_transferor_signature`, a.`wc_created_date`, a.`wc_created_by`, a.`wc_ip_address`, a.`wc_status`, b.`customer_id`, b.`customer_fname`, b.`customer_lname`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_email`, b.`customer_phone`, b.`customer_type_id`, b.`customer_image`, c.`customer_address_id`, c.`customer_address_street_number`, c.`customer_address_route`, c.`customer_address_locality`, c.`customer_address_administrative_area`, c.`customer_address_country`, c.`customer_address_postcode`, c.`customer_address_geo_location`, CONCAT(c.`customer_address_street_number`,  ', ', c.`customer_address_route`, ', ', c.`customer_address_locality`, ', ', c.`customer_address_administrative_area`, ', ', c.`customer_address_country`, ' ', c.`customer_address_postcode`) as customer_full_address, GROUP_CONCAT(d.`wcr_item_id`, '-', d.`wcr_item_qty`, ':', d.`wcr_item_weight` ) as collection_item_list, f.`customer_type_name`, g.`carrier_name`, g.`carrier_address`, g.`carrier_email`, CONCAT(h.`user_fname`, ' ', h.`user_lname`) as collection_manager, h.`user_image` as collection_manager_image, h.user_email as collection_manager_email, h.user_phone as collection_manager_phone, h.user_address as collection_manager_address, i.wc_status_name, j.vehicle_registration_number
		FROM `app_wc_record` as a 
		LEFT JOIN `app_customer` as b ON  a.`wc_customer_id`= b.`customer_id`
		LEFT JOIN `app_customer_address` as c ON a.`wc_customer_address_id`= c.`customer_address_id`
		LEFT JOIN `app_wc_record_items` as d ON a.`wc_id` = d.`wc_id`
		LEFT JOIN `app_customer_type` as f ON b.`customer_type_id`= f.`customer_type_id`
		LEFT JOIN `app_wc_carrier` as g ON a.`wc_carrier_id`= g.`carrier_id`
		LEFT JOIN `app_system_user` as h ON a.`wc_manager_id`= h.`user_id`
		LEFT JOIN `app_wc_status` as i ON a.`wc_status_id`= i.`wc_status_id`
		LEFT JOIN `app_wc_carrier_vehicle` as j ON a.`wc_vehicle_id`= j.`vehicle_id` WHERE a.`wc_code` = '".$wc_code."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			$row =  $dbc->db_fetch_assoc(true);
			$row['customer_image']	=	getResizeImage($row['customer_image'], 50);
			$row['collection_manager_image']	=	getResizeImage($row['collection_manager_image'], 50);
			$row['wc_transferor_signature']	=	$app->basePath($row['wc_transferor_signature'], false);
			$row['wc_created_date']		=	dateView($row['wc_created_date']);
			$row['wc_due_date']		=	dateView($row['wc_due_date'], "DATE");
			if($row['collection_manager'] == "")
			{
				$row['collection_manager'] = "Not assigned yet";
				$row['collection_manager_image'] = getResizeImage(DEFAULT_USER_IMAGE, 50);
				$row['collection_manager_email'] = "Not available";
				$row['collection_manager_phone'] = "Not available";
				$row['collection_manager_address'] = "Not available";
			}
			return $row;
		}
		else
			return false;
	}
	
	static function getAndroidCustomerCollection($customer_id)
	{
		$sql = "SELECT a.`wc_id`, a.`wc_code`, a.`wc_status`, b.`wc_status_name`, b.`wc_status_color_code`
		FROM `app_wc_record` as a 
		INNER JOIN `app_wc_status` as b ON a.`wc_status` = b.`wc_status_id`
		WHERE a.`wc_customer_id` = '$customer_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc())
		$data[] = $row;
		return $data;
	}
	
	function getTodayCollectionRoute($date)
	{
		global $app;	
		$sql="SELECT a.wc_id, a.wc_code,  a.wc_status_id, CONCAT(COALESCE(b.`customer_fname`, 'not Available'), ' ', COALESCE(b.`customer_lname`)) as customer_name, b.`customer_email`, b.`customer_image`, b.`customer_phone`, c.`customer_address_street_number`, c.`customer_address_route`, c.`customer_address_locality`, c.`customer_address_administrative_area`, c.`customer_address_country`, c.`customer_address_postcode`, c.`customer_address_geo_location`, CONCAT(c.`customer_address_street_number`,  ', ', c.`customer_address_route`, ', ', c.`customer_address_locality`, ', ', c.`customer_address_administrative_area`, ', ', c.`customer_address_country`, ' ', c.`customer_address_postcode`) as customer_full_address, c.`customer_address_postcode`,  g.`carrier_name`, g.`carrier_address`, g.`carrier_email`, g.`carrier_logo_image`, CONCAT(COALESCE(h.`user_fname`, 'Not available'), ' ', COALESCE(h.`user_lname`)) as collection_manager, h.user_image as collection_manager_image, h.user_email as collection_manager_email,  i.wc_status_name, i.wc_status_color_code, j.user_image as driver_image, CONCAT(COALESCE(j.`user_fname`, 'Not assigned'), ' ', COALESCE(j.`user_lname`)) as driver_name 
		FROM `app_wc_record` as a 
		LEFT JOIN `app_customer` as b ON  a.`wc_customer_id`= b.`customer_id`
		INNER JOIN `app_customer_address` as c ON a.`wc_customer_address_id`= c.`customer_address_id` 		
		LEFT JOIN `app_wc_carrier` as g ON a.`wc_carrier_id`= g.`carrier_id`
		LEFT JOIN `app_system_user` as h ON a.`wc_manager_id`= h.`user_id`				
		LEFT JOIN `app_wc_status` as i ON a.`wc_status_id`= i.`wc_status_id`
		LEFT JOIN `app_system_user` as j ON a.`wc_driver_id`= j.`user_id`
		WHERE DATE_FORMAT(a.`wc_due_date`, '%Y-%m-%d') = '".$date."' AND a.`wc_is_drop_off` = '0' AND a.`wc_status_id` != '".COLLECTION_STATUS_CANCEL."'";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$collection_array = array();
		if($dbc->db_num_rows($result)>0)
		{
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['customer_image']	=	getResizeImage($row['customer_image'], 50);
				//$row['driver_image']	=	getResizeImage($row['driver_image'], 50);
				$row['collection_manager_image']	=	$app->basePath($row['collection_manager_image']);
				$collection_array[] = $row;
			}
			return $collection_array;
		}
		else
			return false;
	
	}
	
	function getCollectionManager($wc_manager_id){
		global $app;
		$sql = "SELECT CONCAT(`user_fname`, ' ', `user_lname`) as manager_name, `user_image`, `user_signature` FROM `app_system_user` WHERE (`user_id` = '$wc_manager_id' AND `user_signature` !='') OR `user_is_collection_mgr` = 1 ORDER BY `user_is_collection_mgr` LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$collection_array = array();
		if($dbc->db_num_rows($result)>0)
		{
			$row = $dbc->db_fetch_assoc(true);
			$row['manager_image']	=	getResizeImage($row['user_image'], 50);
			$row['manager_signature']	=	$app->sitePath($row['user_signature'], false);
			return $row;
		}
		else
			return false;
	}
	
	function getInventoryChartTable(){
		$WcrItem = new WcrItem(0);
		$list = $WcrItem->getComplaintItemList($this->id);
		$html="<table width='100%' cellspacing='0px' cellpadding='0px' style='font-size:13px; border:1px solid #999;'>";
		if(!empty($list) && count($list)>0)
		{
			$html .= "<tr style='background:#eaeaea;'>";
			$html .= "<th align='left' width='25%' style='padding:5px; border-right:1px solid #999;'>Item Name</th>";
			$html .= "<th align='left' width='25%' style='padding:5px; border-right:1px solid #999;'>Type</th>";
			$html .= "<th align='center' width='20%' style='padding:5px; border-right:1px solid #999;'>EWC Code</th>";
			$html .= "<th align='center' width='10%' style='padding:5px; border-right:1px solid #999;'>Qty</th>";
			$html .= "<th align='center' width='20%' style='padding:5px;'>Charge</th>";
			$html .= "</tr>";
			foreach($list as $item){
				$html .= "<tr style='border-bottom:1px solid #999;'>";
				$html .= "<td align='left' style='padding:5px; border-right:1px solid #999; border-top:1px solid #999;'>".$item['wci_name']."</td>";
				$html .= "<td align='left' style='padding:5px; border-right:1px solid #999; border-top:1px solid #999;'>".$item['wci_type_name']."</td>";
				$html .= "<td align='center' style='padding:5px; border-right:1px solid #999; border-top:1px solid #999;' style='padding:5px;'>".$item['wci_ewc_code']."</td>";
				$html .= "<td align='center' style='padding:5px; border-right:1px solid #999; border-top:1px solid #999;'>".(intval($item['wcr_item_qty'])==0 ? "N/A":intval($item['wcr_item_qty']))."</td>";
				$html .= "<td align='center' style='padding:5px; border-top:1px solid #999;'>".(floatval($item['wcr_item_charge_amount'])>0 ? ("&pound; ".(floatval($item['wcr_item_charge_amount'])*intval($item['wcr_item_qty']))." ".$item['wcr_item_charge_format']):"<b style='color:#4CAF50;'>Free</b>")."</td>";
				$html .= "</tr>";
			}
		}
		else
			$html.="<tr><td align='center'>No item listed yet. Items will be listed at collection time on site.</td></tr>";
		$html.="</table>";
		return $html;
	}
	
	function getReportSqlBasicAuditReport(){
		return "SELECT a.`wcr_item_qty`, b.`wci_name`, 'Data Destroyed' as status FROM `app_wc_record_items` as a INNER JOIN `app_wc_item_master` as b ON a.`wcr_item_id` = b.`wci_id` WHERE a.`wc_id` = '".$this->id."'";
	}
	
	function getReportcollecionListSql(){
		return "SELECT a.`wc_code`, DATE_FORMAT(a.`wc_due_date`, '%d-%b-%Y') AS wc_due_date, DATE_FORMAT(a.`wc_created_date`, '%d-%b-%Y %h:%i%p') AS wc_created_date, DATE_FORMAT(a.`wc_completion_date`, '%d-%b-%Y') AS wc_completion_date, CONCAT(`customer_fname`, ' ', `customer_lname`) as customer_name, b.`customer_email`, b.`customer_phone`, f.`wc_status_name`, CASE WHEN a.`wc_is_drop_off` = '0' THEN 'Collection' WHEN a.`wc_is_drop_off` = '1' THEN 'Drop Off' ELSE 'Pickup' END as record_type FROM `app_wc_record` AS a LEFT JOIN `app_customer` AS b ON ( b.`customer_id` = a.`wc_customer_id` ) LEFT JOIN `app_wc_status` AS f ON ( f.`wc_status_id` = a.`wc_status_id` ) WHERE 1 ORDER BY a.`wc_id` ";	
	}
	
	function getCollectionItems(){
		global $app;
		$sql = "SELECT a.`wcr_item_id`, a.`wcr_item_qty`, b.`wci_name`, 'Data Destroyed' as status FROM `app_wc_record_items` as a INNER JOIN `app_wc_item_master` as b ON a.`wcr_item_id` = b.`wci_id` WHERE a.`wc_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$collection_array = array();
		if($dbc->db_num_rows($result)>0)
		{
			while($row = $dbc->db_fetch_assoc(true))
			$collection_array[] = $row;
		}
		return $collection_array;
	}
	
}
	
?>