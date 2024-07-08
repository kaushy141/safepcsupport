<?php

function httpRequest($url, $post = '', $header = null, &$status = null) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_SSL_VERIFYPEER => true, CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);
    if ($post) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    if ($header) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $out = curl_exec($ch);
    
    //$info = curl_getinfo($ch);
    // Print the information
    //print_r($info);

    if (curl_errno($ch)) exit('Error: ' . curl_error($ch));
    if ($status !== null) $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return $out;
}

function getAccessToken() {
    
    // Return existing access token if exists and not expired
    if (file_exists(TOKEN_CACHE)) {
        $file = file_get_contents(TOKEN_CACHE);
        $json = json_decode($file, true);
        if ($json && !empty($json['token'])) {
            if (!empty($json['expires']) && time() < $json['expires']) {
                return $json['token'];
            }
        }
    }
    
    // Otherwise get new access token
    $post = 'grant_type=refresh_token&refresh_token=' . APP_REFRESH_TOKEN
          . '&client_id=' . APP_LWA_ID . '&client_secret=' . APP_LWA_SECRET;
    $url = 'https://api.amazon.com/auth/o2/token';
    $response = httpRequest($url, $post, ['user-agent:' . USER_AGENT]);
    
    // Validate new access token response
    if (strpos($response, '{"access_token":') !== 0) {
        exit('Error: Access token response was bad: ' . $response);
    }
    if (strpos($response, 'expires_in') === false) {
        exit('Error: No "expires_in" found in response: ' . $response);
    }
    $json = json_decode($response, true);
    if (!$json || empty($json['access_token']) || empty($json['expires_in'])) {
        exit('Error: Access token JSON decode failure: ' . $response);
    }
    
    // Cache access token with an expires timestamp
    $cacheData = json_encode([
        'token' => $json['access_token'],
        'expires' => time() + $json['expires_in'],
    ]);
    file_put_contents(TOKEN_CACHE, $cacheData);
    
    // Return access token
    return $json['access_token'];
}

function amazonRequest($method, $path, $qs = '', $post = '') {
    
    // Get access token
    $accessToken = getAccessToken();
    
    // Two formats for date used throughout
    $date = gmdate('Ymd\THis\Z');
    $ymd = gmdate('Ymd');
    
    // Build a canonical request. This is just a highly-structured and
    // ordered version of the request you will be making. Each part is
    // newline-separated. The number of headers is variable, but this
    // uses four headers. Headers must be in alphabetical order.
    $canonicalRequest = $method . "\n" // HTTP method
    . $path . "\n" //  Path component of the URL
    . $qs . "\n" // Query string component of the URL (without '?')
    . 'host:' . HOST . "\n" // Header
    . 'user-agent:' . USER_AGENT . "\n" // Header
    . 'x-amz-access-token:' . $accessToken . "\n" // Header
    . 'x-amz-date:' . $date . "\n" // Header
    . "\n" // A newline is needed here after the headers
    . 'host;user-agent;x-amz-access-token;x-amz-date' . "\n" // Header names
    . hash('sha256', $post); // Hash of the payload (empty string okay)
    
    // Create signing key, which is hashed four times, each time adding
    // more data to the key. Don't ask me why Amazon does it this way.
    $signKey = hash_hmac('sha256', $ymd, 'AWS4' . IAM_USER_SECRET, true);
    $signKey = hash_hmac('sha256', REGION, $signKey, true);
    $signKey = hash_hmac('sha256', 'execute-api', $signKey, true);
    $signKey = hash_hmac('sha256', 'aws4_request', $signKey, true);
    
    // Create a String-to-Sign, which indicates the hash that is used and
    // some data about the request, including the canonical request from above.
    $stringToSign = 'AWS4-HMAC-SHA256' . "\n"
    . $date . "\n"
    . $ymd . '/' . REGION . '/execute-api/aws4_request' . "\n"
    . hash('sha256', $canonicalRequest);
    
    // Sign the string with the key, which will create the signature
    // you'll need for the authorization header.
    $signature = hash_hmac('sha256', $stringToSign, $signKey);
    
    // Create Authorization header, which is the final step. It does NOT use
    // newlines to separate the data; it is all one line, just broken up here
    // for easier reading.
    $authorization = 'AWS4-HMAC-SHA256 '
    //. 'Credential=' . IAM_USER_KEY . '/' . $ymd . '/'
    // . REGION . '/execute-api/aws4_request,'
    . 'SignedHeaders=host;user-agent;x-amz-access-token;x-amz-date,'
    . 'Signature=' . $signature;
    
    // Create the header array for the cURL request. The headers must be
    // in alphabetical order. You must include all of the headers that were
    // in the canonical request above, plus you add in the authorization header
    // and an optional content-type header (for POST requests with JSON payload).
    $headers = [];
    $headers[] = 'authorization:' . $authorization;
    if ($post) $headers[] = 'content-type:application/json;charset=utf-8';
    $headers[] = 'host:' . HOST;
    //$headers[] = 'user-agent:' . USER_AGENT;
    $headers[] = 'x-amz-access-token:' . $accessToken;
    $headers[] = 'x-amz-date:' . $date;
    
    // Run the http request and capture the status code
    $status = '';
    $fullUrl = ENDPOINT . $path . ($qs ? '?' . $qs : '');
    $result = httpRequest($fullUrl, $post, $headers, $status);
    
    // Validate the response
    if (strpos($result, 'Error:') === 0) exit($result);
    if (empty($result)) exit('Error: Empty response');
    if ($status != 200) exit('Error: Status code ' . $status . ': ' . $result);
    if (strpos($result, '{') !== 0) exit('Error: Invalid JSON: ' . $result);
    
    // Decode json and return it
    $json = json_decode($result, true);
    if (!$json) exit('Error: Problem decoding JSON: ' . $result);
    return $json;
}


