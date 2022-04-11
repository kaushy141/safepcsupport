<?php 
include("../../setup.php");
ini_set('display_errors', 1);
error_reporting(0);
extract(App::exploreApiCredentilas('BackMarket'));

$serviceUrl = "https://www.backmarket.fr/ws/orders/?";

if(isset($_GET['date_creation']) && $_GET['date_creation'] !=''){
	$serviceUrl .= "date_creation=".urlencode(gmdate('Y-m-d H:i:s', strtotime($_GET['date_creation'])));
}
else{
	$serviceUrl .= "date_creation=".urlencode(gmdate("Y-m-d H:i:s", strtotime(' -1 day')));
}
$serviceUrl .= "&page-size=5";

try {	
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $serviceUrl);
	curl_setopt( $ch, CURLOPT_POST, false );
	curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
													"Content-Type: application/json",
													"Accept: application/json",
													"Accept-Language: en-gb",
													"Authorization: Basic $Token"
												));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:90.0) Gecko/20100101 Firefox/90.0");
	curl_setopt( $ch, CURLINFO_HEADER_OUT, true);
	$response = curl_exec($ch);
	
	$responseArray  = json_decode($response, true);	
	
	if(isset($responseArray['count']) && $responseArray['count'] > 0){
		processBackOrder($responseArray['results']);
	}
	else{
		echo date('Y-m-d H:i:s').": Data fetch Error : ".$response."\n";
	}
	
} catch ( Exception $ex ) {
	echo date('Y-m-d H:i:s').": Data fetch Exception : ".$ex."\n";
}


