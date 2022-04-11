<?php
class EventList extends DB{
		
	public function getList($start, $end, $timeZone)
	{
		global $app;
		$sql="SELECT '#01579B' as color, 'viewcomplaint' as urlview, a.`complaint_id` as id, CONCAT('Complaint: ', a.`complaint_ticket_number`) as title, CONCAT('Description: ', a.`complaint_description`, '<br/>', 'Details: ', a.`complaint_problem_details`) as description, a.`complaint_created_date` as start, a.`complaint_created_date` as end FROM `app_complaint_record` as a WHERE 1 AND a.`complaint_created_date`>='$start' AND a.`complaint_created_date`<='$end' 
		UNION 
		SELECT '#3E2723' as color, 'updatecollection' as urlview, a.`wc_id` as id, CONCAT('Collection: ', a.`wc_code`) as title, CONCAT('Collection Manager: ', COALESCE(h.`user_fname`,'Not Assigned'), ' ', COALESCE(h.`user_lname`, ''), '<br/>Consignee Name: ', COALESCE(b.`customer_fname`, 'Not Available'), ' ', COALESCE(b.`customer_lname`,''), '<br/>Collection Address: ', c.`customer_address_street_number`, ', ', c.`customer_address_route`, ', ', c.`customer_address_locality`, ', ', c.`customer_address_administrative_area`, ', ', c.`customer_address_country`, ' ', c.`customer_address_postcode`) as description, a.`wc_created_date` as start, a.`wc_created_date` as end FROM `app_wc_record` as a LEFT JOIN `app_customer` as b ON a.`wc_customer_id`= b.`customer_id` LEFT JOIN `app_customer_address` as c ON a.`wc_customer_address_id`= c.`customer_address_id` LEFT JOIN `app_system_user` as h ON a.`wc_manager_id`= h.`user_id` WHERE 1 AND a.`wc_created_date` >= '$start' AND a.`wc_created_date`<='$end'
		
		UNION
		
		SELECT '#009688' as color, 'updatecustomer' as urlview, a.`customer_id` as id,  CONCAT('Customer: ', a.`customer_fname`, ' ', a.`customer_lname`, ' (',b.`customer_type_name`,')') as title, CONCAT( 'Email: ',a.`customer_email`, ' &nbsp; Mobile: ', a.`customer_phone`,'<br/>Address: ',GROUP_CONCAT(CONCAT(`customer_address_street_number`, ' ', `customer_address_route` , ' ', `customer_address_locality`, ' ', `customer_address_administrative_area`, ' ', `customer_address_country`, ' ', `customer_address_postcode`) separator '<br/>')) as description, a.`customer_created_date` as start, a.`customer_created_date` as end FROM `app_customer` as a 
 INNER JOIN `app_customer_type` as b ON a.`customer_type_id`=b.`customer_type_id` 
 INNER JOIN `app_customer_address` as c ON a.`customer_id`=c.`customer_id` 
 WHERE a.`customer_created_date`>='$start' AND a.`customer_created_date`<='$end' GROUP BY a.`customer_id`
 
 	UNION 
	
	SELECT '#C51162' as color, 'updateemployee' as urlview, a.`user_id` as id, CONCAT('Employee: ',a.`user_fname`, ' ', a.`user_lname`, '(',b.`user_type_name`,')') as title, CONCAT('Email: ', a.`user_email`, ' Mobile: ', a.`user_phone`, '<br/>Address: ', a.`user_address`) as description, a.`user_created_date` as start, a.`user_created_date` as end FROM `app_system_user` as a INNER JOIN `app_user_type` as b ON a.`user_type_id` = b.`user_type_id` WHERE a.`user_created_date`>='$start' AND a.`user_created_date`<='$end'
	
	UNION
	
	SELECT '#e67e22 ' as color, 'salesinvoice' as urlview, a.`sales_invoice_id` as id, CONCAT('Sales: ', a.`sales_invoice_number`) as title, CONCAT('Employee  : ', COALESCE(h.`user_fname`,'Not Assigned'), ' Created invoice for Customer : ', COALESCE(b.`customer_fname`, 'Not Available'), ' ', COALESCE(b.`customer_lname`,''), '<br/>Collection Address: ', c.`customer_address_street_number`, ', ', c.`customer_address_route`, ', ', c.`customer_address_locality`, ', ', c.`customer_address_administrative_area`, ', ', c.`customer_address_country`, ' ', c.`customer_address_postcode`) as  description, a.`sales_invoice_created_date` as start, a.`sales_invoice_created_date` as end FROM `app_sales_invoice` as a LEFT JOIN `app_customer` as b ON a.`sales_invoice_customer_id`= b.`customer_id` LEFT JOIN `app_customer_address` as c ON a.`sales_invoice_customer_address_id`= c.`customer_address_id` LEFT JOIN `app_system_user` as h ON a.`sales_invoice_creator`= h.`user_id` WHERE 1 AND a.`sales_invoice_created_date` >= '$start' AND a.`sales_invoice_created_date`<='$end'
	
	
	UNION 
	
	SELECT '#0798f0' as color, 'viewweborder' as urlview, a.`web_order_id` as id, CONCAT('New Website Order #', a.`web_order_number` ) as title, CONCAT('Amount: ', a.`web_order_total_amount`, ' Payment: ', a.`web_order_payment_method` , ' placed by ', COALESCE(b.`customer_fname`, 'Not Available'), ' ', COALESCE(b.`customer_lname`,'')) as  description, a.`web_order_created_date` as start, a.`web_order_created_date` as end FROM `app_website_order` as a LEFT JOIN `app_customer` as b ON a.`web_order_customer_id`= b.`customer_id` WHERE 1 AND a.`web_order_created_date` >= '$start' AND a.`web_order_created_date`<='$end'
	
 ";	
 //echo $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		$outputArray = array();	
		while($row = $dbc->db_fetch_assoc(true)){					
			$row['start'] = TZDate($row['start'], $timeZone);
			$row['end'] = TZDate($row['end'], $timeZone);
			$row['url'] = $app->basePath($row['urlview']."/".$row['id']);
			$outputArray[] = $row;	
		} 
		return json_encode($outputArray);	
	}
}
?>