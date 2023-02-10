<?php
class SalesProfit extends DB{
	function __construct(){
				
	}	
		
	function getCountryFilteration(){
		$sql = "SELECT b.`customer_address_country`, COUNT(a.`web_order_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_customer_address` AS b ON a.`web_order_address_id` = b.`customer_address_id` GROUP BY b.`customer_address_country` ORDER BY b.`customer_address_country`";
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
	
	function getWebsiteFilteration(){
		$sql = "SELECT b.`store_id`, b.`store_name`, b.`store_official_name`, b.`store_title`, b.`store_icon` FROM `app_website_order` AS a INNER JOIN `app_store_master` AS b ON a.`web_order_website_id` = b.`store_id`
			UNION
			SELECT b.`store_id`, b.`store_name`, b.`store_official_name`, b.`store_title`, b.`store_icon` FROM `app_sales_invoice` AS a INNER JOIN `app_store_master` AS b ON a.`sales_invoice_store_id` = b.`store_id`
			 GROUP BY store_id ORDER BY `store_name`";
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
		
		
	function getReportCondition($column, $interval, $from_date = null, $to_date = null){
		$condition = "";
		if($interval == "7"){
			$condition = " AND a.`$column` >= (CURDATE() - INTERVAL 7 DAY)";
		}
		elseif($interval == "15"){
			$condition = " AND a.`$column` >= (CURDATE() - INTERVAL 15 DAY)";
		}
		elseif($interval == "30"){
			$condition = " AND a.`$column` >= (CURDATE() - INTERVAL 30 DAY)";
		}
		elseif($interval == "cm"){
			$condition = " AND (MONTH(a.`$column`) = MONTH(CURDATE()) AND YEAR(a.`$column`) = YEAR(CURDATE()))";
		}
		elseif($interval == "lm"){
			$condition = " AND MONTH(a.`$column`) = MONTH((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 1 MONTH) AND YEAR(a.`$column`) = YEAR((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 1 MONTH)";
		}
		elseif($interval == "3m"){
			$condition = " AND a.`$column` >= ((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 3 MONTH)";
		}
		elseif($interval == "6m"){
			$condition = " AND a.`$column` >= ((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 6 MONTH)";
		}
		elseif($interval == "1y"){
			$condition = " AND a.`$column` >= ((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 1 YEAR)";
		}
		elseif($interval == "cy"){			
			$condition = " AND YEAR(a.`$column`) = YEAR(CURRENT_DATE)";
		}
		elseif($interval == "ly"){
			$condition = " AND YEAR(a.`$column`) = YEAR((DATE_ADD(CURRENT_DATE,INTERVAL -DAY(CURRENT_DATE)+1 DAY))- INTERVAL 1 YEAR)";
		}
		elseif($interval == "custom"){
			$condition .= $from_date ? " AND a.`$column` > '$from_date' " : "";
			$condition .= $to_date ? " AND a.`$column` <= DATE_ADD('$to_date', INTERVAL +1 DAY) " : "";
		}
		elseif($interval == "IN"){ 
			$condition .= " AND a.`$column` IN (".(is_array($from_date) ? implode(',', $from_date) : $from_date).")";
		}
		else{
			$condition .= " AND a.`$column` $interval '$from_date'";
		}
		return $condition;
		
	}
	function getProfitSummary($interval, $from_date=null, $to_date=null, $currency="GBP", $website=0){
		
		$ordercondition = $this->getReportCondition('web_order_created_date', $interval, $from_date, $to_date);		
		$invoicecondition = $this->getReportCondition('sales_invoice_date', $interval, $from_date, $to_date);
		
		if($website){
			$ordercondition .= ($this->getReportCondition('web_order_website_id', "IN", $website));		
			$invoicecondition .= ($this->getReportCondition('sales_invoice_store_id', "IN", $website));
		}
			
		$sql = "SELECT a.`web_order_currency` AS currency, 'Web order' AS source, s.`store_name` AS store, CONCAT(c.`customer_fname`, ' ', c.`customer_lname`) AS customer, a.`web_order_created_date` AS invoice_date, a.`web_order_id` as id, a.`web_order_number` AS order_number, p.`wo_product_sku` as product_sku, p.`wo_product_name` as product_name, p.`wo_product_purchase_amount` AS purchase_price, p.`wo_product_sell_price` AS sell_price, p.`wo_product_shipping_price` AS shipping_intake, p.`wo_product_options` as options, p.`wo_product_purchase_amount` AS purchase_amount, p.`wo_product_shipping_price` as shipping_paid, a.`web_order_payment_method` as payment FROM `app_website_order` AS a 
		INNER JOIN `app_website_order_product` AS p ON ( p.`wo_web_order_id` = a.`web_order_id` ) 
		INNER JOIN `app_store_master` AS s ON a.`web_order_website_id` = s.`store_id` 
		INNER JOIN `app_customer` AS c ON ( c.`customer_id` = a.`web_order_customer_id` )  
		WHERE a.web_order_status = '1' $ordercondition 
		
		UNION 
		
		SELECT a.`sales_invoice_currency` AS currency, 'Sales invoice' AS source, s.`store_name` as store, CONCAT(c.`customer_fname`, ' ', c.`customer_lname`) AS customer, a.`sales_invoice_date` AS invoice_date, a.`sales_invoice_id` as id, a.`sales_invoice_number` AS order_number, p.`sipd_product_sku` as product_sku, i.`product_name` as product_name, p.`sipd_product_price` AS purchase_price, p.`sipd_product_price` AS sell_price, 0 AS shipping_intake, '' as options, p.`sipd_purchase_amount` as purchase_base_amount, 0 as shipping_paid, a.`sales_invoice_payment_mode` as payment FROM `app_sales_invoice` AS a 
		INNER JOIN `app_sales_invoice_product_detail` AS p ON ( p.`sipd_invoice_id` = a.`sales_invoice_id` ) 
		INNER JOIN `app_sales_products` AS i ON ( i.`product_id` = p.`sipd_product_id` ) 
		INNER JOIN `app_store_master` AS s ON a.`sales_invoice_store_id` = s.`store_id` 
		INNER JOIN `app_customer` AS c ON ( c.`customer_id` = a.`sales_invoice_customer_id` )
		WHERE a.sales_invoice_status = '1' $invoicecondition 
		
		ORDER BY invoice_date DESC, id DESC";
		$_SESSION['REPORT']['SQL'] = $sql;
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$record_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['base_purchase_price'] = getBaseAmount($row['purchase_price'], $row['currency'], $currency);
				$row['base_sell_price'] = getBaseAmount($row['sell_price'], $row['currency'], $currency);
				$row['base_shipping_paid'] = getBaseAmount($row['shipping_paid'], $row['currency'], $currency);
				$row['base_profit'] =  round($row['base_sell_price'] - ($row['base_purchase_price'] + $row['base_shipping_paid']), 2);
				$row['report_currency'] = getCurrencySymbol($currency);
				$record_array[] = $row;
			}
			
			$products = round(array_sum(array_column($record_array, 'order_number')));
			$purchase = round(array_sum(array_column($record_array, 'base_purchase_price')),2);
			$sell = round(array_sum(array_column($record_array, 'base_sell_price')),2);
			$profit = round(array_sum(array_column($record_array, 'base_profit')),2);
			$shipment = round(array_sum(array_column($record_array, 'base_shipping_paid')),2);
			return array(
				//'sql' => $sql,
				'records' => $record_array,
				'recordData' => array(
					'orders' => $orders,
					'purchase' => getCurrencySymbol($currency).number_format($purchase,2),
					'sell' => getCurrencySymbol($currency).number_format($sell,2),
					'profit' => getCurrencySymbol($currency).number_format($profit,2),
					'shipment' => getCurrencySymbol($currency).number_format($shipment,2)
				)
			);
		}
		else
			return NULL;
	}
	
}

?>