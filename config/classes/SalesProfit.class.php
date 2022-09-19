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
		$sql = "SELECT a.`web_order_website_id`, b.`store_name`, b.`store_title`, b.`store_icon`, COUNT(a.`web_order_id`) as record FROM `".$this->table_name."` AS a INNER JOIN `app_store_master` AS b ON a.`web_order_website_id` = b.`store_id` GROUP BY `web_order_website_id` ORDER BY `store_name`";
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
			$condition = "a.`$column` >= (CURDATE() - INTERVAL 7 DAY)";
		}
		elseif($interval == "15"){
			$condition = "a.`$column` >= (CURDATE() - INTERVAL 15 DAY)";
		}
		elseif($interval == "30"){
			$condition = "a.`$column` >= (CURDATE() - INTERVAL 30 DAY)";
		}
		elseif($interval == "cm"){
			$condition = "(MONTH(a.`$column`) = MONTH(CURDATE()) AND YEAR(a.`$column`) = YEAR(CURDATE()))";
		}
		elseif($interval == "lm"){
			$condition = "MONTH(a.`$column`) = MONTH((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 1 MONTH) AND YEAR(a.`$column`) = YEAR((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 1 MONTH)";
		}
		elseif($interval == "3m"){
			$condition = "a.`$column` >= ((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 3 MONTH)";
		}
		elseif($interval == "6m"){
			$condition = "a.`$column` >= ((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 6 MONTH)";
		}
		elseif($interval == "1y"){
			$condition = "a.`$column` >= ((DATE_ADD(CURRENT_DATE,INTERVAL - DAY(CURRENT_DATE)+1 DAY)) - INTERVAL 1 YEAR)";
		}
		elseif($interval == "cy"){			
			$condition = "YEAR(a.`$column`) = YEAR(CURRENT_DATE)";
		}
		elseif($interval == "ly"){
			$condition = "YEAR(a.`$column`) = YEAR((DATE_ADD(CURRENT_DATE,INTERVAL -DAY(CURRENT_DATE)+1 DAY))- INTERVAL 1 YEAR)";
		}
		elseif($interval == "custom"){
			$condition .= $from_date ? " a.`$column` > '$from_date' " : "";
			$condition .= $to_date ? ($condition ? (" AND a.`$column` <= DATE_ADD('$to_date', INTERVAL +1 DAY) "):"") : "";
		}
		return $condition;
		
	}
	function getProfitSummary($interval, $from_date=null, $to_date=null){
		
		$ordercondition = $this->getReportCondition('web_order_created_date', $interval, $from_date, $to_date);
		
		$invoicecondition = $this->getReportCondition('sales_invoice_date', $interval, $from_date, $to_date);
			
		$sql = "SELECT a.`web_order_currency` AS currency, 'Web order' AS source, a.`web_order_created_date` AS invoice_date, a.`web_order_id` as id, a.`web_order_number` AS order_number, p.`wo_product_sku` as product_sku, p.`wo_product_name` as product_name, p.`wo_product_purchase_amount` AS purchase_price, p.`wo_product_sell_price` AS sell_price, p.`wo_product_shipping_price` AS shipping_intake, p.`wo_product_options` as options, p.`wo_product_purchase_amount` AS purchase_amount, p.`wo_product_shipping_price` as shipping_paid FROM `app_website_order` AS a INNER JOIN `app_website_order_product` AS p ON ( p.`wo_web_order_id` = a.`web_order_id` ) WHERE a.web_order_status = '1' AND $ordercondition 
		
		UNION 
		
		SELECT a.`sales_invoice_currency` AS currency, 'Sales invoice' AS source, a.`sales_invoice_date` AS invoice_date, a.`sales_invoice_id` as id, a.`sales_invoice_number` AS order_number, p.`sipd_product_sku` as product_sku, i.`product_name` as product_name, p.`sipd_product_price` AS purchase_price, p.`sipd_product_price` AS sell_price, 0 AS shipping_intake, '' as options, p.`sipd_purchase_amount` as purchase_base_amount, 0 as shipping_paid FROM `app_sales_invoice` AS a INNER JOIN `app_sales_invoice_product_detail` AS p ON ( p.`sipd_invoice_id` = a.`sales_invoice_id` ) INNER JOIN `app_sales_products` AS i ON ( i.`product_id` = p.`sipd_product_id` ) WHERE a.sales_invoice_status = '1' AND $invoicecondition 
		
		ORDER BY invoice_date DESC, id DESC";
		
		$dbc 	= 	new DB();
		$result	=	$dbc->db_query($sql);
		if($dbc->db_num_rows()>0)
		{
			$record_array = array();			
			while($row = $dbc->db_fetch_assoc(true))
			{
				$row['base_purchase_price'] = getBaseAmount($row['purchase_price'], $row['currency']);
				$row['base_sell_price'] = getBaseAmount($row['sell_price'], $row['currency']);
				$row['base_shipping_paid'] = getBaseAmount($row['shipping_paid'], $row['currency']);
				$row['base_profit'] =  round($row['base_sell_price'] - ($row['base_purchase_price'] + $row['base_shipping_paid']), 2);
				$record_array[] = $row;
			}
			$products = round(array_sum(array_column($record_array, 'order_number')));
			$purchase = round(array_sum(array_column($record_array, 'base_purchase_price')),2);
			$sell = round(array_sum(array_column($record_array, 'base_sell_price')),2);
			$profit = round(array_sum(array_column($record_array, 'base_profit')),2);
			$shipment = round(array_sum(array_column($record_array, 'base_shipping_paid')),2);
			return array(
				'records' => $record_array,
				'recordData' => array(
					'orders' => $orders,
					'purchase' => '&pound;'.number_format($purchase,2),
					'sell' => '&pound;'.number_format($sell,2),
					'profit' => '&pound;'.number_format($profit,2),
					'shipment' => '&pound;'.number_format($shipment,2)
				)
			);
		}
		else
			return NULL;
	}
	
}

?>