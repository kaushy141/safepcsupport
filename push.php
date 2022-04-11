<?php include("setup.php"); ?>
<?php
$DeviceManager = new DeviceManager();
$firebaseIds = $DeviceManager->getAllUserDevice();
$registration_ids = explode("," , $firebaseIds);

$DATA = sanitizePostData($_POST);
Log::Text(json_encode($DATA));
$title 		= isset($DATA['TITLE'])	? getData($DATA['TITLE'])	:	NULL;
$message 	= isset($DATA['BODY'])	? getData($DATA['BODY'])	:	NULL;
$aouth		= isset($DATA['AOUTH'])	? getData($DATA['AOUTH'])	:	NULL;
$url 		= isset($DATA['URL'])	? getData($DATA['URL'])		:	NULL;
$created_date 	= isset($DATA['CREATED_DATE'])	? getData($DATA['CREATED_DATE']) :	NULL;

$icon 						= getFcmMessageIcon($aouth);
$web_order_website_id 		= getWebsiteIdByAouth($aouth);
$status = 1;

if($title!=NULL && $message!=NULL && $aouth!=NULL && (in_array($DATA['AOUTH'], 
	array(
				md5("www.mac4sale.co.uk"), 
				md5("www.consolekiller.co.uk"), 
				md5("www.technocart.co.uk"), 
				md5("www.refurbmac.co.uk"), 
				md5("www.safepcdirect.co.uk"), 
				md5("www.consolekillerpc.co.uk"),
				md5("www.hotspotmobiles.com"),
				md5("www.mac4sale.ae")
		)
)))
{
	$customer_fname = $customer_lname = $customer_email = $customer_phone = $customer_company = $customer_address_postcode = $customer_address_street_number = $customer_address_route = $customer_address_locality = $customer_address_administrative_area = $customer_address_country = $customer_address_geo_location = "";
	
	$customer_fname 	= isset($DATA['CUSTOMER']['first_name'])	? getData($DATA['CUSTOMER']['first_name'])		:	"";
	$customer_lname 	= isset($DATA['CUSTOMER']['last_name'])		? getData($DATA['CUSTOMER']['last_name'])		:	"";
	$customer_email 	= isset($DATA['CUSTOMER']['email'])			? getData($DATA['CUSTOMER']['email'])			:	"";
	$customer_phone 	= isset($DATA['CUSTOMER']['phone'])			? getData($DATA['CUSTOMER']['phone'])			:	"";
	$customer_company 	= isset($DATA['CUSTOMER']['company'])		? getData($DATA['CUSTOMER']['company'])			:	"";
	
	$CustAddData = isset($DATA['CUSTOMER']['address'])	? $DATA['CUSTOMER']['address']	:	NULL;
	
	$customer_address_postcode 				= isset($CustAddData['postcode'])	? getData($CustAddData['postcode'])	:	"";
	$customer_address_street_number 		= isset($CustAddData['housenum'])	? getData($CustAddData['housenum'])	:	"";
	$customer_address_route 				= isset($CustAddData['street'])		? getData($CustAddData['street'])	:	"";
	$customer_address_locality 				= isset($CustAddData['city'])		? getData($CustAddData['city'])		:	"";
	$customer_address_administrative_area 	= isset($CustAddData['region'])		? getData($CustAddData['region'])	:	"";
	$customer_address_country 				= isset($CustAddData['country'])	? getData($CustAddData['country'])	:	"";
	
	$customer_address_route = trim(trim($customer_address_route, $customer_address_street_number));
	
	$web_order_total_amount 	= isset($DATA['TOTAL'])		? getData($DATA['TOTAL'])		:	0;
	$web_order_total_shipping 	= isset($DATA['SHIPPING'])	? getData($DATA['SHIPPING'])		:	0;
	
	$web_order_payment_method 	= isset($DATA['PAYMENT'])	? getData($DATA['PAYMENT'])		:	0;
	$web_order_number 			= isset($DATA['ORDERID'])	? getData($DATA['ORDERID'])		:	0;
	$web_order_is_paid 			= isset($DATA['ISPAID'])	? getData($DATA['ISPAID'])		:	'Unknown';
	$web_order_currency 		= isset($DATA['CURRENCY'])	? getData($DATA['CURRENCY'])		:	'XXX';
	$webpush_products 			= isset($DATA['PRODUCT'])	? array_filter($DATA['PRODUCT'])	: 	array();
	$web_order_total_quantity 	= count($webpush_products);
	$web_order_status = STATUS_PENDING;
	$websiteOrder =  new WebsiteOrder();
	
	if(defined('ENABLE_NEWPAY_PENDING_ORDER') && !ENABLE_NEWPAY_PENDING_ORDER && $web_order_payment_method == 'retailfinance' && $web_order_is_paid != 'Yes'){
		die;
	}
	
	
	if(!$websiteOrder->isOrderExist($web_order_number, $web_order_website_id))
	{
		if($customer_email == "")
			$customer_email = $customer_phone != "" ? $customer_phone : 'anonymous-'.time();
		$customer_email = strtolower($customer_email);
		$Customer        = new Customer(0);
		$web_order_customer_id  = $Customer->isEmailExists($customer_email);
		if (!$web_order_customer_id) {
			$customer_image  = DEFAULT_USER_IMAGE;
			$customer_type_id = WEBSITE_CUSTOMER;
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
				"web_order_created_date" => $created_date == NULL ? "NOW()" : $created_date,
				"web_order_status" => $web_order_status
				)
			);
			
		$websiteOrderProduct = new WebsiteOrderProduct();
		$websiteOrderProduct->wo_web_order_id = $web_order_id;
		if(count($webpush_products))
		{
			foreach($webpush_products as $product)
			{
				if(!empty($product))
				{
					$wo_product_name 	 	= isset($product['name'])	? getData($product['name'])		:	"";
					$wo_product_sku	 	 	= isset($product['sku'])	? getData($product['sku'])		:	"";
					$wo_product_quantity 	= isset($product['qty'])	? getData($product['qty'])		:	"";
					$wo_product_sell_price 	= isset($product['price'])	? getData($product['price'])	:	"";
					$wo_product_url 		= isset($product['url'])	? getData($product['url'])		:	"";
					$wo_product_image 		= isset($product['image'])	? getData($product['image'])	:	"";
					$wo_product_premium 	= isset($product['premium'])? getData($product['premium'])	:	"0";
					$wo_product_options		= (isset($product['options']) && !empty($product['options']))? sanitizePostData(json_encode($product['options']))	:	"NULL";
					$wo_process_code 	 	= "";
					
					for($i=0; $i< $wo_product_quantity; $i++){
						$websiteOrderProduct->insert(array(
								"wo_web_order_id" 		=> $websiteOrderProduct->wo_web_order_id,
								"wo_product_name" 		=> $wo_product_name,
								"wo_product_sku" 		=> $wo_product_sku,
								"wo_product_quantity" 	=> 1,
								"wo_product_sell_price" => $wo_product_sell_price,
								"wo_product_sell_base_price" => getBaseAmount($wo_product_sell_price, $web_order_currency),
								"wo_product_url" 		=> $wo_product_url,
								"wo_product_image" 		=> $wo_product_image,
								"wo_product_options"	=> $wo_product_options,
								"wo_product_premium" 	=> $wo_product_premium,
								"wo_process_code" 		=> $wo_process_code
							)
						);
					}
				}
			}
		}
		
		send_android_notification($registration_ids, $title, $message, $icon, $url, $status);
		WebsiteOrder::checkFulfillment();
	}
}



function send_android_notification($registration_ids, $title, $message, $icon, $url, $status) 
	{
		$orderNotification = new OrderNotification();
		$orderNotification->add($title, $message, $url, $icon, $status);
	
		$fields = array(
		'registration_ids' => $registration_ids,
			"notification" => array(
			  "title" 	=> $title,
			  "body" 	=> $message,
			  "icon" 	=> $icon    
			  ),
			  "data" 	=> array(
							  "title" 			=> $title,
							  "message" 		=> $message,
							  "is_background" 	=> false,
							  "timestamp" 		=> time(),
							  "imageUrl" 		=> $icon
							)
	
		);
		$headers = array(
		'Authorization: key='.GOOGLE_FIREBASE_API_AUTHERISATION_KEY, 
		'Content-Type: application/json'
		);
	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, GOOGLE_FIREBASE_API_URL );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		curl_exec($ch );	
		curl_close( $ch );
}
?>