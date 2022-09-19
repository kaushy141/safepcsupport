<?php
class SalesInvoice extends DB{
	public $id;
	public $key_id	=	"sales_invoice_id";
	public $table_name	=	"app_sales_invoice";
	public $status	=	"sales_invoice_status";	
	public $customer		=	0;
	function __construct($id=0){
				
		$this->id	=	$id;
	}
	
	function getInvoiceColumnsValuesArray(){
		$sql="SELECT * FROM `app_sales_invoice` WHERE `sales_invoice_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result)){	
			$values = $dbc->db_fetch_assoc(true);	
			unset($values['sales_invoice_id']);		
			$values['sales_invoice_number'] = $this->getSalesInvoiceNumber();
			$values['sales_invoice_is_paid'] = 0;
			$values['sales_invoice_creator'] = getLoginId();
			$values['sales_invoice_is_original'] = 1;
			$values['sales_invoice_date'] = 'CURRENT_DATE';		
			$values['sales_invoice_created_date'] = 'NOW()';					
			$values['sales_invoice_is_cancelled'] = 0;
			$values['sales_invoice_status'] = 2;
			$values['sales_invoice_picking_user'] = 0;
			$values['sales_invoice_picking_time'] = 'NULL';
			$values['sales_invoice_packing_user'] = 0;
			$values['sales_invoice_packing_time'] = 'NULL';
			$values['sales_invoice_process_user'] = 0;
			$values['sales_invoice_process_time'] = 'NULL';
			$values['sales_invoice_cancel_user'] = 0;
			$values['sales_invoice_cancel_time'] = 'NULL';
			$values['sales_invoice_commission_processed'] = 0;
			$values['sales_invoice_is_generated'] = 0;
			return $values;
		}
		else
			return false;
	}
	
	function autoGenerateCycleInvoice()
	{
		$values = $this->getInvoiceColumnsValuesArray();
		unset($values['sales_invoice_id']);
		$values['sales_invoice_is_original'] = 0;
		$salesInvoice = new SalesInvoice();
		$dest_sales_invoice_id = $salesInvoice->insert($values);
		$this->copyProductsFromInvoice($this->id, $dest_sales_invoice_id);	
		return $dest_sales_invoice_id;
	}
	
	function duplicateInvoice($customer_email = null, $address_id = 0){
		$values = $this->getInvoiceColumnsValuesArray();
		$salesInvoice = new SalesInvoice();
		$dest_sales_invoice_id = $salesInvoice->insert($values);
		$this->copyProductsFromInvoice($this->id, $dest_sales_invoice_id);	
		if($customer_email != null){
			$duplicateSalesInvoice = new SalesInvoice($dest_sales_invoice_id);
			$duplicateInvoiceData = $duplicateSalesInvoice->getDetails();
			$customer_email = trim(strtolower($customer_email));
			$customer = new Customer();
			$new_customer_id = $customer->getIdByEmail(md5($customer_email));
			
			if($new_customer_id != $duplicateInvoiceData['sales_invoice_customer_id'] || $address_id != $duplicateInvoiceData['sales_invoice_customer_address_id']){
				if(!$new_customer_id){
					$customer_image        = DEFAULT_USER_IMAGE;        
					$customer = new Customer();
					$customer_code = $customer->getNewCode($customer_email, $customer_email);
					
					$Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
					
					$new_customer_id = $customer->insert(array(
						'customer_code' => $customer_code,
						'customer_fname' => '',
						'customer_lname' => '',
						'customer_email' => $customer_email,
						'customer_phone' => '',
						'customer_company' => '',
						'customer_type_id' => 6,
						'customer_image' => $customer_image,
						'customer_status' => 1,
						'customer_password' => gePassword(),
						'customer_created_by' => getLoginId(),
						'customer_is_mobile_verified'=> 0,
						'customer_is_email_verified'=> 0
					));
				}
				if($address_id == 0){
					$customerAddress = new CustomerAddress();
					$address_id = $customerAddress->add($new_customer_id, '', '', '','', '', '', '', '', '');
				}
				$duplicateSalesInvoice->update(array(
					'sales_invoice_customer_id' => $new_customer_id,
					'sales_invoice_customer_address_id' => $address_id
				));
			}
		}
		return $dest_sales_invoice_id;
	}
	
	
	function copyProductsFromInvoice($source_sales_invoice_id, $dest_sales_invoice_id)
	{
		$sql="INSERT INTO `app_sales_invoice_product_detail`(`sipd_invoice_id`, `sipd_product_id`, `sipd_product_quantity`, `sipd_product_sku`, `sipd_product_price`) (SELECT '$dest_sales_invoice_id' as sipd_invoice_id, `sipd_product_id`, `sipd_product_quantity`, `sipd_product_sku`, `sipd_product_price` FROM `app_sales_invoice_product_detail` WHERE `sipd_invoice_id` = '$source_sales_invoice_id')";	
		//echo $sql;die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function getSalesInvoiceStatistics($conditions, $web_order_currency = 'GBP'){
		$sql = "SELECT b.`store_name`, b.`store_icon`, 
		COUNT(*) as total_order,
		SUM(a.`sales_invoice_total_amount` - a.`sales_discount_amount`) as total_amount,
		SUM(CASE WHEN a.`sales_invoice_status` = 1 THEN 1 ELSE 0 END) AS completed, 
		SUM(CASE WHEN a.`sales_invoice_status` = 1 THEN (a.`sales_invoice_total_amount` - a.`sales_discount_amount`) ELSE 0 END) AS completed_amount, 
		SUM(CASE WHEN a.`sales_invoice_status` = 3 THEN 1 ELSE 0 END) AS cancelled, 
		SUM(CASE WHEN a.`sales_invoice_status` = 3 THEN (a.`sales_invoice_total_amount` - a.`sales_discount_amount`) ELSE 0 END) AS cancelled_amount 
		FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`sales_invoice_store_id` = b.`store_id`  $conditions AND `sales_invoice_currency` = '$web_order_currency' AND a.`sales_invoice_is_dummy` = '0' GROUP BY  b.`store_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$total_website_data = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['total_amount'] = round($row['total_amount'],2);
				$row['completed_amount'] = round($row['completed_amount'],2);
				$row['cancelled_amount'] = round($row['cancelled_amount'],2);
				$total_website_data[] = $row;				
			}
		}
		return $total_website_data;
	}
	
		
	function add($sales_invoice_number, $sales_invoice_customer_id, $sales_invoice_customer_address_id, $sales_invoice_store_id, $sales_invoice_is_paid, $sales_invoice_payment_mode, $sales_invoice_payment_reference, $sales_invoice_cycle_mode, $sales_invoice_is_generated, $sales_invoice_is_original, $sales_invoice_creator, $sales_invoice_is_estimate, $sales_invoice_is_vat_applicable, $sales_discount_amount, $sales_invoice_manufacturer,  $sales_invoice_status, $sales_invoice_vat, $sales_invoice_bank_account, $sales_invoice_currency)
	{
		$sql="INSERT INTO `app_sales_invoice`(`sales_invoice_number`, `sales_invoice_customer_id`, `sales_invoice_customer_address_id`, `sales_invoice_store_id`, `sales_invoice_is_paid`, `sales_invoice_payment_mode`, `sales_invoice_payment_reference`, `sales_invoice_cycle_mode`,  `sales_invoice_created_date`, `sales_invoice_is_generated`, `sales_invoice_is_original`, `sales_invoice_creator`, `sales_invoice_is_estimate`, `sales_invoice_is_vat_applicable`, `sales_discount_amount`, `sales_invoice_manufacturer`, `sales_invoice_status`, `sales_invoice_vat`, `sales_invoice_bank_account`, `sales_invoice_currency`) VALUES ('$sales_invoice_number', $sales_invoice_customer_id, '$sales_invoice_customer_address_id', '$sales_invoice_store_id', '$sales_invoice_is_paid', '$sales_invoice_payment_mode', '$sales_invoice_payment_reference', '$sales_invoice_cycle_mode', NOW(), '$sales_invoice_is_generated', '$sales_invoice_is_original', '$sales_invoice_creator', '$sales_invoice_is_estimate', '$sales_invoice_is_vat_applicable', '$sales_discount_amount', '$sales_invoice_manufacturer', '$sales_invoice_status', '$sales_invoice_vat', '$sales_invoice_bank_account', '$sales_invoice_currency')";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$this->id = $dbc->db_insert_id();	
		return $this->id;
	}
	
	function copyInvoice($sales_invoice_number, $sales_invoice_customer_id, $sales_invoice_customer_address_id, $sales_invoice_store_id, $sales_invoice_is_paid, $sales_invoice_payment_mode, $sales_invoice_payment_reference, $sales_invoice_cycle_mode, $sales_invoice_is_generated,  $sales_invoice_is_original, $sales_invoice_creator, $sales_invoice_is_estimate, $sales_invoice_is_vat_applicable, $sales_discount_amount, $sales_invoice_manufacturer,  $sales_invoice_vat, $sales_invoice_bank_account, $sales_invoice_currency)
	{
		$sql="INSERT INTO `app_sales_invoice`(`sales_invoice_number`, `sales_invoice_customer_id`, `sales_invoice_customer_address_id`, `sales_invoice_store_id`, `sales_invoice_is_paid`, `sales_invoice_payment_mode`, `sales_invoice_payment_reference`, `sales_invoice_cycle_mode`,  `sales_invoice_created_date`, `sales_invoice_is_generated`,`sales_invoice_is_original`, `sales_invoice_creator`, `sales_invoice_last_date`, `sales_invoice_is_estimate`, `sales_invoice_is_vat_applicable`, `sales_discount_amount`, `sales_invoice_manufacturer`, `sales_invoice_vat`, `sales_invoice_bank_account`, `sales_invoice_currency`) VALUES ('$sales_invoice_number', $sales_invoice_customer_id, '$sales_invoice_customer_address_id', '$sales_invoice_store_id', '$sales_invoice_is_paid', '$sales_invoice_payment_mode', '$sales_invoice_payment_reference', '$sales_invoice_cycle_mode', NOW(), '$sales_invoice_is_generated', '$sales_invoice_is_original', '$sales_invoice_creator', CURRENT_DATE,  '$sales_invoice_is_estimate', '$sales_invoice_is_vat_applicable', '$sales_discount_amount', '$sales_invoice_manufacturer', '$sales_invoice_vat', '$sales_invoice_bank_account', '$sales_invoice_currency')";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function getLastTimePaidMark(){
		$sql = "SELECT MAX(`sales_invoice_mark_paid_on`) as simpo FROM `app_sales_invoice`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record = $dbc->db_fetch_assoc(true);
		return $record['simpo'];
	}
	
	function getSalesInvoiceFromInvoiceID($sales_invoice_mark_paid_on = null){
		global $app;
		$condition = $sales_invoice_mark_paid_on != null ? " AND a.`sales_invoice_mark_paid_on` > '$sales_invoice_mark_paid_on' ORDER BY a.`sales_invoice_id` DESC LIMIT 3" : " ORDER BY a.`sales_invoice_id` DESC LIMIT 1";
		$sql = "SELECT a.`sales_invoice_id`, a.`sales_invoice_number`, a.`sales_invoice_mark_paid_on`, a.`sales_payment_paid_amount`, b.user_fname,  b.user_lname,  b.user_image, c.`customer_fname`, c.`customer_lname`, d.`store_name`, d.`store_icon`,d.`store_logo`
		FROM  `app_sales_invoice` AS a  
		INNER JOIN `app_system_user` AS b ON ( b.`user_id` = a.`sales_invoice_mark_paid_by` )  
		INNER JOIN `app_customer` AS c ON ( c.`customer_id` = a.`sales_invoice_customer_id` )  
		INNER JOIN `app_store_master` AS d ON ( d.`store_id` = a.`sales_invoice_store_id` )  
		WHERE a.`sales_invoice_mark_paid_on` IS NOT NULL $condition";
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$result_array = array();
		if($dbc->db_num_rows() > 0)
		{
			while($row = $dbc->db_fetch_assoc(true)){
				$result_array[] = array(
											"title" => "{$row['user_fname']} {$row['user_lname']} marked <b class='text-success'>Paid</b> {$row['store_name']}'s Sales invoice #<b>{$row['sales_invoice_number']}</b>.",
											"image" => $app->imagePath($row["user_image"]),
											"user"  => $row['customer_fname']. " ". $row['customer_lname'],
											"time"	=> dateView($row["sales_invoice_mark_paid_on"],"NOW"),
											"link"	=> $app->siteUrl("salesinvoice/".$row["sales_invoice_id"]),
											"limit" => $row['sales_invoice_mark_paid_on']
										);
			}
			$result_array = array_reverse($result_array);
		}
		return $result_array;
	}
	
	function delete()
	{
		$sql = "DELETE FROM `app_sales_invoice` WHERE `sales_invoice_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		$sql = "DELETE FROM `app_sales_invoice_product_detail` WHERE `sipd_invoice_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
	}
	
	function getCycledOriginalSalesInvoice()
	{
		$sql="SELECT `sales_invoice_id`, DATE_FORMAT(`sales_invoice_last_date`, '%Y-%m-%d') as sales_invoice_last_date, `sales_invoice_cycle_mode` FROM `app_sales_invoice` WHERE `sales_invoice_is_original` = '1' AND `sales_invoice_cycle_mode` = 'WEEK' AND ADDDATE(`sales_invoice_last_date`, INTERVAL 1 WEEK) = CURRENT_DATE UNION
		SELECT `sales_invoice_id`, DATE_FORMAT(`sales_invoice_last_date`, '%Y-%m-%d') as sales_invoice_last_date, `sales_invoice_cycle_mode` FROM `app_sales_invoice` WHERE `sales_invoice_is_original` = '1' AND `sales_invoice_cycle_mode` = 'MONTH' AND ADDDATE(`sales_invoice_last_date`, INTERVAL 1 MONTH) = CURRENT_DATE UNION
		SELECT `sales_invoice_id`, DATE_FORMAT(`sales_invoice_last_date`, '%Y-%m-%d') as sales_invoice_last_date, `sales_invoice_cycle_mode` FROM `app_sales_invoice` WHERE `sales_invoice_is_original` = '1' AND `sales_invoice_cycle_mode` = 'YEAR' AND ADDDATE(`sales_invoice_last_date`, INTERVAL 1 YEAR) = CURRENT_DATE";
		//echo $sql;die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		if($dbc->db_num_rows($result))
		{
			while($row = $dbc->db_fetch_assoc(true))
			$record_array[] = $row;
			return $record_array;
		}
		return NULL;
	}
	
	function updateNextInvoiceDate($sales_invoice_last_date = NULL)
	{
		if($sales_invoice_last_date == NULL)
			$sales_invoice_last_date = "CURRENT_DATE";
		else
			$sales_invoice_last_date = "'$sales_invoice_last_date'";
			
		$sql = "UPDATE `app_sales_invoice` SET `sales_invoice_last_date` = $sales_invoice_last_date WHERE `sales_invoice_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getSalesInvoiceNumber()
	{
		$sql="SELECT `sales_invoice_number`, CURRENT_DATE as currentdate FROM `app_sales_invoice` WHERE YEAR(`sales_invoice_created_date`) = YEAR(NOW()) AND MONTH(`sales_invoice_created_date`) = MONTH(NOW()) ORDER BY `sales_invoice_id` DESC LIMIT 1 ";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows())
		$data = $dbc->db_fetch_assoc();
		else
		{
			$data['sales_invoice_number'] = date("ym")."0000";
			$data['currentdate'] = date("Y-m-d");
		}
		$numcount = intval(substr($data['sales_invoice_number'],5,4))+1;
		return strtoupper(date("ym",strtotime($data['currentdate'])).sprintf("%04d",$numcount));
	}
	
	function addProducts($sipd_invoice_id, $sipd_product_id, $sipd_product_quantity, $sipd_product_sku, $sipd_bach_product_code,  $sipd_product_price, $sipd_purchase_amount, $sipd_purchase_source)
	{
		$sql="INSERT INTO `app_sales_invoice_product_detail`(`sipd_invoice_id`, `sipd_product_id`, `sipd_product_quantity`, `sipd_product_sku`, `sipd_bach_product_code`, `sipd_product_price`, `sipd_purchase_amount`, `sipd_purchase_source`) VALUES ('$sipd_invoice_id', '$sipd_product_id', '$sipd_product_quantity', '$sipd_product_sku', '$sipd_bach_product_code', '$sipd_product_price', '$sipd_purchase_amount', '$sipd_purchase_source')";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		return $dbc->db_insert_id();	
	}
	
	function isProductUpdated($sipd_invoice_id, $sipd_product_id, $sipd_product_quantity, $sipd_product_price)
	{
		$sql="SELECT `sipd_id` FROM `app_sales_invoice_product_detail` WHERE `sipd_invoice_id` = '$sipd_invoice_id' AND `sipd_product_id` = '$sipd_product_id' AND `sipd_product_quantity` = '$sipd_product_quantity' AND `sipd_product_price` = '$sipd_product_price'";
		$dbc 	= 	new DB();
		$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$record = $dbc->db_fetch_assoc(true);
			return $record['sipd_id'];
		}
		else
			return 0;			
	}
	
	function load($code='')
	{
		$sql="SELECT a.*, b.`customer_id`, b.`customer_fname`, b.`customer_lname`, CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_email`, b.`customer_company`, b.`customer_image`, b.`customer_phone`, b.`customer_type_id`, b.`customer_tax_number`, c.`customer_address_id`, c.`customer_address_street_number`, c.`customer_address_route`, c.`customer_address_locality`, c.`customer_address_administrative_area`, c.`customer_address_country`, c.`customer_address_postcode`, c.`customer_address_geo_location`, CONCAT(c.`customer_address_street_number`,  ', ', c.`customer_address_route`, ', ', c.`customer_address_locality`, ', ', c.`customer_address_administrative_area`, ', ', c.`customer_address_country`, ' ', c.`customer_address_postcode`) as customer_full_address, d.`store_name`, d.`store_logo`, d.`store_icon`, d.`store_vat_percent`, e.`wc_status_name`, e.`wc_status_color_code` FROM `app_sales_invoice` as a 
		LEFT JOIN `app_customer` as b ON  a.`sales_invoice_customer_id`= b.`customer_id`
		LEFT JOIN `app_customer_address` as c ON a.`sales_invoice_customer_address_id`= c.`customer_address_id`
		LEFT JOIN `app_store_master` as d ON a.`sales_invoice_store_id` = d.`store_id`  
		LEFT JOIN `app_wc_status` AS e ON e.`wc_status_id` = a.`sales_invoice_status`  
		WHERE a.`sales_invoice_id` = '".$this->id."' OR a.`sales_invoice_number` = '$code'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function getProductsIdArray()
	{
		$sql="SELECT GROUP_CONCAT(`sipd_product_id`) as products FROM `app_sales_invoice_product_detail` WHERE `sipd_invoice_id` = '".$this->id."'";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))
		{
			$row = $dbc->db_fetch_assoc(true);
			return explode(",", $row['products']);
		}
		return NULL;
	}
	
	function updateProduct($sipd_product_id, $sipd_product_quantity, $sipd_product_sku, $sipd_bach_product_code, $sipd_product_price, $sipd_purchase_amount, $sipd_purchase_source)
	{
		$sql="UPDATE `app_sales_invoice_product_detail` SET `sipd_product_quantity` = '$sipd_product_quantity' ,`sipd_product_sku` = '$sipd_product_sku', `sipd_bach_product_code` = '$sipd_bach_product_code' ,`sipd_product_price` = '$sipd_product_price' ,`sipd_purchase_amount` = '$sipd_purchase_amount' ,`sipd_purchase_source` = '$sipd_purchase_source' WHERE `sipd_invoice_id`='".$this->id."' AND `sipd_product_id`='$sipd_product_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function updateProductSerials($sipd_id, $serial_number, $type = 1)
	{
		$sql = "SELECT `sipd_product_serial_details` FROM `app_sales_invoice_product_detail` WHERE `sipd_id` = '$sipd_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$sipd_data = "";
		if($dbc->db_num_rows($result) == 1)
		{
			$record = $dbc->db_fetch_assoc(true);
			$sipd_data = $record['sipd_product_serial_details'];
		}
		$sipd_data_array = $sipd_data != "" ? explode("|", $sipd_data) : array();
		
		$type == 1 ? array_push($sipd_data_array, $serial_number): (count($sipd_data_array) ? $sipd_data_array = array_diff($sipd_data_array, array($serial_number)) : array());
		
		$sipd_product_serial_details = count($sipd_data_array) ? implode("|", $sipd_data_array) : "";
		$sqlUpdate="UPDATE `app_sales_invoice_product_detail` SET `sipd_product_serial_details` = '$sipd_product_serial_details' WHERE  `sipd_id`='$sipd_id'";
		$dbc 	= 	new DB();
		return $dbc->db_query($sqlUpdate);
	}
	
	function removeProduct($sipd_product_id)
	{
		$sql="DELETE FROM `app_sales_invoice_product_detail` WHERE `sipd_invoice_id`='".$this->id."' AND `sipd_product_id`='$sipd_product_id'";	
		$dbc 	= 	new DB();
		return $dbc->db_query($sql);
	}
	
	function getProducts()
	{
		$sql="SELECT a.`sipd_id`, a.`sipd_product_price` as product_price, a.`sipd_product_quantity` as product_quantity, a.`sipd_product_sku` as product_srno, a.`sipd_bach_product_code` as product_batch_code, a.`sipd_product_serial_details`, a.`sipd_purchase_amount`, a.`sipd_purchase_source`, b.`product_name`, c.`hardware_id`, c.`hardware_name` FROM `app_sales_invoice_product_detail` as a 
		INNER JOIN `app_sales_products` as b ON a.`sipd_product_id` = b.`product_id`
		INNER JOIN `app_hardware_type` as c ON b.`product_hardware_id` = c.`hardware_id` 
		WHERE a.`sipd_invoice_id` = '".$this->id."'";	
		//echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$record_array = array();
		if($dbc->db_num_rows($result))
		{
			while($row = $dbc->db_fetch_assoc(true))
			$record_array[] = $row;
			return $record_array;
		}
		return NULL;
	}
	
	function geInvoiceProductsQuery($currency = 'GBP', $vat=SALES_VAT_PERCENTAGE)
	{
		return "SELECT CONCAT('$currency ',ROUND((a.`sipd_product_price`*100)/(100+$vat),2)) as product_unit_price, CONCAT('$currency ', ROUND((((a.`sipd_product_price`*100)/(100+$vat)) * a.`sipd_product_quantity`), 2)) as product_amount,  a.`sipd_product_quantity` as product_quantity, CONCAT(c.`hardware_name`, ': ', b.`product_name`, CASE WHEN (a.`sipd_product_serial_details` IS NOT NULL OR a.`sipd_product_serial_details` != '') THEN CONCAT(' - <b>', REPLACE(a.`sipd_product_serial_details`, '|', '</b> , <b>'), '</b>') ELSE '' END) as product_name, CONCAT('$currency ',ROUND((((a.`sipd_product_price`*100)/(100+$vat)*$vat/100) * a.`sipd_product_quantity`),2)) as product_vat FROM `app_sales_invoice_product_detail` as a 
		INNER JOIN `app_sales_products` as b ON a.`sipd_product_id` = b.`product_id`		
		INNER JOIN `app_hardware_type` as c ON b.`product_hardware_id` = c.`hardware_id` 
		WHERE a.`sipd_invoice_id` = '".$this->id."' ORDER BY a.sipd_id";	
	}
	
	function getInvoiceSum($vat=null)
	{
		$vat = $vat === null ? SALES_VAT_PERCENTAGE : $vat;
		$sql = "SELECT SUM(`sipd_product_quantity`) AS items, SUM(ROUND(((`sipd_product_price`*100)/(100+$vat) * $vat/100),2) * `sipd_product_quantity`) as product_sum_vat, SUM(ROUND((`sipd_product_price`*100)/(100+$vat),2) * `sipd_product_quantity`) as product_sum_amount FROM `app_sales_invoice_product_detail` WHERE `sipd_invoice_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))	
		{	
			$data = $dbc->db_fetch_assoc(true);
			return $data;
		}
	}
	
	function getRevenueReport(){
		$sql = "SELECT DATE_FORMAT(`sales_invoice_date`, '%b-%y')as month, SUM(`sales_invoice_total_amount`) as sale FROM `app_sales_invoice` group by month order by `sales_invoice_date` desc LIMIT 12";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		$recordArray = array();
		while($row = $dbc->db_fetch_assoc())
		{
			$recordArray['label'][]= $row['month'];
			$recordArray['value'][]= $row['sale'];
		}
		return $recordArray;

	}
	
	function getReportSql()
	{
		$vat = SALES_VAT_PERCENTAGE;
		return "SELECT a.`sales_invoice_number`, DATE_FORMAT( a.`sales_invoice_date`, '%d-%b-%Y') as invoice_created_date, CONCAT(`customer_fname`, ' ', `customer_lname`) as customer_name, b.`customer_id`, CONCAT(b.`customer_email`,' / ', b.`customer_phone`) as customer_contact, ROUND(SUM(d.`sipd_product_quantity`*ROUND((d.`sipd_product_price`*100)/(100+$vat),2)),2) as total_products_amount, SUM(ROUND(((d.`sipd_product_price`*100)/(100+$vat)*20/100),2)) as total_products_vat, ROUND(SUM(d.`sipd_product_quantity` * d.`sipd_product_price`),2) as total_products_total, SUM(d.`sipd_product_quantity`) as total_products_quantity,  g.`store_name` FROM `app_sales_invoice` AS a LEFT JOIN `app_customer` AS b ON ( b.`customer_id` = a.`sales_invoice_customer_id` ) LEFT JOIN `app_sales_invoice_product_detail` AS d ON ( d.`sipd_invoice_id` = a.`sales_invoice_id` ) LEFT JOIN `app_store_master` AS g ON ( g.`store_id` = a.`sales_invoice_store_id` ) WHERE 1 GROUP BY a.`sales_invoice_id` ORDER BY `sales_invoice_date`";	
	}
		
	function getDetails()
	{
		$sql="SELECT a.*,  CONCAT(b.`customer_fname`, ' ', b.`customer_lname`) as customer_name, b.`customer_image`, b.`customer_email`, b.`customer_phone`, c.`store_name`, c.`store_icon` FROM `app_sales_invoice` as a 
		LEFT JOIN `app_customer` as b ON a.`sales_invoice_customer_id` = b.`customer_id` 
		LEFT JOIN `app_store_master` as c ON a.`sales_invoice_customer_id` = c.`store_id`  
		WHERE a.`sales_invoice_id` = '".$this->id."'";	
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function getJsonRecords($draw, $searchKeyword, $orderPosition, $orderDirection, $start, $length, $filter=NULL, $pending = false){
				
		global $app;
		$this->start 	= $start;
		$this->length	= $length;
		$this->aColumn = array(
					"TABLES"=>array(
								"`app_sales_invoice`" => array(
															"column"=> array("`sales_invoice_id`", "`sales_invoice_number`", "`sales_invoice_date`", "`sales_invoice_created_date`", "`sales_invoice_is_paid`", " `sales_invoice_payment_mode`", "`sales_invoice_payment_reference`", "`sales_invoice_cycle_mode`", "`sales_invoice_is_estimate`", "`sales_invoice_is_cancelled`", "`sales_invoice_is_generated`", "`sales_invoice_currency`", "`sales_invoice_is_dummy`"),
															"reference" => "a",
															"join" => NULL
															),
								"`app_customer`" => array(
															"column"=> array("CONCAT(`customer_fname`, ' ', `customer_lname`) as customer_name", "customer_fname", "customer_lname", "`customer_id`", "`customer_email`", "`customer_phone`", "`customer_is_mobile_verified`", "`customer_is_email_verified`"),
															"reference" => "b",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_sales_invoice`", "on"=>array("`customer_id`" => "`sales_invoice_customer_id`"))
															),
								
								"`app_sales_invoice_product_detail`" => array(
															"column"=> array("ROUND(SUM(d.`sipd_product_quantity`*d.`sipd_product_price`),2) as total_products_amount", "GROUP_CONCAT(DISTINCT d.`sipd_product_sku` SEPARATOR ', ') AS product_srno", "`sipd_product_sku`", "`sipd_product_serial_details`"),
															"reference" => "d",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_sales_invoice`", "on"=>array("`sipd_invoice_id`" => "`sales_invoice_id`"))
															),
								"`app_sales_products`" => array(
															"column"=> array("`product_name`"),
															"reference" => "e",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_sales_invoice_product_detail`", "on"=>array("`product_id`" => "`sipd_product_id`"))
															),
								"`app_store_master`" => array(
															"column"=> array("`store_name`", "`store_icon`", "`store_link`"),
															"reference" => "g",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_sales_invoice`", "on"=>array("`store_id`" => "`sales_invoice_store_id`"))
															),
								"`app_wc_status`" => array(
															"column"=> array("`wc_status_name`", "`wc_sales_status_color_code`"),
															"reference" => "f",
															"join" => array("type"=>"LEFT JOIN", "table"=>"`app_sales_invoice`", "on"=>array("`wc_status_id`" => "`sales_invoice_status`"))
															),
								),
					"ORDER"	=>array("sales_invoice_date", "`sales_invoice_is_estimate`", "`sales_invoice_number`", "CONCAT(`customer_fname`,  `customer_fname`)", "ROUND(SUM(d.`sipd_product_quantity`*d.`sipd_product_price`),2)", "`store_name`")						
							  );
		
		$this->searchKeyword = $searchKeyword;
		
		$conditionArray = array();
		if($filter != NULL && count($filter)){
			foreach($filter as $field=>$values){
				$filedCondArray = array();
				
				if($field == "sales_invoice_created_date_from"){
					if($values)
						$filedCondArray[] = array("DATE(sales_invoice_date)", ">=", sanitizePostData($values));
				}
				elseif($field == "sales_invoice_created_date_to"){
					if($values)
						$filedCondArray[] = array("DATE(sales_invoice_date)", "<=", sanitizePostData($values));
				}
				else
				{
					if(is_array($values)){
						foreach($values as $_val)
							$filedCondArray[] = array("a.".$field, "=", sanitizePostData($_val));
					}
					else
						$filedCondArray[] = array("a.".$field, "=", sanitizePostData($values));
				}

				$conditionArray[] = $filedCondArray;				
			}
		}
		if(isCustomer()){
			$conditionArray[] = array("`sales_invoice_customer_id`", "=", getLoginId());	
		}
		
		if($pending){
			$conditionArray[] = array("`sales_invoice_is_cancelled`", "=", '0');
			$conditionArray[] = array("`sales_invoice_status`", "!=", '1');
			$conditionArray[] = array("`sales_invoice_is_paid`", "=", '1');			
		}
		
		$this->condition = $conditionArray;
		
		if($this->customer!=0)
		$this->condition = array(array("b.`customer_id`","=",$this->customer));
											
		$this->orderPosition = $orderPosition; 
		$this->orderDirection= $orderDirection;
		$this->groupby = array("a.`sales_invoice_id`");
			
		
		$sql = $this->getSql();
		//echo $sql; die;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$num_rows_filtered= $dbc->db_num_rows($result);
		
		$dbcTotal 	= 	new DB();
		$resultTotal = $dbcTotal->db_query($this->SqlExceptLimit);
		$num_rows_total= $dbcTotal->db_num_rows($resultTotal);	
		$output =array("draw" => $draw, "recordsTotal" => $num_rows_total, "recordsFiltered" => $num_rows_total,"data"=>array());
		while($row = $dbc->db_fetch_assoc()){	
			$invoice_type_name = $row["sales_invoice_is_estimate"] ? "Estimate" : "Invoice";				
			$output["data"][] = array(								
								dateView($row["sales_invoice_date"],"DATE"),
								$invoice_type_name,
								$row["sales_invoice_number"],
								viewText($row["customer_name"]),
								getCurrencySymbol($row['sales_invoice_currency']).$row["total_products_amount"],
								$row["store_name"],
								$row["wc_status_name"].($row["sales_invoice_is_paid"]?" (Paid)":" (Unpaid)"),
								isAdmin() ? "<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-default dropdown-toggle dropdown-toggle-split\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    <i class=\"fa fa-navicon fa-fw\"></i> <span class=\"sr-only\">Toggle Dropdown</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right\" ".($row['sales_invoice_is_dummy'] == 1 ? "style=\"background-color:#ffee58\"" : "").">
	<a class=\"dropdown-item redirect\" href=\"salesinvoice/".$row['sales_invoice_id']."\"><i class=\"fa fa-share-square-o fa-fw\"></i> View</a>
    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#appModal\" onclick=\"openChatLogForm('$row[sales_invoice_id]|S', '$row[sales_invoice_number] Log Report')\"><i class=\"fa fa-comments-o fa-fw\"></i> Log</a>	
	".((1 || $row['sales_invoice_is_generated'] == 1 || $row['sales_invoice_is_estimate'] == 1) ? "<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::SALESINV($row['sales_invoice_id'])."');\"><i class=\"fa fa-file-pdf-o fa-fw text-danger\"></i> ".(($row['sales_invoice_is_generated'] == 0 && $row['sales_invoice_is_estimate'] == 0) ? "Preview ".($row['sales_invoice_is_dummy'] == 0 ? "" : "Dummy")." $invoice_type_name" : "".($row['sales_invoice_is_dummy'] == 0 ? "Customer" : "Dummy")." $invoice_type_name")."</a><a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::SALESINVCOMMERCIAL($row['sales_invoice_id'])."');\"><i class=\"fa fa-file-pdf-o fa-fw text-danger\"></i> Shipping $invoice_type_name</a>":"")."<a class=\"dropdown-item\" href=\"javascript:newWindow('".DOC::SALESPACKSLIP($row['sales_invoice_id'])."');\"><i class=\"fa fa-file-pdf-o fa-fw text-danger\"></i> Download Packing Slip</a>".(isAdmin()?	
    (($row['sales_invoice_is_cancelled']? "
	<a class=\"dropdown-item\"  href=\"#\" onclick=\"confirmMessage.Set('Are you sure to undo Cancel Sales Invoice...?', 'undoCancelInvoice', '$row[sales_invoice_id]')\"><i class=\"fa fa fa-ban fa-fw text-primary\"></i> UnDO Cancel</a>
	<a class=\"dropdown-item\" target=\"new\" href=\"".DOC::SALESINVMEMO($row['sales_invoice_id'])."\"><i class=\"fa fa fa-file-text-o fa-fw text-danger\"></i> Canceled MEMO</a>":($row['sales_invoice_is_dummy'] == 0 ? "<a class=\"dropdown-item\" href=\"#\" onclick=\"confirmMessage.Set('Are you sure to undo Cancel Sales Invoice...?', 'cancelInvoice', '$row[sales_invoice_id]')\"><i class=\"fa fa fa-ban fa-fw text-danger\"></i> Cancel Invoice</a>
	<a class=\"dropdown-item redirect\" href=\"addrefund/S/".$row['sales_invoice_id']."\"><i class=\"fa fa-registered text-danger fa-fw\"></i> Refund</a><a class=\"dropdown-item\" href=\"javascript:requestFeedback('".$row['sales_invoice_id']."', 'S', '".$row['customer_id']."')\"><i class=\"fa fa-rss fa-fw\"></i> Request Feedback</a></div></div>" :""))):""):"N/A",
								$_SESSION['app_theme'] != 'dark' ? $row['wc_sales_status_color_code']: null,	
								"salesinvoice/".$row['sales_invoice_id'],
								$row['sales_invoice_is_dummy']
								
			);
		}
		return json_encode($output);		
	}	
	
	static function getSalesStatusCount(){
		$sql = "SELECT count(a.`sales_invoice_id`) as record, b.`wc_status_name` as status, b.`wc_sales_status_color_code` as color FROM `app_sales_invoice` AS a INNER JOIN `app_wc_status` AS b ON a.`sales_invoice_status` = b.`wc_status_id` WHERE 1 GROUP BY b.`wc_status_name`";
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
	function getWebsiteFilteration($pending=false){
		$condition = "";
		if($pending){
			$condition = "WHERE a.`sales_invoice_is_cancelled` = '0' AND a.`sales_invoice_status` != '1' AND a.`sales_invoice_is_paid` = '1'";	
		}
		$sql = "SELECT a.`sales_invoice_store_id`, b.`store_name`, b.`store_title`, b.`store_icon`, COUNT(a.`sales_invoice_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`sales_invoice_store_id` = b.`store_id` $condition GROUP BY `sales_invoice_store_id` ORDER BY `store_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$worder_array = array();
		if($dbc->db_num_rows()>0)
		{			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$worder_array[] = $row;				
			}
		}
		return $worder_array;
	}
	function getCreatorFilteration($pending = false){
		$condition = "";
		if($pending){
			$condition = "WHERE a.`sales_invoice_is_cancelled` = '0' AND a.`sales_invoice_status` != '1' AND a.`sales_invoice_is_paid` = '1'";	
		}
		$sql = "SELECT b.`user_id`, b.`user_fname`, b.`user_lname`, b.`user_image`, COUNT(a.`sales_invoice_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_system_user` AS b on a.`sales_invoice_creator` = b.`user_id` $condition GROUP BY a.`sales_invoice_creator` ORDER BY b.`user_fname`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$row['user_image'] =	getResizeImage($row['user_image'],50);
		$data[] = $row;
		}
		return $data;
	}
	function getStatusFilteration($pending = false){
		$condition = "";
		if($pending){
			$condition = "WHERE a.`sales_invoice_is_cancelled` = '0' AND a.`sales_invoice_status` != '1' AND a.`sales_invoice_is_paid` = '1'";	
		}
		$sql = "SELECT b.`wc_status_id`, b.`wc_status_name`, COUNT(a.`sales_invoice_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_wc_status` AS b on a.`sales_invoice_status` = b.`wc_status_id` $condition GROUP BY b.`wc_status_name` ORDER BY b.`wc_status_name`";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$data = array();		
		while($row = $dbc->db_fetch_assoc(true)){
		$data[] = $row;
		}
		return $data;
	}
	
	static function todaysInvoiceActivity(){
		$sql = "
		SELECT COUNT(`sales_invoice_id`) AS records, 'Picked' AS label, '#FF9900' AS color  FROM `app_sales_invoice` WHERE DATE_FORMAT(`sales_invoice_picking_time`,'%Y-%m-%d') = CURRENT_DATE UNION 
		SELECT COUNT(`sales_invoice_id`) AS records, 'Packed' AS label, '#3399FF' AS color FROM `app_sales_invoice` WHERE DATE_FORMAT(`sales_invoice_packing_time`,'%Y-%m-%d') = CURRENT_DATE  UNION 
		SELECT COUNT(`sales_invoice_id`) AS records, 'Processed' AS label, '#8BC34A' AS color FROM `app_sales_invoice` WHERE DATE_FORMAT(`sales_invoice_process_time`,'%Y-%m-%d') = CURRENT_DATE UNION 
		SELECT COUNT(`sales_invoice_id`) AS records, 'Canceled' AS label, '#FF3300' AS color FROM `app_sales_invoice` WHERE DATE_FORMAT(`sales_invoice_cancel_time`,'%Y-%m-%d') = CURRENT_DATE";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$results_array = array();
		if($dbc->db_num_rows()>0)
		{					
			while($row = $dbc->db_fetch_assoc(true))
			{
				$results_array[] = $row;	
			}
		}
		return $results_array;
	}
	
}