function processBackOrder($orders)
{
	$processedOrder = array();
	$aouth = md5('www.backmarket.co.uk');
	$icon 						= getFcmMessageIcon($aouth);
	$web_order_website_id 		= getWebsiteIdByAouth($aouth);
	$web_order_website_id 		= $web_order_website_id ? $web_order_website_id : 15;						
	
	foreach($orders as $order)
	{
		$order = sanitizePostData($order);
		$web_order_number 			= isset($order['order_id'])	? getData($order['order_id'])		:	'N/A';
		
		$websiteOrder =  new WebsiteOrder();
		$isOrderExist = $websiteOrder->isOrderExist($web_order_number, $web_order_website_id);
		$web_order_currency 		= isset($order['currency'])	? getData($order['currency'])		:	'XXX';	
		if(!$isOrderExist)
		{
			$products = $order['orderlines'];
			$productData = array();
			foreach($products as $_product)
			{
				$productData[] = array(
					"wo_product_name" 			=> getData($_product['product']),
					"wo_product_sku" 			=> getData($_product['listing']),
					"wo_product_quantity" 		=> getData($_product['quantity']),
					"wo_product_sell_price" 	=> getData($_product['price']),
					"wo_product_sell_base_price" => getBaseAmount(getData($_product['price']), $web_order_currency),
					"wo_product_image"			=> BACKMARKET_PRODUCT_IMAGE,
					"wo_product_premium"		=> 0,
					"wo_process_code"			=> ''
				);
				
			}
			if(count($productData))
			{							
				$shippingAddress = $order['shipping_address'];
				$customer_fname = isset($shippingAddress['first_name']) ? getData($shippingAddress['first_name']) :	'N/A';
				$customer_lname = isset($shippingAddress['last_name']) ? getData($shippingAddress['last_name']) :	'N/A';
				$customer_email = isset($shippingAddress['email']) ? getData($shippingAddress['email']) :	'N/A';
				$customer_phone = isset($shippingAddress['phone']) ? getData($shippingAddress['phone']) :	'';
				$customer_company = isset($shippingAddress['company']) ? getData($shippingAddress['company']) :	'';
				
				$customer_address_postcode = $shippingAddress['postal_code'];
				$customer_address_street_number = isset($shippingAddress['street']) ? getData($shippingAddress['street']) : "N/A";
				$customer_address_route = isset($shippingAddress['street2']) ? getData($shippingAddress['street2']) : "N/A";
				$customer_address_locality = isset($shippingAddress['city']) ? getData($shippingAddress['city']) : "N/A";
				$customer_address_administrative_area = isset($shippingAddress['city']) ? getData($shippingAddress['city']) : "N/A";
				$customer_address_country = Country::getValueByParameter('name', 'iso2', getData($shippingAddress['country']));
				$customer_address_geo_location = "";
				
				$web_order_total_amount 	= isset($order['price'])		? getData($order['price'])		:	0;
				
				$web_order_total_shipping   = isset($order['shipping_price'])	? getData($order['shipping_price'])		:	0;
				$web_order_payment_method 	= isset($order['payment_method'])	? getData($order['payment_method'])		:	'N/A';
				
				$web_order_is_paid 			= 'Yes';										
				$web_order_total_quantity 	= count($productData);				
				$web_order_status = STATUS_PENDING;
				
				$web_order_invoice_image = isset($order['delivery_note']) ? getData($order['delivery_note'])		:	'NULL';
				$web_order_invoice_date  = isset($order['date_shipping']) ? date('Y-m-d H:i:s', strtotime($order['date_shipping'])) : NULL;	
				
				$web_order_created_date = date('Y-m-d H:i:s', strtotime($order['date_creation']));	
				
				$customer_email = strtolower($customer_email);
				$Customer        = new Customer(0);
				$web_order_customer_id  = $Customer->isEmailExists($customer_email);
				
				if (!$web_order_customer_id) {
					$customer_image  = DEFAULT_USER_IMAGE;
					$customer_type_id = 7;
					$customer_status = 1;
					$customer_password    = gePassword();
					$customer_is_mobile_verified = 0;
					$customer_is_email_verified  = 0;
					$customer_created_by = ADMIN_USER;
					$customer_address_status     = 1;
					
					$customer_code   = $Customer->getNewCode($customer_email, $customer_fname . $customer_lname);
					$web_order_customer_id  = $Customer->add($customer_code, $customer_fname, $customer_lname, $customer_email, $customer_phone, $customer_company, $customer_type_id, $customer_image, $customer_status, $customer_password, $customer_created_by, $customer_is_mobile_verified, $customer_is_email_verified);
				} 				
				
				$CustomerAddress = new CustomerAddress(0);
					$web_order_address_id = $CustomerAddress->isCustomerAddressExists($web_order_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country);
					if (!$web_order_address_id)
						$web_order_address_id = $CustomerAddress->add($web_order_customer_id, $customer_address_street_number, $customer_address_route, $customer_address_locality, $customer_address_administrative_area, $customer_address_country, $customer_address_postcode, $customer_address_geo_location, $customer_address_status);								
				
				
				$web_order_id = $websiteOrder->insert(array(
					"web_order_customer_id" => $web_order_customer_id,
					"web_order_address_id" => $web_order_address_id,
					"web_order_total_amount" => $web_order_total_amount,
					"web_order_total_shipping" => $web_order_total_shipping,
					"web_order_total_quantity" => $web_order_total_quantity,
					"web_order_is_paid"	=>$web_order_is_paid,
					"web_order_currency" => $web_order_currency,
					"web_order_payment_method" => $web_order_payment_method,
					"web_order_website_id" => $web_order_website_id,
					"web_order_number" => $web_order_number,
					"web_order_created_date" => $web_order_created_date,
					"web_order_status" => $web_order_status,
					)
				);
				/*
				if($web_order_invoice_date != NULL){
					$websiteOrder->update(array(
						"web_order_invoice_image" => $web_order_invoice_image,
						"web_order_invoice_date" => $web_order_invoice_date
					));
				}			
				*/
				foreach($productData as $product)
				{
					$websiteOrderProduct = new WebsiteOrderProduct();
					$product['wo_web_order_id']	= 	$web_order_id;
					for($i=0; $i< $product['wo_product_quantity']; $i++)
					{								
						$websiteOrderProduct->insert($product);
					}
				}
				$successImportOrder++;
			}
			$processedOrder[] = "Order #$web_order_number fetched";
		}
	}
	echo date('Y-m-d H:i:s').": ".count($processedOrder)."/".count($orders)." Processed". (count($processedOrder) ? implode("\n", $processedOrder) : "")."\n";
}

?>
