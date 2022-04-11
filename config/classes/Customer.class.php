<?php
class Customer extends DB{
	public $id;		
	public $key_id	=	"customer_id";
	public $table_name	=	"app_customer";
	public $status	=	"customer_status";
	function __construct($customer_id=0){
				
		$this->id	=	intval($customer_id);
	}
	
	function setLive()
	{
		$sql="UPDATE `".$this->table_name."` SET `customer_is_live` = '1' WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);				
	}
	
	function setOffline()
	{
		$sql="UPDATE `".$this->table_name."` SET `customer_is_live` = '0' WHERE `".$this->key_id."` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);				
	}
	function getIdByEmail($md5Email)
	{
		$sql="SELECT `".$this->key_id."` FROM `".$this->table_name."` WHERE MD5(`customer_email`) = '$md5Email'";
		//echo $sql;	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_fetch_assoc($sql))		
			return $data['customer_id'];
		else
			return false;
	}
	
	function setFCMToken($customer_fcm_token)
	{
		$this->update(
						array(
								"customer_fcm_token"=>$customer_fcm_token
							 )
						);
	}
	
	function getFCMToken()
	{
		$sql="SELECT `customer_fcm_token` FROM `".$this->table_name."` WHERE `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($data = $dbc->db_fetch_assoc(true))		
			return $data['customer_fcm_token'];
		else
			return false;
	}
		
	function getDetails()
	{
		//$hardware_id!=NULL?$this->hardware_id=$hardware_id:"";
		$sql="SELECT a.*, CONCAT(a.`customer_fname`, ' ', a.`customer_lname`) as customer_name, b.`customer_type_name`,   GROUP_CONCAT(CONCAT(`customer_address_street_number`, ' ', `customer_address_route` , ' ', `customer_address_locality`, ' ', `customer_address_administrative_area`, ' ', `customer_address_country`, ' ', `customer_address_postcode`) separator '<br/>') as customer_address_name FROM `app_customer` as a 
 INNER JOIN `app_customer_type` as b ON a.`customer_type_id`=b.`customer_type_id` 
 LEFT JOIN `app_customer_address` as c ON a.`customer_id`=c.`customer_id` 
 WHERE a.`customer_id` = '".$this->id."' GROUP BY a.`customer_id`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
	
	public function getDropdownList($fieldName, $keyword, $extra, $condition)
	{
		if($condition != "")
		$condition = "AND $condition";
		if(isCustomer())
		{
			$condition .= " AND a.`customer_id` = '$_SESSION[customer_id]' ";
		}
		
		$sql="SELECT DISTINCT a.`customer_id`, a.`customer_fname`, a.`customer_lname`, a.`customer_email`, a.`customer_phone`, a.`customer_company`, a.`customer_tax_number`, a.`customer_type_id`, b.`customer_address_street_number`, b.`customer_address_route`, b.`customer_address_locality`, b.`customer_address_administrative_area`, b.`customer_address_country`, b.`customer_address_postcode`, b.`customer_address_geo_location`,  CONCAT(a.`customer_fname`, ' ',  a.`customer_lname` ,' > ', a.customer_email, ' #[', b.`customer_address_street_number`, ', ', b.`customer_address_route` , ', ', b.`customer_address_locality`, ', ', b.`customer_address_administrative_area`, ', ', b.`customer_address_country`, ' - ', b.`customer_address_postcode`, ']') as itemlabel, a.customer_email as value, CONCAT(a.customer_email,'-', b.`customer_address_id`) as data_id FROM `".$this->table_name."` as a INNER JOIN `app_customer_address` as b ON a.`customer_id`=b.`customer_id`  WHERE (LOWER(a.customer_email) LIKE '$keyword%' OR LOWER(a.customer_fname) LIKE '$keyword%' OR LOWER(a.customer_lname) LIKE '$keyword%' OR LOWER(CONCAT(a.`customer_fname`, ' ',  a.`customer_lname`)) LIKE '$keyword%' OR LOWER(b.`customer_address_postcode`) LIKE '%$keyword%') $condition 
		ORDER BY LOWER(a.`customer_fname`) LIKE '$keyword', LOWER(a.`customer_fname`) LIKE '$keyword%', LOWER(a.`customer_fname`) LIKE '%$keyword%', itemlabel LIMIT 10";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		if($result)
		if($dbc->db_num_rows()>0)
		{	$record = null;
			while($row = $dbc->db_fetch_assoc(true)){
				$record = $row;
				$data[]	= array("label"=>$row['itemlabel'], "data-id"=>$row['data_id'], "value"=>$row['value'], "extra"=>$row);
			}
			$record['customer_address_street_number'] = '';
			$record['customer_address_route'] = '';
			$record['customer_address_locality'] = '';
			$record['customer_address_administrative_area'] = '';
			$record['customer_address_country'] = '';
			$record['customer_address_postcode'] = '';
			$record['customer_address_geo_location'] = '';
			$record['itemlabel'] = '';
			array_unshift($data, array("label"=>"Add new Address", "data-id"=>$record['customer_email'].'-0', "value"=>$record['value'], "extra"=>$record));
		}
		return $data;
	}
		
	function getOptions($customer_id=NULL)
	{
		$sql="SELECT `customer_id`, CONCAT(`customer_fname`, ' ', `customer_lname`) as user_name FROM `app_customer` WHERE `customer_status`=1 ORDER BY `customer_fname`, `customer_lname`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="<option value=\"0\"> - Select Customer - </option>";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<option value=\"$row[customer_id]\" ".(($customer_id==$row['customer_id'])?"selected":"")." >".$row['user_name']."</option>";					
		}
		return $html;	
	}
	
