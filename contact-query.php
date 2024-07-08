<?php include("setup.php"); ?>
<?php
$DATA = sanitizePostData($_POST);
Log::Text(json_encode($DATA),'QUERY-CONTACT');
Log::Text(json_encode($_POST),'QUERY-CONTACT');
Log::Text(json_encode($_GET),'QUERY-CONTACT');
// $websiteOrder =  new WebsiteOrder();
// $web_order_id = $websiteOrder->insert(array(
// 				"web_order_customer_id" => $web_order_customer_id,
// 				"web_order_address_id" => $web_order_address_id,
// 				"web_order_total_amount" => $web_order_total_amount,
// 				"web_order_total_shipping" => $web_order_total_shipping,
// 				"web_order_total_quantity" => $web_order_total_quantity,
// 				"web_order_is_paid"	=>$web_order_is_paid,
// 				"web_order_currency" => $web_order_currency,
// 				"web_order_payment_method" => $web_order_payment_method,
// 				"web_order_website_id" => $web_order_website_id,
// 				"web_order_number" => $web_order_number,
// 				"web_order_created_date" => $created_date == NULL ? "NOW()" : $created_date,
// 				"web_order_status" => $web_order_status
// 				)
// 			);
?>