function getOrders($days=2) {
    $datetime = gmdate("Y-m-d\TH:i:s\Z", strtotime(" -{$days} day"));
    $method = 'GET';
    $url = '/orders/v0/orders';
    $qs = 'CreatedAfter='.$datetime.'&MarketplaceIds=' . MP_ID;
    //echo $qs;
    return amazonRequest($method, $url, $qs);
}


function getOrderBuyerInfo($orderId) {
    $method = 'GET';
    $url = "/orders/v0/orders/{$orderId}/buyerInfo";
    $qs = 'BuyerName&BuyerEmail';
    //echo $qs;
    return amazonRequest($method, $url, $qs);
}


function getOrderItemsInfo($orderId) {
    $method = 'GET';
    $url = "/orders/v0/orders/{$orderId}/orderItems";
    $qs = '';
    //echo $qs;
    return amazonRequest($method, $url, $qs);
}



function getOrderAddressInfo($orderId) {
    $method = 'GET';
    $url = "/orders/v0/orders/{$orderId}/address";
    $qs = '';
    //echo $qs;
    return amazonRequest($method, $url, $qs);
}

function getOrderInfo($orderId) {
    $method = 'GET';
    $url = "/orders/v0/orders/{$orderId}";
    $qs = '';
    //echo $qs;
    return amazonRequest($method, $url, $qs);
}