	function getCustomerTypeFilter(){
		$sql = "SELECT COUNT(`customer_id`) as record, a.customer_type_id, b.`customer_type_name` FROM `".$this->table_name."` AS a INNER JOIN `app_customer_type` AS b ON a.`customer_type_id` = b.`customer_type_id` GROUP BY a.`customer_type_id` ORDER BY b.`customer_type_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$record_array[] = $row;				
			}
		}
		return $record_array;
	}
	
	function getChatCustomer()
	{
		global $app;
		$sql="SELECT DISTINCT a.`customer_id`, CONCAT(a.`customer_fname`, ' ', a.`customer_lname`) as customer_name, a.`customer_image`, a.`customer_created_date`, a.`customer_is_live`, count(d.`complaint_log_id`) as chat_count, d.`complaint_log_time` FROM `app_customer` as a LEFT JOIN `app_complaint_log` as d ON ( a.`customer_id` = d.`complaint_customer_id` AND d.`is_readed` = '0' AND d.`complaint_customer_id` != 0 ) WHERE a.`customer_status`=1 GROUP BY a.`customer_id` ORDER BY d.`complaint_log_time` DESC, a.`customer_fname`, a.`customer_lname`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$output_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){	
		$row['customer_created_date'] = dateView($row['customer_created_date'], 'NOW');	
		if($row['chat_count']>0)
			$row['customer_address'] = "message - ".dateView($row['complaint_log_time'], 'NOW');	
		else
			$row['customer_address'] = "Created- ".dateView($row['customer_created_date'], 'NOW');	
		$row['customer_image'] = getResizeImage($row['customer_image'],50);			
			$output_array[]=$row;			
		}
		return $output_array;	
	}
	
	
	function login($username, $password)
	{
		if($username && $password)
		{
			$username = md5($username);		
			$password = md5($password);			
			$sql ="SELECT a.`customer_id`, a.`customer_code`, a.`customer_fname`, a.`customer_lname`, CONCAT(a.`customer_fname`, ' ', a.`customer_lname`) as customer_name, a.`customer_email`, a.`customer_phone`, a.`customer_type_id`, a.`customer_image`, b.`customer_type_name`, a.`customer_status`, a.`customer_password`, a.`customer_created_date`, a.`customer_created_by`, a.`customer_is_mobile_verified`, a.`customer_is_email_verified` FROM `app_customer` as a INNER JOIN `app_customer_type` as b ON a.`customer_type_id`=b.`customer_type_id` WHERE  (MD5(LOWER(a.`customer_email`)) = '$username' OR MD5(LOWER(a.`customer_code`)) =  '$username') AND MD5(a.`customer_password`) = '$password'";			
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);
			if($dbc->db_num_rows()==1)
				return $dbc->db_fetch_assoc();
			else
				return false;
		}
	}
	
		
	function isEmailExists($customer_email=NULL, $except_customer_id=NULL)
	{
		$subsql=$except_customer_id!=NULL?" AND `customer_id` != '$except_customer_id'":"";
		
		$sql="SELECT `customer_id` FROM `app_customer` WHERE LOWER(`customer_email`) = LOWER('$customer_email') $subsql ORDER BY `customer_id`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)>0)
		{
			$data = $dbc->db_fetch_assoc();
			return $data['customer_id'];
		}
		else
			return false;	
	}
	
	function add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified)
	{
		$sql="INSERT INTO `app_customer`(`customer_code`, `customer_fname`, `customer_lname`, `customer_email`, `customer_phone`, `customer_company`,  `customer_type_id`, `customer_image`, `customer_status`, `customer_password`, `customer_created_date`, `customer_created_by`, `customer_is_mobile_verified`, `customer_is_email_verified`) VALUES ('$customer_code', '$customer_fname', '$customer_lname', '$customer_email', '$customer_phone', '$customer_company', '$customer_type_id', '$customer_image', '$customer_status', '$customer_password', NOW(), '$customer_created_by', '$customer_is_mobile_verified', '$customer_is_email_verified')";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function load()
	{
		global $app;		
		$sql="SELECT a.`customer_id`, a.`customer_code`, a.`customer_fname`, a.`customer_lname`, CONCAT(a.`customer_fname`, ' ', a.`customer_lname`) as customer_name, a.`customer_email`, a.`customer_phone`, a.`customer_company`, a.`customer_tax_number`, a.`customer_type_id`,  a.`customer_image`, b.`customer_type_name`, a.`customer_status`, a.`customer_password`, a.`customer_created_date`, a.`customer_created_by`, a.`customer_is_mobile_verified`, a.`customer_is_email_verified`, a.`customer_remark`, c.`customer_address_id`,  c.`customer_address_street_number`,  c.`customer_address_route`, c.`customer_address_locality`, c.`customer_address_administrative_area`,c. `customer_address_country`, c.`customer_address_postcode`, c.`customer_address_geo_location` 
		FROM `app_customer` as a 
		INNER JOIN `app_customer_type` as b ON a.`customer_type_id`=b.`customer_type_id`
		LEFT JOIN `app_customer_address` as c ON a.`customer_id`=c.`customer_id` 
		WHERE a.`customer_id` = '".$this->id."'  GROUP BY a.`customer_id` ORDER BY c.`customer_address_status` DESC, c.`customer_address_id` DESC LIMIT 1";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()==1)
			return $dbc->db_fetch_assoc();
		else
			return false;
	}
	
	function getNewCode($customer_email, $name)
	{
		$email_data = explode("@",$customer_email);
		$customer_code = trim(strtolower($email_data[0]));
		$code_finding_flag=true;
		$i=0;
		if($customer_code!="" && $name!="")
		{
			$sql="SELECT `customer_id` FROM `app_customer` WHERE LOWER(`customer_code`) = '$customer_code'";	
			$dbc 	= 	new DB();
			$result	=	$dbc->db_query($sql);		
			if($dbc->db_num_rows()==0)
				return $customer_code;
		}
		return "";
	}
	
	function getRecords()
	{		
		global $app;
		$sql="SELECT a.`customer_id`, a.`customer_code`, a.`customer_fname`, a.`customer_lname`, CONCAT(a.`customer_fname`, ' ', a.`customer_lname`) as customer_name, a.`customer_email`, a.`customer_phone`, a.`customer_type_id`,  a.`customer_image`, b.`customer_type_name`, a.`customer_status`, a.`customer_password`, a.`customer_created_date`, a.`customer_created_by`, a.`customer_is_mobile_verified`, a.`customer_is_email_verified`, GROUP_CONCAT(CONCAT(`customer_address_street_number`, ' ', `customer_address_route` , ' ', `customer_address_locality`, ' ', `customer_address_administrative_area`, ' ', `customer_address_country`, ' ', `customer_address_postcode`) separator '<br/>') as customer_address_name 
		FROM `app_customer` as a 
		INNER JOIN `app_customer_type` as b ON a.`customer_type_id`=b.`customer_type_id`
		INNER JOIN `app_customer_address` as c ON a.`customer_id`=c.`customer_id` 
		WHERE 1 GROUP BY a.`customer_id`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$html="";	
		while($row = $dbc->db_fetch_assoc()){					
			$html.="<tr style=\"".(!$row['customer_status']?"background-color:#ffebee;":""). "\">";
				$html.="<td>".$row['customer_name']."</td>";
				$html.="<td>".$row['customer_email']."</td>";
				$html.="<td>".$row['customer_phone']."</td>";
				$html.="<td>".$row['customer_address_name']."</td>";
				$html.="<td>".$row['customer_type_name']."</td>";
				$html.="<td>".dateView($row['customer_created_date'])."</td>";
				$html.="<td>".statusView($row['customer_id'], $row['customer_status'])."</td>";
				$html.="<td>"."<a data-title=\"Update #".$row['customer_name']." Customer\" href=\"updatecustomer_".$row['customer_id']."\" type=\"button\" class=\"btn btn-secondary redirect\"><i class=\"fa fa-lightbulb-o\"></i>&nbsp; View</a>"."</td>";
			$html.="</tr>";		
		}
		return $html;	
	}
	
	
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_customer`" => array(
															"column"=> array("`customer_id`", "`customer_type_id`", "`customer_code`", "`customer_fname`",  "`customer_lname`", "`customer_password`", "`customer_email`", "`customer_phone`", "`customer_company`", "`customer_status`", "`customer_created_date`", "customer_image", "`customer_created_by`", "`customer_is_email_verified`", "`customer_is_mobile_verified`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_customer_type`" => array(
															"column"=> array("`customer_type_name`"),
															"reference" => "b",
															"join" => array("type"=>"INNER JOIN", "table"=>"`app_customer`", "on"=>array("`customer_type_id`" => "`customer_type_id`"))
															)
								),
					"ORDER"	=>array("`customer_created_date`", "CONCAT(`customer_fname`,  `customer_lname`)", "`customer_email`", "`customer_type_name`", "`customer_status`")						
							  );
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $filed=>$values){
				$filedCondArray = array();
				if(is_array($values)){
					foreach($values as $_val)
						$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($_val));
				}
				else
					$filedCondArray[] = array("a.".$filed, "=", sanitizePostData($values));
				$conditionArray[] = $filedCondArray;	
			}
		}
		$this->condition = $conditionArray;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`customer_id`");
				
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		
		$dbcTotal 	= 	new DB();
		$_SESSION['REPORT']['CUSTOMER-EXPORT']=$sqlTotal = $this->SqlExceptLimit;
		$resultTotal = $dbcTotal->db_query($sqlTotal);
		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
		
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								"<img class=\"img-avatar\" src=\"".getResizeImage($row["customer_image"], 50)."\" height=\"40px\">",
								$row["customer_fname"]." ".$row["customer_lname"]."<br/><div class=\"small text-muted\">".dateView($row["customer_created_date"],"NOW")."</div>",
								$row["customer_email"]."<div>".$row["customer_phone"]."</div>",
								$row["customer_type_name"],
								statusView($row["customer_id"],$row["customer_status"]),
								"<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\">
    
    <a class=\"dropdown-item redirect\" href=\"updatecustomer/".$row['customer_id']."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>    	
    <a class=\"dropdown-item\" href=\"#\" onclick=\"newWindow('".$app->basePath("report.php?format=customer-record&id=$row[customer_id]")."')\"><i class=\"fa fa-file-pdf-o fa-fw\"></i> Performance</a><a class=\"dropdown-item\" href=\"javascript:requestFeedback('".$row['customer_id']."', 'U', '".$row['customer_id']."')\"><i class=\"fa fa-comments-o fa-fw\"></i> Request Feedback</a>
	<a class=\"dropdown-item copytochasecustomer\" data-id=\"".$row['customer_id']."\" href=\"javascript:void(0)\"><i class=\"fa fa-copy fa-fw\"></i> Copy to Chase Customer</a>
  </div>
