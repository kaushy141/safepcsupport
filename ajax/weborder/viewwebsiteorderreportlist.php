<?php
	$data  = sanitizePostData($_POST);
	extract($data);
	$salesMapColumnArray = array(
		'web_order_website_id' => 'sales_invoice_store_id',
		'web_order_status' => 'sales_invoice_status',
		'web_order_payment_method' => NULL,
		'web_order_currency' => 'sales_invoice_currency'
	);
	
	
	$orderTotalConditionArray = array();
	$salesTotalConditionArray = array();
	//print_r($filter);
	if($filter != NULL && count($filter)){
		foreach($filter as $field=>$values){
			$orderConditionArray = array();
			$salesConditionArray = array();
			if($field == "duration"){
				if($values == "today"){
				$orderConditionArray[] = " DATE(a.web_order_created_date) = CURRENT_DATE ";
				$salesConditionArray[] = " DATE(a.sales_invoice_created_date) = CURRENT_DATE ";
				}
				elseif($values == "month"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ";
				}
				elseif($values == "quarter"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) ";
				}
				elseif($values == "halfyear"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) ";
				}
				elseif($values == "year"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ";
				}
				elseif($values == "custom"){
				$orderConditionArray[] = " a.web_order_created_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
				$salesConditionArray[] = " a.sales_invoice_created_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";
				}
			}
			elseif($field == "web_order_date_range"){
				if(isset($values['from']) && $values['from'] != ""){
					$orderConditionArray[] = " a.web_order_created_date >= '".$values['from']."'";
					$salesConditionArray[] = " a.sales_invoice_created_date >= '".$values['from']."'";
				}
				if(isset($values['to']) && $values['to'] != ""){
					$orderConditionArray[] = " a.web_order_created_date <= '".$values['to']."'";
					$salesConditionArray[] = " a.sales_invoice_created_date <= '".$values['to']."'";
				}
			}
			else
			{
				if(is_array($values)){
					foreach($values as $_val){
						$orderConditionArray[] = array("a.".$field, "=", sanitizePostData($_val));
						if(isset($salesMapColumnArray[$field]))
						$salesConditionArray[] = array("a.".$salesMapColumnArray[$field], "=", sanitizePostData($_val));
					}
				}
				else{
					$orderConditionArray[] = array("a.".$field, "=", sanitizePostData($values));
					if(isset($salesMapColumnArray[$field]))
					$salesConditionArray[] = array("a.".$salesMapColumnArray[$field], "=", sanitizePostData($values));
				}
				
				
			}
			$orderTotalConditionArray[] = $orderConditionArray;	
			$salesTotalConditionArray[] = $salesConditionArray;
		}
	}
	$websiteOrder = new WebsiteOrder();
	$websiteOrder->condition = $orderTotalConditionArray;
	$reportData['website_order'] = $websiteOrder->getWebsiteOrderStatistics($websiteOrder->getCondition(), $filter['web_order_currency'] ?: 'GBP');
	
	$salesInvoice = new SalesInvoice();
	$salesInvoice->condition = $salesTotalConditionArray;
	
	$reportData['website_sales'] = $salesInvoice->getSalesInvoiceStatistics($salesInvoice->getCondition(), $filter['web_order_currency'] ?: 'GBP');
	
	
	echo json_encode(array("200",  "success|Weborder report loaded", $reportData, $filter['web_order_currency'] ?: 'GBP'));
	


?>