function processOrder($orderPayload)
	{
	    $order = $orderPayload['payload'];
		if($order['OrderStatus'] != "Canceled" && $order['OrderStatus'] != "Pending")
		{
			$aouth = md5('www.amazon.com');
			$icon 						= getFcmMessageIcon($aouth);
			$web_order_website_id 		= getWebsiteIdByAouth($aouth);
			$web_order_website_id 		= $web_order_website_id ? $web_order_website_id : 11;						
				
			$web_order_number 			= isset($order['AmazonOrderId'])	? getData($order['AmazonOrderId'])		:	'N/A';
			
			$websiteOrder =  new WebsiteOrder();
			$isOrderExist = $websiteOrder->isOrderExist($web_order_number, $web_order_website_id);
			
			if(!$isOrderExist)
			{
			
				$productData = array();
				$web_order_total_shipping = 0;
				$web_order_currency 		= isset($order['OrderTotal']['CurrencyCode'])	? getData($order['OrderTotal']['CurrencyCode'])		:	'XXX';	
				$products = getOrderItemsInfo($web_order_number);
				/*
				echo "<pre>";
				print_r($order);
				echo "</pre>";
				echo "<pre>";
				print_r($products);
				echo "</pre>";
				echo "<br/><br/><br/>";
				*/
				if($products)
				{
					if(count($products))
					{
					    $productDataArray = $products['payload']['OrderItems'] ?? null;
					    if($productDataArray != null)
						foreach($productDataArray as $_product)
						{
						    for($i=0; $i< $_product['QuantityOrdered']; $i++)
						    {
    							$productData[] = array(
    								"wo_product_name" 			=> getData($_product['Title']),
    								"wo_product_sku" 			=> getData($_product['SellerSKU']),
    								"wo_product_quantity" 		=> 1,
    								"wo_product_sell_price" 	=> getData($_product['ItemPrice']['Amount']/$_product['QuantityOrdered']),
    								"wo_product_sell_base_price" => getBaseAmount(getData($_product['ItemPrice']['Amount']/$_product['QuantityOrdered']), $web_order_currency),
    								"wo_product_image"			=> AMAZON_PRODUCT_IMAGE,
    								"wo_product_premium"		=> (isset($order['IsPremiumOrder']) && $order['IsPremiumOrder'] == true) ? 1 : 0,
    								"wo_process_code"			=> ""
    							);
						    }
							$web_order_total_shipping += isset($_product['ShippingPrice']) ? $_product['ShippingPrice']['Amount']:0;
						}
					}
				}
				if(count($productData))
				{							
					$amazon_address = $order['ShippingAddress'];
					$customer_fname = isset($amazon_address['Name']) ? substr($amazon_address['Name'], 0, strpos($amazon_address['Name'], ' ')) : "Amazon";
					$customer_lname = isset($amazon_address['Name']) ? substr($amazon_address['Name'], strpos($amazon_address['Name'], ' ')) : "User";
					$customer_email = $order['BuyerInfo']['BuyerEmail'];
					$customer_phone = '';
					$customer_company = '';
					$customer_address_postcode = $amazon_address['PostalCode'];
					$customer_address_street_number = isset($amazon_address['AddressLine1']) ? $amazon_address['AddressLine1'] : "N/A";
					$customer_address_route = "";
					$customer_address_locality = isset($amazon_address['City']) ? $amazon_address['City'] : "N/A";
					$customer_address_administrative_area = isset($amazon_address["StateOrRegion"]) ? $amazon_address["StateOrRegion"] : "N/A";
					$customer_address_country = Country::getValueByParameter('name', 'iso2', $amazon_address['CountryCode']);
					$customer_address_geo_location = "";
					$web_order_total_amount 	= isset($order['OrderTotal']['Amount'])		? getData($order['OrderTotal']['Amount'])		:	0;
					
					
					$web_order_payment_method 	= isset($order['PaymentMethod'])	? getData($order['PaymentMethod'])		:	'N/A';
					
					$web_order_is_paid 			= isset($order['ISPAID'])	? getData($order['ISPAID'])		:	'Yes';
											
					$web_order_total_quantity 	= $order['NumberOfItemsShipped'] + $order['NumberOfItemsUnshipped'];
					$web_order_status = STATUS_PENDING;
					$web_order_created_date = date('Y-m-d H:i:s', strtotime($order['PurchaseDate']));						
					
					if($customer_email == "")
						$customer_email = $customer_phone != "" ? $customer_phone : 'anonymous-'.time();
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
						"web_order_status" => $web_order_status
						)
					);
					
					
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
			}
		}
		return $successImportOrder;
	}