</div>",
								"updatecustomer/".$row['customer_id'],
								
			);
		}
		return json_encode($output);		
	}
	
	
	
	function getJsonRecordscheckbox($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length,  $group_id){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_customer`" => array(
															"column"=> array("`customer_id`", "`customer_type_id`", "`customer_code`", "`customer_fname`",  "`customer_lname`", "`customer_password`", "`customer_email`", "`customer_phone`", "`customer_status`", "`customer_created_date`", "customer_image", "`customer_created_by`", "`customer_is_email_verified`", "`customer_is_mobile_verified`"),
															"reference" => "a",
															"join" => NULL
															)
								),
					"ORDER"	=>array("CONCAT(`customer_fname`,  `customer_lname`)", "`customer_email`", "`customer_phone`", "`customer_created_date`", "`customer_status`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`customer_id`");
		$this->condition = array();		
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		$num_rows_total= $this->Count();
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		
		
		
		while($row = $dbc->db_fetch_assoc()){					
			$output["data"][] = array(
								"<img class=\"img-avatar\" src=\"".getResizeImage($row["customer_image"], 50)."\" height=\"28px\">",
								$row["customer_fname"]." ".$row["customer_lname"]."<br/><span class='small'>".$row["customer_email"]."</span>",
								$row["customer_email"]."</div>",
								$row["customer_status"]?"Active":"Deactive",
								"",
								$row['customer_id']
			);
		}
		return json_encode($output);		
	}
	
	function getExtra($fieldName, $keyword)
	{
		global $app;
		$sql="SELECT $fieldName as itemlabel ,a.`customer_id`, a.`customer_fname`, a.`customer_lname`, a.`customer_email`,  a.`customer_image`, a.`customer_phone`, a.`customer_company`, a.`customer_type_id`, `customer_address_street_number`, `customer_address_route`, `customer_address_locality`, `customer_address_administrative_area`, `customer_address_country`, `customer_address_postcode`, `customer_address_geo_location` FROM `app_customer` as a INNER JOIN `app_customer_type` as b ON a.`customer_type_id`=b.`customer_type_id` LEFT JOIN (SELECT MAX(acd.customer_address_id) as maxid, acd.* FROM `app_customer_address` as acd GROUP BY acd.`customer_id`) address_max ON a.`customer_id` = address_max.customer_address_id WHERE LOWER($fieldName) LIKE '$keyword%' ORDER BY $fieldName";
		//echo $sql;	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();
		if($result)
		if($dbc->db_num_rows()>0)
		{	
			while($row = $dbc->db_fetch_assoc(true))	
				$data[$row['itemlabel']] = $row;	
		}
		return $data;
	}
	
	function getReportSql($format = NULL)
	{
		global $app;
		$image_path = $app->sitePath();
		$barcode_file = $app->basePath("qrcode.php?data=");
		if($format == NULL)
		return "SELECT a.`customer_id`, a.`customer_code`, CONCAT(a.`customer_fname`, ' ', a.`customer_lname`) as customer_name, a.`customer_email`, a.`customer_phone`,  a.`customer_image`,  b.`customer_type_name`, CASE WHEN a.`customer_status` = 1 THEN 'Active' ELSE 'Deactive' END as status, DATE_FORMAT(a.`customer_created_date`, '%d-%b %Y %H:%i%p') as careated_date , (SELECT COUNT(`complaint_id`) FROM `app_complaint_record` WHERE `complaint_customer_id` = a.`customer_id`) as totalrequest, GROUP_CONCAT(CONCAT(`customer_address_street_number`, ' ', `customer_address_route` , ' ', `customer_address_locality`, ' ', `customer_address_administrative_area`, ' ', `customer_address_country`, ' ', `customer_address_postcode`) separator '<br/>') as customer_address_name 
		FROM `app_customer` as a 
		INNER JOIN `app_customer_type` as b ON a.`customer_type_id`=b.`customer_type_id`
		INNER JOIN `app_customer_address` as c ON a.`customer_id`=c.`customer_id` 
		WHERE 1 GROUP BY a.`customer_id`";
	}
	
	function getConsolidateRecord(){
		$sql = "SELECT DISTINCT customer_fname, customer_lname,customer_phone, (select count(complaint_id) from app_complaint_record where complaint_customer_id = customer_id) as rma, (select count(sales_invoice_id) from app_sales_invoice where sales_invoice_customer_id = customer_id) as invoice, (select count(wc_id) from app_wc_record where wc_customer_id=customer_id) as collection, (select count(refund_id) from app_refund_record where refund_customer_id= customer_id) as refund, (select count(web_order_id) from app_website_order WHERE web_order_customer_id=customer_id) as orders FROM app_customer WHERE `".$this->key_id."` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)>0)
		{
			return $dbc->db_fetch_assoc();
		}
		else
			return false;	
	}
	
	function getListView($offset=0, $searchkeyword = "")
	{
		global $app;
		$condition = "";
		if($searchkeyword!="")
		{
			$searchkeyword = trim(strtolower($searchkeyword));
			$condition = " AND (LOWER(a.`customer_fname`) LIKE '%$searchkeyword%' OR  LOWER(a.`customer_lname`) LIKE '%$searchkeyword%' OR LOWER(a.`customer_email`) LIKE '%$searchkeyword%'  OR LOWER(CONCAT(a.`customer_fname`, ' ', a.`customer_lname`)) LIKE '%$searchkeyword%' OR  LOWER(b.`customer_type_name`) LIKE '%$searchkeyword%') ";
		}
		
		$sql="SELECT a.`customer_id`, a.`customer_code`, a.`customer_fname`, a.`customer_lname`, CONCAT(a.`customer_fname`, ' ', a.`customer_lname`) as customer_name, a.`customer_email`, a.`customer_phone`, a.`customer_type_id`,  a.`customer_image`, a.`customer_status`, a.`customer_password`, a.`customer_created_date`, a.`customer_created_by`, a.`customer_is_mobile_verified`, a.`customer_is_email_verified`, b.`customer_type_name`
		FROM `app_customer` as a 
		INNER JOIN `app_customer_type` as b ON a.`customer_type_id`=b.`customer_type_id` 
		WHERE 1 $condition GROUP BY a.`customer_id` ORDER BY a.`customer_id` DESC Limit 10 OFFSET $offset";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();	
		while($row = $dbc->db_fetch_assoc(true)){					
			$row['customer_image']			= getResizeImage($row['customer_image'], 50);
			$row['customer_created_date']	= dateView($row['customer_created_date'], "DATE");
			$row['customer_status_color']	= $row['customer_status']?"#4CAF50":"#F44336";
			$record_array[] = $row;		
		}
		return $record_array;	
	}
	
	function getAndroidDetails()
	{
		$sql="SELECT a.`customer_id`, a.`customer_code`, a.`customer_fname`, a.`customer_lname`, CONCAT(a.`customer_fname`, ' ', a.`customer_lname`) as customer_name, a.`customer_email`, a.`customer_phone`, a.`customer_type_id`, a.`customer_image`, b.`customer_type_name`, a.`customer_status`, a.`customer_password`, a.`customer_created_date`, a.`customer_created_by`, a.`customer_is_mobile_verified`, a.`customer_is_email_verified`, b.`customer_type_name`,   GROUP_CONCAT(CONCAT(`customer_address_street_number`, ' ', `customer_address_route` , ' ', `customer_address_locality`, ' ', `customer_address_administrative_area`, ' ', `customer_address_country`, ' ', `customer_address_postcode`) separator '<br/>') as customer_full_address FROM `app_customer` as a 
 INNER JOIN `app_customer_type` as b ON a.`customer_type_id`=b.`customer_type_id` 
 INNER JOIN `app_customer_address` as c ON a.`customer_id`=c.`customer_id` 
 WHERE a.`customer_id` = '".$this->id."' GROUP BY a.`customer_id`";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
}

?>