class SalesInvoiceProduct extends DB{
	public $id;
	public $key_id		=	"sipd_id";
	public $table_name	=	"app_sales_invoice_product_detail";
	public $sales_invoice_id	=	"sipd_invoice_id";
	function __construct($sipd_id=0){
				
		$this->id	=	$sipd_id;
	}
	
	function getDetails(){
		$sql = "SELECT a.*, b.`product_name`, b.`product_hardware_id` FROM  `".$this->table_name."` AS a INNER JOIN `app_sales_products` AS b ON a.`sipd_product_id` = b.`product_id` WHERE a.`sipd_id` = '".$this->id."'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows($result))		
			return $dbc->db_fetch_assoc(true);
		else
			return false;
	}
	
	function getProductList($sipd_invoice_id){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `".$this->sales_invoice_id."` = '$sipd_invoice_id'";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$image_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$image_array[] = $row;
			}
			return $image_array;
		}
		return NULL;
	}
	
}

class SalesMedia extends DB{
	public $id;
	public $key_id		=	"sales_image_id";
	public $table_name	=	"app_sales_record_images";
	public $status		=	"sales_image_status";	
	public $sales_image_invoice_id	=	"sales_image_invoice_id";
	function __construct($sales_image_id=0){
				
		$this->id	=	$sales_image_id;
	}
	
	function getImageList($sales_image_invoice_id){
		$sql = "SELECT * FROM `".$this->table_name."` WHERE `".$this->sales_image_invoice_id."` = '$sales_image_invoice_id' AND `".$this->status."` = '1' ";
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$image_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$image_array[] = $row;
			}
			return $image_array;
		}
		return NULL;
	}
	
}



?>